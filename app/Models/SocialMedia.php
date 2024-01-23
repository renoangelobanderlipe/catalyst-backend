<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
  use HasFactory;

  protected $table = 'social_media';

  protected $fillable = [
    'user_id',
    'platform_name',
    'user_name',
    'url',
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
