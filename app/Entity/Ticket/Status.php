<?php

namespace App\Entity\Ticket;

use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Entity\Ticket\Status
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $status
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Status query()
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Status whereUserId($value)
 * @mixin \Eloquent
 */
class Status extends Model
{
    public const OPEN = 'open';
    public const APPROVED = 'approved';
    public const CLOSED = 'closed';

    protected $table = 'ticket_statuses';

    protected $guarded = ['id'];

    public static function statusesList(): array
    {
        return [
            self::OPEN => 'Открыта',
            self::APPROVED => 'В обработке',
            self::CLOSED => 'Закрыта',
        ];
    }

    public function isOpen(): bool
    {
        return $this->status === self::OPEN;
    }

    public function isApproved(): bool
    {
        return $this->status === self::APPROVED;
    }

    public function isClosed(): bool
    {
        return $this->status === self::CLOSED;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
