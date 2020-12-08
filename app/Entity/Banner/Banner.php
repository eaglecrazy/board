<?php


namespace App\Entity\Banner;


use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Banner extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_MODERATED = 'moderated';
    public const STATUS_ORDERED = 'ordered';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'close';

    protected $table = 'banner_banners';
    protected $guarded = 'id';
    protected $casts = ['published_at' => 'datetime'];


//    --------------------
//    Изменения статусов
//    --------------------

    public function cancelModeration(): void
    {
        if (!$this->isOnModeration()) {
            throw new \DomainException('Этот баннер не находится на модерации.');
        }
        $this->update([
            'status' => self::STATUS_MODERATION,
        ]);
    }

    public function moderate(): void
    {
        if (!$this->isOnModeration()) {
            throw new \DomainException('Этот баннер не находится на модерации.');
        }
        $this->update([
            'status' => self::STATUS_MODERATED,
        ]);

    }

    public function order(int $cost): void
    {
        if (!$this->isModerated()) {
            throw new \DomainException('Баннер не прошёл модерацию.');
        }
        $this->update([
            'cost' => $cost,
            'status' => self::STATUS_ORDERED,
        ]);
    }

    public function pay(Carbon $date): void
    {
        if (!$this->isOrdered()) {
            throw new \DomainException('Баннер не входит в заказ.');
        }
        $this->update([
            'published_at' => $date,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function reject($reason): void
    {
        $this->update([
            'status' => self::STATUS_DRAFT,
            'reject_reason' => $reason,
        ]);
    }

    public function sendToModeration(): void
    {
        if (!$this->isDraft()) {
            throw new \DomainException('Этот баннер не черновик.');
        }
        $this->update([
            'status' => self::STATUS_MODERATION,
        ]);
    }

//    --------------------
//    Ограничения
//    --------------------

    function scopeActive(Builder $query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    function scopeForUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

//    --------------------
//    Разное
//    --------------------
    public function assertIsActive(): void
    {
        if(!$this->isActive()){
            throw new \DomainException('Баннер не активен.');
        }
    }

    public function canBeChanged(): bool
    {
        return $this->isDraft();
    }

    public function canBeRemoved(): bool
    {
        return !$this->isActive();
    }

//    --------------------
//    Связи
//    --------------------

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

//    --------------------
//    Статусы
//    --------------------
    private function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    private function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    private function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    private function isModerated(): bool
    {
        return $this->status === self::STATUS_MODERATED;
    }

    private function isOnModeration(): bool
    {
        return $this->status === self::STATUS_MODERATION;
    }

    private function isOrdered(): bool
    {
        return $this->status === self::STATUS_ORDERED;
    }

    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_MODERATION => 'On Moderation',
            self::STATUS_MODERATED => 'Moderated',
            self::STATUS_ORDERED => 'Payment',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CLOSED => 'Closed',
        ];
    }


//    --------------------
//    Форматы
//    --------------------
    public static function formatsList(): array
    {
        return ['240x400'];
    }

    public function getHeight(): int
    {
        return explode('x', $this->format)[1];
    }

    public function getWidth(): int
    {
        return explode('x', $this->format)[0];
    }
}
