<?php

namespace App\Usecases\Tickets;

use App\Entity\Ticket\Ticket;
use App\Entity\User\User;
use App\Http\Requests\Ticket\CreateRequest;
use App\Http\Requests\Ticket\EditRequest;
use App\Http\Requests\Ticket\MessageRequest;
use DomainException;

class TicketService
{
    public function create(User $user, CreateRequest $request): Ticket
    {
        return Ticket::new($user->id, $request['subject'], $request['content']);
    }

    public function edit(Ticket $ticket, EditRequest $request): void
    {
        $ticket->edit(
            $request['subject'],
            $request['content']
        );
    }

    public function message(User $user, Ticket $ticket, MessageRequest $request): void
    {
        $ticket->addMessage($user->id, $request['message']);
    }

    public function approve(User $user, Ticket $ticket): void
    {
        $ticket->approve($user->id);
    }

    public function close(User $user, Ticket $ticket): void
    {
        $ticket->close($user->id);
    }

    public function reopen(User $user, Ticket $ticket): void
    {
        $ticket->reopen($user->id);
    }

    public function removeByOwner(Ticket $ticket): void
    {
        if (!$ticket->canBeRemoved()) {
            throw new DomainException('Нельзя удалить активную заявку.');
        }
        $ticket->delete();
    }

    public function removeByAdmin(Ticket $ticket): void
    {
        $ticket->delete();
    }
}
