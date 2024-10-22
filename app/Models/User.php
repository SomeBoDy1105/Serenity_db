<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\MessageSent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';
    protected $guarded = ['id'];


    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    const USER_TOKEN = "userToken";

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'created_by');
    }

    public function routeNotificationForOneSignal(): array
    {
        return ['tags' => ['key' => 'userId', 'relation' => '=', 'value' => (string)($this->id)]];
    }

    public function sendNewMessageNotification(array $data): void
    {
        $this->notify(new MessageSent($data));
    }

    public function Admin()
    {
        return $this->hasOne(Admin::class, 'id_user');
    }

    public function Client()
    {
        return $this->hasOne(Client::class, 'id_user');
    }

    public function Reviewer()
    {
        return $this->hasOne(Reviewer::class, 'id_user');
    }

    public function Adviser()
    {
        return $this->hasOne(Adviser::class, 'id_user');
    }
}
