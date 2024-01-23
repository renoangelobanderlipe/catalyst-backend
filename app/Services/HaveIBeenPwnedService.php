<?php

namespace App\Services;

use App\Helpers\HttpHelper;
use Illuminate\Support\Facades\Http;

/**
 * For Reference of this service you may check the API of https://haveibeenpwned.com
 * In order to protect the value of the source password being searched for, Pwned Passwords also implements a k-Anonymity model that allows a password to be searched for by partial hash. This allows the first 5 characters of either a SHA-1 or an NTLM hash (not case-sensitive) to be passed to the API: 
 * GET https://api.pwnedpasswords.com/range/{first 5 hash chars}
 * 
 * When a password hash with the same first 5 characters is found in the Pwned Passwords repository, the API will respond with an HTTP 200 and include the suffix of every hash beginning with the specified prefix, followed by a count of how many times it appears in the data set. The API consumer can then search the results of the response for the presence of their source hash and if not found, the password does not exist in the data set. A sample SHA-1 response for the hash prefix "21BD1" would be as follows: 
 * 
 * 0018A45C4D1DEF81644B54AB7F969B88D65:1
 * 00D4F6E8FA6EECAD2A3AA415EEC418D38EC:2
 * 011053FD0102E94D6AE2F8B83D76FAF94F6:1
 * 012A7CA357541F0AC487871FEEC1891C49C:2
 * 0136E006E24E7D152139815FB0FC6A50B15:2
 *
 */

/** NOTE : BEFORE MODIFYING THE CODE MAKE SURE TO REFER TO THE OFFICIAL DOCUMENTATION OF HAVEIBEENPWNED! */
class HaveIBeenPwnedService
{
  /**
   * Check the exposure count of a password in known data breaches using Have I Been Pwned API.
   *
   * This method calculates the SHA-1 hash of the provided password and queries the Have I Been Pwned API
   * to determine if the password has been exposed in data breaches. It checks the count of occurrences
   * of the hashed password suffix in the API response. The method returns the exposure count, indicating
   * how many times the password has been found in known breaches. If the password has not been exposed,
   * it returns 0.
   *
   * @param string $password The password to check for exposure.
   *
   * @return int The exposure count of the password in known data breaches.
   */
  public function exposureCount($password)
  {
    // Generate the SHA-1 hash of the password and extract prefix and suffix.
    $hashedPassword = strtoupper(sha1($password));
    $prefix = substr($hashedPassword, 0, 5);
    $suffix = substr($hashedPassword, 5);

    // Build the API endpoint URL based on the configuration.
    $url = config('services.haveibeenpwned.url') . '' . config('services.haveibeenpwned.range_endpoint');

    // Make a GET request to the Have I Been Pwned API.
    $response = HttpHelper::getWithOptions("$url/{$prefix}");

    // Extract the response body into an array of hashes.
    $hashes = explode("\r\n", $response->body());

    // Iterate through the hashes to find a match for the password suffix.
    foreach ($hashes as $hash) {
      list($hashSuffix, $count) = explode(':', $hash);
      if ($hashSuffix === $suffix) {
        // Return the exposure count if a match is found.
        return (int)$count;
      }
    }

    // Return 0 if the password has not been exposed.
    return 0;
  }

  /**
   * Check if a password has been exposed in known data breaches using Have I Been Pwned API.
   *
   * This method determines if the provided password has been exposed in known data breaches
   * by calling the 'exposureCount' method. If the exposure count is greater than 0, indicating
   * that the password has been found in breaches, the method returns true. Otherwise, it returns false.
   *
   * @param string $password The password to check for exposure.
   *
   * @return bool True if the password has been exposed; false otherwise.
   */
  public function haveIBeenPwned($password, $hasExposure = false)
  {
    $exposureCount = $this->exposureCount($password);

    if ($hasExposure) {
      return [
        'exposure_count' => $exposureCount,
        'is_exposed' => $exposureCount > 0 ? true : false,
      ];
    }

    // Check if the exposure count of the password is greater than 0.
    return $exposureCount > 0 ? true : false;
  }
}
