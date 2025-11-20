<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEvents = Event::count();

        $totalTicketsSold = Ticket::count();

        $totalRevenue = Ticket::sum('price');

        $topSeats = Ticket::select('seat_id', DB::raw('count(*) as sold_count'))
            ->groupBy('seat_id')
            ->orderByDesc('sold_count')
            ->limit(3)
            ->with('seat')
            ->get();

        $totalSeatsCount = Seat::count();

        $events = Event::withCount('tickets')
            ->withSum('tickets', 'price')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('admin.dashboard', [
            'totalEvents' => $totalEvents,
            'totalTicketsSold' => $totalTicketsSold,
            'totalRevenue' => $totalRevenue,
            'topSeats' => $topSeats,
            'events' => $events,
            'totalSeatsCount' => $totalSeatsCount,
        ]);
    }
}