<?php


namespace App\Entity\Adverts\Advert\Dialog;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $advert_id
 * @property int $user_id
 * @property int $client_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $user_new_messages
 * @property int $client_new_messages
 */
class Dialog extends Model
{
    protected $table = 'advert_dialogs';

    protected $guarded = ['id'];

    public function writeMessageByOwner(int $ownerId, string $message): void
    {
        $this->messages()->create([
            'user_id' => $ownerId,
            'message' => $message,
        ]);
        $this->client_new_messages++;
        $this->save();
    }

    public function writeMessageByClient(int $clientId, string $message): void
    {
        $this->messages()->create([
            'user_id' => $clientId,
            'message' => $message,
        ]);
        $this->user_new_messages++;
        $this->save();
    }

    public function readByOwner(): void
    {
        $this->update(['user_new_messages' => 0]);
    }

    public function readByClient(): void
    {
        $this->update(['client_new_messages' => 0]);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'dialog_id', 'id');
    }

    public function getAdvert(): Advert
    {
        return Advert::where('id', $this->advert_id)->first();
    }

    public function getLastMessageShort(): string
    {
        $str = $this->messages()->orderByDesc('created_at')->pluck('message')->first();
        return Str::limit($str, 50);
    }
}
