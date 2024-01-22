<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Exception;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable;

  protected $table = 'users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'first_name',
    'middle_name',
    'last_name',
    'email',
    'password',
    'status',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
    'password' => 'hashed',
  ];

  protected static function boot()
  {
    parent::boot();
    static::creating(function ($userAccount) {
      do {
        $hash = str()->random(mt_rand(6, 20));
      } while (static::where('hash', $hash)->exists());
      $userAccount->hash = $hash;
    });
  }

  public function scopeExist($query, $column, $value)
  {
    $query->where($column, $value);
  }

  public function generateHashedPassword(string $password)
  {
    return Hash::make($password);
  }

  public function registerUser(array $payload)
  {
    $password = $this->generateHashedPassword($payload['password']);

    $user = User::create([
      'first_name' => $payload['first_name'],
      'middle_name' => $payload['middle_name'],
      'last_name' => $payload['last_name'],
      'email' => $payload['email'],
      'password' => $password,
      'status' => 'active',
    ]);

    $token = $user->createToken(env('SANTUM_TOKEN'))->plainTextToken;

    return [
      ...$user->getOriginal(),
      'token' => $token,
    ];
  }

  public function updateProfile(array $payload, string $id)
  {
    $user = User::find($id);

    $user->update([
      'email' => $payload['email'],
      'first_name' => $payload['first_name'],
      'middle_name' => $payload['middle_name'],
      'last_name' => $payload['last_name'],
    ]);

    return $user;
  }

  public function updateUserPassword(string $password, string $id)
  {
    $user = User::find($id);
    return $user->update(['password' => $this->generateHashedPassword($password)]);
  }
}
