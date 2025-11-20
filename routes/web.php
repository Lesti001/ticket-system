<?php

use Illuminate\Support\Facades\Route;
use App\Models\Event;
use App\Models\Seat;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SeatController;

Route::get('/', function () {

    $events = Event::withCount('tickets')
        ->where('event_date_at', '>', now())
        ->orderBy('event_date_at', 'asc')
        ->paginate(5);

    $totalSeats = Seat::count();

    return view('welcome', [
        'events' => $events,
        'totalSeats' => $totalSeats,
    ]);
})->name('welcome');

Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

Route::get('/events/{event}/purchase', [EventController::class, 'showPurchaseForm'])
    ->middleware('auth')
    ->name('events.purchase.form');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::post('/events/{event}/purchase', [EventController::class, 'purchaseTickets'])
    ->middleware('auth')
    ->name('events.purchase');

Route::get('/my-tickets', [TicketController::class, 'myTickets'])
    ->middleware('auth')
    ->name('tickets.my');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');

    Route::post('/events', [EventController::class, 'store'])->name('events.store');

    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');

    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');

    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::resource('seats', SeatController::class);

    Route::get('/tickets/admission', [TicketController::class, 'showAdmissionForm'])->name('tickets.admission');

    Route::post('/tickets/admission', [TicketController::class, 'admitTicket'])->name('tickets.admit');
});

require __DIR__ . '/auth.php';