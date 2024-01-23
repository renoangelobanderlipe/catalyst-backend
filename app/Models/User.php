<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Exception;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, HasRoles;

  protected $table = 'users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'email',
    'first_name',
    'middle_name',
    'last_name',
    'suffix',
    'password',
    'is_active',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = ['password'];

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

  public function userProfile(): HasOne
  {
    return $this->hasOne(UserProfile::class);
  }

  public function socialMedia(): HasMany
  {
    return $this->hasMany(SocialMedia::class);
  }

  public function projects(): HasMany
  {
    return $this->hasMany(Projects::class);
  }

  public function accessToken(): HasOne
  {
    return $this->hasOne(AccessToken::class);
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

    $token = $user->createToken(config('sanctum.token_name'))->plainTextToken;

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
