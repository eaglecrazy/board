<?php

namespace App\Entity\Ticket;

use App\Entity\User\User;
use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Entity\Ticket\Ticket
 *
 * @property int $id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $subject
 * @property string $content
 * @property string $status
 * @method static Builder forUser(User $user)
 * @method static Ticket findOrFail(int $id)
 * @method static \Illuminate\Database\Query\Builder orderByDesc(Carbon $updated_at)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Ticket\Message[] $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Ticket\Status[] $statuses
 * @property-read int|null $statuses_count
 * @property-read User $user
 * @method static Builder|Ticket newModelQuery()
 * @method static Builder|Ticket newQuery()
 * @method static Builder|Ticket query()
 * @method static Builder|Ticket whereContent($value)
 * @method static Builder|Ticket whereCreatedAt($value)
 * @method static Builder|Ticket whereId($value)
 * @method static Builder|Ticket whereStatus($value)
 * @method static Builder|Ticket whereSubject($value)
 * @method static Builder|Ticket whereUpdatedAt($value)
 * @method static Builder|Ticket whereUserId($value)
 * @mixin \Eloquent
 */
class Ticket extends Model
{
    protected $table = 'ticket_tickets';

    protected $guarded = ['id'];

    public static function new(int $userId, string $subject, string $content): self
    {
        $ticket = self::create([
            'user_id' => $userId,
            'subject' => $subject,
            'content' => $content,
            'status' => Status::OPEN,
        ]);
        $ticket->setStatus(Status::OPEN, $userId);
        return $ticket;
    }

    private function setStatus($status, ?int $userId): void
    {
        $this->statuses()->create(['status' => $status, 'user_id' => $userId]);
        $this->update(['status' => $status]);
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(Status::class, 'ticket_id', 'id');
    }

    public static function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function isOpen(): bool
    {
        return $this->status === Status::OPEN;
    }

    public function canBeRemoved(): bool
    {
        return $this->isOpen();
    }

    public function isApproved(): bool
    {
        return $this->status === Status::APPROVED;
    }

    public function isClosed(): bool
    {
        return $this->status === Status::CLOSED;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function edit(string $subject, string $content): void
    {
        $this->update([
            'subject' => $subject,
            'content' => $content,
        ]);
    }

    public function approve(int $userId): void
    {
        if ($this->isApproved()) {
            throw new DomainException('Заявка уже на рассмотрении.');
        }
        $this->setStatus(Status::APPROVED, $userId);
    }

    public function close(int $userId): void
    {
        if ($this->isClosed()) {
            throw new DomainException('Заявка уже закрыта');
        }
        $this->setStatus(Status::CLOSED, $userId);
    }

    public function reopen(int $userId): void
    {
        if (!$this->isClosed()) {
            throw new DomainException('Заявка не закрыта.');
        }
        $this->setStatus(Status::APPROVED, $userId);
    }

    public function addMessage(int $userId, $message): void
    {
        if (!$this->allowsMessages()) {
            throw new DomainException('Заявка закрыта.');
        }
        $this->messages()->create([
            'user_id' => $userId,
            'message' => $message,
        ]);
        $this->update();
    }

    public function allowsMessages(): bool
    {
        return !$this->isClosed();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'ticket_id', 'id');
    }
}
