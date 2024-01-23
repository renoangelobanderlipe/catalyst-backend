<?php

namespace App\Traits;

trait HttpResponse
{
  public function buildMessage(array $data, $statusCode)
  {
    return response()->json($data, $statusCode);
  }

  public function withData(array $data, $statusCode = 200)
  {
    return $this->buildMessage([
      'data' => $data
    ], $statusCode);
  }

  public function error(string $message, $statusCode = 401)
  {
    return $this->buildMessage([
      'message' => $message
    ], $statusCode);
  }

  public function success($message = 'Success', $statusCode = 200)
  {
    return $this->buildMessage([
      'message' => $message
    ], $statusCode);
  }
}
