<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
  use HasFactory;

  protected $table = 'access_tokens';

  protected $casts = [
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
