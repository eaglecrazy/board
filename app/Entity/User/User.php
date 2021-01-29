<?php

namespace App\Entity\User;

use App\Entity\Adverts\Advert\Advert;
use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Mail;


/**
 * App\Entity\User\User
 *
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $email
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property string|null $verify_token
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereVerifyToken($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereRole($value)
 * @property string|null $phone
 * @property bool $phone_verified
 * @property string|null $phone_verify_token
 * @property \Illuminate\Support\Carbon|null $phone_verify_token_expire
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  wherePhoneVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  wherePhoneVerifyToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  wherePhoneVerifyTokenExpire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  bySocialNetwork($value, $value)
 * @property bool $phone_auth
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User\User  wherePhoneAuth($value)
 * @property-read Collection|Advert[] $favorites
 * @property-read int|null $favorites_count
 * @property-read Collection|\App\Entity\User\SocialNetwork[] $socialNetworks
 * @property-read int|null $social_networks_count
 * @property-read Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    public const STATUS_WAIT = 'wait';
    public const STATUS_ACTIVE = 'active';
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MODERATOR = 'moderator';
    public const PHONE_VERIFY_TIME = 300;

//    public const PHONE_VERIFY_TIME = 5;


    public static function statusesList(): array
    {
        return [
            self::STATUS_WAIT => 'Ожидает',
            self::STATUS_ACTIVE => 'Активный',
        ];
    }

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'status',
        'verify_token',
        'role',
        'phone',
        'phone_verified',
        'phone_verify_token',
        'phone_verify_token_expire',
        'phone_auth',
    ];

    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified' => 'boolean',
        'phone_auth' => 'boolean',
        'phone_verify_token_expire' => 'datetime',
    ];


    //------------------
    // Favorites
    //------------------

    public function addToFavorites($advertId): void
    {
        if ($this->hasInFavorites($advertId)) {
            throw new DomainException('Объявление уже находится в избранном.');
        }
        $this->favorites()->attach($advertId);
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Advert::class, 'advert_favorites', 'user_id', 'advert_id');
        //можно написать короче
        //return $this->belongsToMany((Advert::class, 'advert_favorites');
    }

    public function removeFromFavorites($advertId): void
    {
        $this->favorites()->detach($advertId);
    }

    public function hasInFavorites($advertId): bool
    {
        return $this->favorites()->where('advert_id', $advertId)->exists();
    }



    //------------------
    // Register + Add
    //------------------

    public static function register(string $name, string $email, string $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'verify_token' => Str::random(),
            'status' => self::STATUS_WAIT,
        ]);
    }

    public static function new(string $name, string $email, string $password): self
    {
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'verify_token' => Str::random(),
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function verify(): void
    {
        if (!$this->isWait()) {
            throw new DomainException('Пользоваьель уже верифицирован.');
        }
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'verify_token' => null,
        ]);
    }

    //------------------
    // Roles
    //------------------

    public function changeRole($role): void
    {
        if (!in_array($role, self::rolesList(), true)) {
            throw new \InvalidArgumentException('Undefined role "' . $role . '"');
        }
        if ($this->role === $role) {
            throw new DomainException('Роль уже назначена');
        }
        $this->update(['role' => $role]);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isModerator(): bool
    {
        return $this->role === self::ROLE_MODERATOR;
    }

    public static function rolesList(): array
    {
        return [
            self::ROLE_USER => 'User',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_MODERATOR => 'Moderator',
        ];
    }

    //------------------
    // Phone
    //------------------
    public function unverifyPhone(): void
    {
        $this->phone_verified = false;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function requestPhoneVerification(Carbon $now): string
    {
        //если нет телефона
        if (empty($this->phone)) {
            throw new DomainException('Не введён номер телефона.');
        }
        //если уже есть токен, и есть время когда он закончится и оно больше чем сейчас
        //то есть токен отправили и получить другой ещё нельзя
        if (!empty($this->phone_verify_token) && $this->phone_verify_token_expire && $this->phone_verify_token_expire->gt($now)) {
            throw new DomainException('Код уже выслан. Новый можно запросить через ' . Carbon::now()->diffInSeconds($this->phone_verify_token_expire) . ' секунд.');
        }

        $this->phone_verified = false;
        $this->phone_verify_token = (string)random_int(10000, 99999);
        $this->phone_verify_token_expire = $now->copy()->addSeconds(static::PHONE_VERIFY_TIME);
        $this->saveOrFail();

        return $this->phone_verify_token;
    }

    public function verifyPhone($token, Carbon $now): void
    {
        if ($token !== $this->phone_verify_token) {
            throw new DomainException('Некорректный токен.');
        }
        if ($this->phone_verify_token_expire->lt($now)) {
            throw new DomainException('Токен просрочен.');
        }

        $this->phone_verified = true;
        $this->phone_verify_token = null;
        $this->phone_verify_token_expire = null;
        $this->saveOrFail();
    }

    public function isPhoneVerified(): bool
    {
        return $this->phone_verified;
    }

    public function isPhoneAuthEnabled(): bool
    {
        return $this->phone_auth;
    }

    public function notFilledPhone(): bool
    {
        return empty($this->phone);
    }


    //------------------
    // Social Networks
    //------------------
    public function socialNetworks(): HasMany
    {
        return $this->hasMany(SocialNetwork::class, 'user_id', 'id');
    }

    public function scopeBySocialNetwork(Builder $query, string $network, string $socialId): Builder
    {
        return $query->whereHas('socialNetworks', function (Builder $query) use ($network, $socialId) {
            $query->where('network', $network)->where('social_id', $socialId);
        });
    }

    public static function registerBySocialNetwork(string $socialNetwork, string $socialId)
    {
        return DB::transaction(function () use ($socialNetwork, $socialId) {
            $user = User::create([
                'name' => $socialId,
                'email' => null,
                'password' => null,
                'verify_token' => null,
                'role' => User::ROLE_USER,
                'status' => User::STATUS_ACTIVE,
            ]);
            $user->socialNetworks()->create([
                'network' => $socialNetwork,
                'social_id' => $socialId,
            ]);
            return $user;
        });
    }

    public function attachSocialNetwork(string $socialNetwork, string $socialId): void
    {
        $exists = $this->socialNetworks()->where([
            'network' => $socialNetwork,
            'social_id' => $socialId,
        ])->exists();

        if ($exists) {
            throw new DomainException('Социальная сеть ' . $socialNetwork . ' уже привязана к пользователю.');
        }

        $this->socialNetworks()->create([
            'network' => $socialNetwork,
            'social_id' => $socialId,
        ]);
    }


    //------------------
    // Other
    //------------------
    public function findForPassport($email){
        return self::where('email', $email)->where('status', self::STATUS_ACTIVE)->first();
    }
}
