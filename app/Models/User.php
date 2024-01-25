<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Exception;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

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

  public function registerUser(array $payload): array
  {
    $password = $this->generateHashedPassword($payload['password']);

    \DB::beginTransaction();

    $user = User::create([
      'first_name' => $payload['first_name'],
      'last_name' => $payload['last_name'],
      'email' => $payload['email'],
      'password' => $password,
    ]);
    $token = $user->createToken(config('sanctum.token_name'))->plainTextToken;
    \DB::commit();

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
