<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class HttpHelper
{
  /**
   * Perform an HTTP POST request with options.
   *
   * This static method sends an HTTP POST request to the specified URL with the given payload and options.
   * It allows customization of the SSL verification setting through the 'verify' option.
   *
   * @param string $url     The URL to which the POST request will be sent.
   * @param array  $payload The data to be included in the POST request.
   * @param bool   $verify  Optional. Whether to verify SSL certificates (default is false).
   *
   * @return mixed The response from the HTTP POST request.
   */
  public static function postWithOptions(string $url, array $payload, $verify = false)
  {
    return Http::withOptions(['verify' => $verify])->post($url, $payload);
  }

  public static function getWithOptions(string $url, $verify = false)
  {
    return Http::withOptions(['verify' => $verify])->get($url);
  }
}
