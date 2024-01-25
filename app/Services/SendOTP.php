<?php

namespace App\Services;

use App\Scopes\UserScopes;
use Exception;

class SendOTP
{
  public function handle(string $column, string $value)
  {
    $hasEmail =  UserScopes::exist($column, $value)->first();

    if (!$hasEmail) {
      throw new Exception('');
    }

    // send an email with otp
    $validToken = rand(10, 100. . '2024');

    dd($validToken);

    return $this;
  }

  public function execute()
  {
    return 'send';
  }
}
