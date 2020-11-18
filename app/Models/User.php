<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    private static function getIsDeleted(): bool
    {
        $random = Arr::random(range(0,10));
        return $random > 7;
    }

    public static function getRandomUserForCSV(Faker\Generator $faker)
    {
        return array(
            $faker->name,
            $faker->safeEmail,
            $faker->randomNumber(5),
            $faker->phoneNumber,
            User::getIsDeleted()
        );
    }

    /**
     * @param $user
     */
    public function populateUserModel($userRow): void
    {
        $this->name = $userRow[0];
        $this->email = $userRow[1];
        $this->password = $userRow[2];
        $this->phone = $userRow[3];
        $this->deleted_at = $this->getDeletedAt($userRow[4]);
    }

    private function getDeletedAt($deleted)
    {
        return $deleted ? now() : null;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
