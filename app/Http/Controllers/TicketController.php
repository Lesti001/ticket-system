<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function myTickets()
    {
        $userId = Auth::id();        

        $tickets = Ticket::with(['event', 'seat'])
            ->where('user_id', $userId)
            ->get()
            ->sortBy('event.event_date_at')
            ->groupBy('event_id');

        return view('tickets.my', ['groupedTickets' => $tickets]);
    }

    public function showAdmissionForm()
    {
        return view('admin.tickets.admission');
    }

    public function admitTicket(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $ticket = Ticket::where('barcode', $request->barcode)->first();

        if (!$ticket) {
            return back()->with('error', 'Invalid barcode. Ticket not found.');
        }

        if ($ticket->admission_time) {
            return back()->with('error', 'Ticket already used at: ' . $ticket->admission_time->format('Y-m-d H:i:s'));
        }

        $ticket->update([
            'admission_time' => Carbon::now(),
        ]);

        return back()->with('status', 'Admission successful! Ticket validated.');
    }
}
