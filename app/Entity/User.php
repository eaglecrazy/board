<?php

namespace App\Entity;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;


/**
 * App\Entity\User
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
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereVerifyToken($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereRole($value)
 * @property string|null $phone
 * @property bool $phone_verified
 * @property string|null $phone_verify_token
 * @property \Illuminate\Support\Carbon|null $phone_verify_token_expire
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User wherePhoneVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User wherePhoneVerifyToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Entity\User wherePhoneVerifyTokenExpire($value)
 */
class User extends Authenticatable
{
    use Notifiable;

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
            self::STATUS_WAIT => 'Wait',
            self::STATUS_ACTIVE => 'Active',
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
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified' => 'boolean',
        'phone_auth' => 'boolean',
        'phone_verify_token_expire' => 'datetime',
    ];

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
            throw new \DomainException('User is already verified.');
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
            throw new \DomainException('Role is already assigned');
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
            throw new \DomainException('Phone number is empty.');
        }
        //если уже есть токен, и есть время когда он закончится и оно больше чем сейчас
        //то есть токен отправили и получить другой ещё нельзя
        if (!empty($this->phone_verify_token) && $this->phone_verify_token_expire && $this->phone_verify_token_expire->gt($now)) {
            throw new \DomainException('Token is already requested. The new token will be available in ' . Carbon::now()->diffInSeconds($this->phone_verify_token_expire) . ' seconds.');
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
            throw new \DomainException('Incorrect verify token.');
        }
        if ($this->phone_verify_token_expire->lt($now)) {
            throw new \DomainException('Token is expired.');
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

    public function hasFilledProfile(): bool
    {
        return !empty($this->name) && !empty($this->last_name) && $this->isPhoneVerified();
    }

}
