<?php

namespace App\Http\Controllers\V1\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
  public function updateProfile(Request $request, string $id)
  {
    try {
      $updatedData = (new User)->updateProfile($request->all(), $id);

      return $this->withData($updatedData->toArray());
    } catch (\Throwable $throwable) {
      return $this->error($throwable->getMessage());
    }
  }

  public function updatePassword(Request $request, string $id)
  {
    $user = User::find($id);
    try {
      if (!Hash::check($request->current_password, $user->password)) {
        return $this->error('Invalid Password');
      }
      (new User)->updateUserPassword($request->new_password, $id);
      $request->user()->currentAccessToken()->delete();
      return $this->success('Updated Password');
    } catch (\Throwable $throwable) {
      return $this->error($throwable->getMessage());
    }
  }

  public function forgetPassword(Request $request)
  {
    // 
  }
}
