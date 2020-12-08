<?php


namespace App\Entity\Banner;


use Illuminate\Database\Eloquent\Model;

class Banner
{
    public $user_id;
    public $id;


    public const STATUS_DRAFT = 'draft';
    public const STATUS_MODERATION = 'moderation';
    public const STATUS_MODERATED = 'moderated';
    public const STATUS_ORDERED = 'ordered';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CLOSED = 'close';

    public static function statusesList(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_MODERATION => 'On Moderation',
            self::STATUS_MODERATED => 'moderated',
            self::STATUS_ORDERED => 'Payment',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    public static function formatsList(): array
    {
        return ['240x400'];
    }

    public static function forUser(?\App\Entity\User $user): Model
    {

    }

    public static function orderByDesc(string $string): \Illuminate\Database\Query\Builder
    {
    }

    public function canBeChanged(): bool
    {
        return true;
    }
}
