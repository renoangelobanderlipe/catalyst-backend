<?php

namespace App\Http\Controllers\V1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{

  public function login(Request $request)
  {
    try {
      $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
      ]);

      \DB::beginTransaction();

      $user = User::where('email', $request->email)->first();

      if (!$user || !Hash::check($request->password, $user->password)) {
        \DB::rollback();

        return $this->error('Invalid email or password', 401);
      }

      $token = $user->createToken(config('sanctum.token_name'))->plainTextToken;

      $userData = $user->only(['id', 'email', 'first_name', 'middle_name', 'last_name', 'status']);
      $data = array_merge($userData, ['token' => $token]);

      \DB::commit();

      return $this->withData($data);
    } catch (\Throwable $throwable) {
      \DB::rollback();

      return $this->error($throwable->getMessage());
    }
  }

  public function signup(Request $request, User $user)
  {
    $filteredRequest = $request->all();

    try {
      \DB::beginTransaction();
      $user = $user->registerUser($filteredRequest);
      \DB::commit();
      return $this->withData($user);
    } catch (\Throwable $throwable) {
      \DB::rollback();
      $this->error($throwable->getMessage());
    }
  }

  public function logout(Request $request)
  {
    $request->user()->currentAccessToken()->delete();

    return $this->success("Successfully Deleted Token");
  }

  // public function logout(Request $request)
  // {
  //   try {
  //     $user = $request->user();

  //     if (!$user) {
  //       return $this->error('User not authenticated', \Response::HTTP_UNAUTHORIZED);
  //     }

  //     $currentAccessToken = $user->currentAccessToken();

  //     if (!$currentAccessToken) {
  //       return $this->error('No valid access token found for the user', \Response::HTTP_BAD_REQUEST);
  //     }

  //     $currentAccessToken->delete();

  //     return $this->success('Successfully logged out');
  //   } catch (\Throwable $throwable) {
  //     // Log the exception for further investigation
  //     \Log::error('Error during logout:', ['exception' => $throwable]);

  //     return $this->error('An unexpected error occurred during logout', \Response::HTTP_INTERNAL_SERVER_ERROR);
  //   }
  // }
}
