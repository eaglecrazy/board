<?php

namespace App\Entity\Adverts\Advert;

use App\Entity\Adverts\Category;
use App\Entity\Adverts\Advert\Photo;
use App\Entity\Region;
use App\Entity\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


/**
 * App\Entity\Adverts\Advert\Advert
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property int $region_id
 * @property string $title
 * @property int $price
 * @property string $address
 * @property string $content
 * @property string $status
 * @property string|null $reject_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property-read \App\Entity\Adverts\Category $category
 * @property-read \App\Entity\Region $region
 * @property-read \App\Entity\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereRejectReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Adverts\Advert\Value[] $photos
 * @property-read int|null $photos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Adverts\Advert\Value[] $values
 * @property-read int|null $values_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert Active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert forActive()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert forUser(\App\Entity\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert forRegion(\App\Entity\Region $region)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\Adverts\Advert\Advert forCategory(\App\Entity\Adverts\Category $category)
 */
class Advert extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'closed';

    protected $table = 'advert_adverts';

    protected $fillable = ['user_id', 'category_id', 'region_id', 'title', 'price', 'address', 'content', 'status', 'reject_reason', 'published_at', 'expires_at',];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function getValue($attributeId)
    {
        $result = null;
        foreach ($this->values as $value) {
            if ($value->attribute_id === $attributeId) {
                return $value->value;
            }
        }
    }

//    --------------------
//    Ограничения
//    --------------------

    public static function scopeForUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    public static function scopeForRegion(Builder $query, Region $region)
    {
        $ids = [$region->id];
        $childrenIds = $ids;

        while ($childrenIds = Region::where(['parent_id' => $childrenIds])->pluck('id')->toArray()) {
            asort($childrenIds);
            $ids = array_merge($ids, $childrenIds);
        }
        asort($ids);
        return $query->whereIn('region_id', $ids);
    }

    public static function scopeForCategory(Builder $query, Category $category)
    {
        return $query->whereIn('category_id', array_merge(
            [$category->id],
            //получаем всех потомков, и только столбец id, и из него делаем массив
            $category->descendants()->pluck('id')->toArray()
        ));
    }

    public static function scopeActive(Builder $query)
    {
        return $query->where('status', static::STATUS_ACTIVE);
    }

    public static function scopeFavoredByUser(Builder $query, User $user)
    {
        return $query->whereHas('favorites', function (Builder $query) use ($user) {
           $query->where('user_id', $user->id);
        });
    }


//    --------------------
//    Статусы
//    --------------------
    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_MODERATION => 'On Moderation',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isModeration(): bool
    {
        return $this->status === self::STATUS_MODERATION;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

//    --------------------
//    Связи
//    --------------------
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

    public function values()
    {
        return $this->hasMany(Value::class, 'advert_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class, 'advert_id', 'id');
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'advert_favorites', 'advert_id', 'user_id');
    }


//    --------------------
//    Изменения статусов
//    --------------------

    public function sendToModeration(): void
    {
        if (!($this->isDraft() || $this->isClosed())) {
            throw new \DomainException('Advert is not draft.');
        }
//TODO Эту проверку нужно вернуть, когда будут фотки
//        if (!$this->photos()->count()) {
//            throw new \DomainException('You need to upload photos.');
//        }

        $this->update([
            'status' => self::STATUS_MODERATION,
        ]);
    }

    public function moderate(Carbon $date): void
    {
        if ($this->status !== self::STATUS_MODERATION) {
            throw new \DomainException('Advert is not sent to moderation.');
        }
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'published_at' => $date,
            'expires_at' => $date->copy()->addMonth(1),
        ]);
    }

    public function reject($reason): void
    {
        $this->update(
            ['status' => self::STATUS_DRAFT,
                'reject_reason' => $reason
            ]);
    }

    public function expire(): void
    {
        $this->update(
            ['status' => self::STATUS_CLOSED]);
    }

    public function close(): void
    {
        $this->update([
            'status' => self::STATUS_CLOSED,
        ]);
    }




//    --------------------
//    Другое
//    --------------------

    public function isAllowToShow()
    {
        return $this->isActive() || Gate::allows('show-advert', $this);
    }

}

