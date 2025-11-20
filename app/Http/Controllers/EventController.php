<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Seat;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('events.show', ['event' => $event]);
    }

    public function showPurchaseForm(Event $event)
    {
        $allSeats = Seat::all();
        $takenSeatIds = Ticket::where('event_id', $event->id)
            ->pluck('seat_id')
            ->toArray();

        $userTicketsCount = Ticket::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->count();

        $remainingTicketsAllowed = $event->max_number_allowed - $userTicketsCount;

        $priceData = [
            'isDynamic' => $event->is_dynamic_price,
            'daysUntil' => Carbon::now()->diffInDays($event->event_date_at, false),
            'occupancy' => $allSeats->count() > 0 ? (count($takenSeatIds) / $allSeats->count()) : 0,
        ];

        return view('events.purchase', [
            'event' => $event,
            'allSeats' => $allSeats,
            'takenSeatIds' => $takenSeatIds,
            'remainingTicketsAllowed' => $remainingTicketsAllowed,
            'priceData' => $priceData,
        ]);
    }

    public function edit(Event $event)
    {
        return view('events.edit', ['event' => $event]);
    }
    
    public function update(Request $request, Event $event)
    {
        $hasSaleStarted = $event->sale_start_at->isPast();

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'cover_image' => 'nullable|image|max:2048',
        ];

        if (!$hasSaleStarted) {
            $rules['event_date_at'] = 'required|date';
            $rules['sale_start_at'] = 'required|date|before:sale_end_at';
            $rules['sale_end_at'] = 'required|date|after:sale_start_at|before:event_date_at';
            $rules['max_number_allowed'] = 'required|integer|min:1';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('cover_image')) {
            if ($event->cover_image_path) {
                Storage::disk('public')->delete($event->cover_image_path);
            }
            $event->cover_image_path = $request->file('cover_image')->store('covers', 'public');
        }

        $event->title = $validated['title'];
        $event->description = $validated['description'];

        if (!$hasSaleStarted) {
            $event->event_date_at = $validated['event_date_at'];
            $event->sale_start_at = $validated['sale_start_at'];
            $event->sale_end_at = $validated['sale_end_at'];
            $event->max_number_allowed = $validated['max_number_allowed'];
            $event->is_dynamic_price = $request->has('is_dynamic_price');
        }

        $event->save();

        return redirect()->route('admin.dashboard')->with('status', 'Event updated successfully!');
    }
    
    public function destroy(Event $event)
    {
        if ($event->tickets()->exists()) {
            return back()->with('error', 'Cannot delete event because tickets have already been sold.');
        }

        if ($event->cover_image_path) {
            Storage::disk('public')->delete($event->cover_image_path);
        }

        $event->delete();

        return back()->with('status', 'Event deleted successfully!');
    }
    
    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'event_date_at' => 'required|date',
            'sale_start_at' => 'required|date|before:sale_end_at',
            'sale_end_at' => 'required|date|after:sale_start_at|before:event_date_at',
            'max_number_allowed' => 'required|integer|min:1',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('covers', 'public');
        }

        Event::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'event_date_at' => $validated['event_date_at'],
            'sale_start_at' => $validated['sale_start_at'],
            'sale_end_at' => $validated['sale_end_at'],
            'max_number_allowed' => $validated['max_number_allowed'],
            'is_dynamic_price' => $request->has('is_dynamic_price'),
            'cover_image_path' => $path,
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Event created successfully!');
    }

    public function purchaseTickets(Request $request, Event $event)
    {
        $user = Auth::user();
        $allSeats = Seat::all();

        $takenSeatIds = Ticket::where('event_id', $event->id)
            ->pluck('seat_id')
            ->toArray();

        $userTicketsCount = Ticket::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->count();

        $remainingTicketsAllowed = $event->max_number_allowed - $userTicketsCount;

        $validated = $request->validate([
            'seats' => ['required', 'array'],
            'seats.*' => [
                'exists:seats,id',
                Rule::notIn($takenSeatIds),
            ],
        ]);

        if (count($validated['seats']) > $remainingTicketsAllowed) {
            return back()->withErrors([
                'seats' => 'You cannot select more tickets than allowed (' . $remainingTicketsAllowed . ').'
            ])->withInput();
        }

        $priceData = [
            'isDynamic' => $event->is_dynamic_price,
            'daysUntil' => Carbon::now()->diffInDays($event->event_date_at, false),
            'occupancy' => $allSeats->count() > 0 ? (count($takenSeatIds) / $allSeats->count()) : 0,
        ];


        try {
            DB::transaction(function () use ($validated, $user, $event, $priceData) {

                foreach ($validated['seats'] as $seatId) {
                    $seat = Seat::find($seatId);

                    $finalPrice = $event->calculatePrice($seat, $priceData);

                    Ticket::create([
                        'barcode' => fake()->unique()->numerify('#########'),
                        'admission_time' => null,
                        'user_id' => $user->id,
                        'event_id' => $event->id,
                        'seat_id' => $seat->id,
                        'price' => $finalPrice,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors([
                'seats' => 'An error occurred while processing your purchase. Please try again.'
            ])->withInput();
        }

        return redirect()->route('tickets.my')
            ->with('status', 'Tickets purchased successfully!');
    }

}