<?php


namespace App\Entity\Banner;


use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User\User ;
use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Gate;

/**
 * App\Entity\Banner\Banner
 *
 * @property-read Category $category
 * @property-read Region $region
 * @property-read User $user
 * @property-read string $name
 * @property-read string $file
 * @property-read string $url
 * @property-read string $limit
 * @property-read string $title
 * @property-read string $content
 * @property-read string $format
 * @property-read string status
 * @property-read integer $id
 * @property-read integer $price
 * @property-read integer $views
 * @property-read integer $clicks
 * @property-read Carbon $published_at
 * @method static Builder|\App\Entity\Banner\Banner active()
 * @method static Builder|\App\Entity\Banner\Banner forUser(User $user)
 * @method static Builder|\App\Entity\Banner\Banner newModelQuery()
 * @method static Builder|\App\Entity\Banner\Banner newQuery()
 * @method static Builder|\App\Entity\Banner\Banner query()
 * @mixin Eloquent
 */
class Banner extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_MODERATED = 'moderated';
    public const STATUS_ORDERED = 'ordered';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'close';

    protected $table = 'banners_banners';
    protected $guarded = ['id'];
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
            'status' => self::STATUS_DRAFT,
        ]);
    }

    public function moderate(): void
    {
        if (!$this->isOnModeration()) {
            throw new \DomainException('Этот баннер не находится на модерации.');
        }
        $this->update([
            'status' => self::STATUS_MODERATED,
            'reject_reason' => null,
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

    function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    function scopeForUser(Builder $query, User $user): Builder
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

    public function click(){
        $this->assertIsActive();
        $this->clicks++;
        $this->save();
    }

    public function view(): void
    {
        $this->assertIsActive();
        $this->views++;
        if($this->views >= $this->limit){
            $this->status = self::STATUS_CLOSED;
        }
        $this->save();
    }

//    --------------------
//    Связи
//    --------------------

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

//    --------------------
//    Статусы
//    --------------------
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isModerated(): bool
    {
        return $this->status === self::STATUS_MODERATED;
    }

    public function isOnModeration(): bool
    {
        return $this->status === self::STATUS_MODERATION;
    }

    public function isOrdered(): bool
    {
        return $this->status === self::STATUS_ORDERED;
    }

    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT => 'Черновик',
            self::STATUS_MODERATION => 'Модерация',
            self::STATUS_MODERATED => 'Отмодерирован',
            self::STATUS_ORDERED => 'Ожидает оплаты',
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_CLOSED => 'Закрыт',
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
