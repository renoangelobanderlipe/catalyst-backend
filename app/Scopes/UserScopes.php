<?php

namespace App\Scopes;

use App\Models\User;

class UserScopes extends User
{
  public function scopeExist($query, $column, $value)
  {
    $query->where($column, $value);
  }
}
