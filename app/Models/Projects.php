<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
  use HasFactory;

  protected $table = 'projects';

  protected $fillable = [
    'user_id',
    'name',
    'project_tags',
    'project_type',
    'url',
    'image',
    'description',
    'status',
    'is_archived',
    'is_deactivated',
    'start_date',
    'end_date',
  ];

  protected $casts = [
    'updated_at' => 'datetime',
    'created_at' => 'datetime',
  ];
}
