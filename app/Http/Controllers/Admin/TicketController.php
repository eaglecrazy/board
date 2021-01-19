<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Ticket\Status;
use App\Entity\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\EditRequest;
use App\Http\Requests\Ticket\MessageRequest;
use App\Usecases\Tickets\TicketService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    private $service;

    public function __construct(TicketService $service)
    {
        $this->service = $service;
        $this->middleware('can:manage-tickets');
    }

    public function index(Request $request)
    {
        $query = Ticket::orderByDesc('updated_at');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('user'))) {
            $users_ids = \App\Entity\User\User::where('name', 'like', '%' . $value . '%')
                ->orWhere(
                    function ($query) use ($value) {
                        $query->where('last_name', 'like', '%' . $value . '%');
                    })
                ->get()
                ->pluck('id');
            $query->whereIn('user_id', $users_ids);
        }

        if (!empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }

        $tickets = $query->paginate(20);

        $statuses = Status::statusesList();

        return view('admin.tickets.index', compact('tickets', 'statuses'));
    }

    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', compact('ticket'));
    }

    public function editForm(Ticket $ticket)
    {
        return view('admin.tickets.edit', compact('ticket'));
    }

    public function edit(EditRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $this->service->edit($ticket, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Заявка отредактирована.');
    }

    public function message(MessageRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $this->service->message(Auth::user(), $ticket, $request);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }
        return back();
    }

    public function approve(Ticket $ticket): RedirectResponse
    {
        try {
            $this->service->approve(Auth::user(), $ticket);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Заявка принята в обработку.');
    }

    public function close(Ticket $ticket): RedirectResponse
    {
        try {
            $this->service->close(Auth::user(), $ticket);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Заявка закрыта.');
    }

    public function reopen(Ticket $ticket): RedirectResponse
    {
        try {
            $this->service->reopen(Auth::user(), $ticket);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Заявка открыта заново.');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        try {
            $this->service->removeByAdmin($ticket);
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.tickets.index')->with('success', 'Заявка удалена.');
    }
}
