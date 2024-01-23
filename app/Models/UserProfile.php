<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model

{
  use HasFactory;

  protected $table = 'user_profiles';

  protected $fillable = [
    'user_id',
    'birthdate',
    'gender',
    'contact_number',
    'street_address',
    'city',
    'state',
    'postal_code',
    'profile_image',
    'bio',
    'website',
    'additional_info',
  ];

  protected $casts = [
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
