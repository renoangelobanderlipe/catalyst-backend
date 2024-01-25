<?php

namespace App\Http\Controllers\V1;

use App\Services\SendOTP;
use App\Scopes\UserScopes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerifyEmailController extends Controller
{
  /**
   * Handle the incoming request.
   */
  public function __invoke(Request $request)
  {
    $email = $request->email;
    // cache

    (new SendOTP)->handle('email', $email)->execute();

    return $this->error('Email Not Found!', 404);
  }
}
