<?php

namespace App\Http\Controllers\Cabinet;

use App\Entity\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\CreateRequest;
use App\Http\Requests\Ticket\MessageRequest;
use App\Usecases\Tickets\TicketService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    private $service;

    public function __construct(TicketService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $tickets = Ticket::forUser(Auth::user())->orderByDesc('updated_at')->paginate(20);

        return view('cabinet.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        return view('cabinet.tickets.show', compact('ticket'));
    }

    public function create()
    {
        return view('cabinet.tickets.create');
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        try {
            $ticket = $this->service->create(Auth::user(), $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.tickets.show', $ticket);
    }

    public function message(MessageRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->checkAccess($ticket);
        try {
            $this->service->message(Auth::user(), $ticket, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.tickets.show', $ticket);
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->checkAccess($ticket);
        try {
            $this->service->removeByOwner($ticket);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.tickets.index')
            ->with('success', 'Заявка удалена.');
    }

    private function checkAccess(Ticket $ticket): void
    {
        if (!Gate::allows('manage-own-ticket', $ticket)) {
            abort(403);
        }
    }
}
