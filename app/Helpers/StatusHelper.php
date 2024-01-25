<?php

if (!function_exists('getStatusWord')) {
  /**
   * Convert numeric status to corresponding word.
   *
   * @param int $status
   * @return string
   */
  function getStatusWord($status)
  {
    return $status == 1 ? 'Active' : 'Inactive';
  }

  if (!function_exists('getStatusNumber')) {
    /**
     * Convert status word to corresponding numeric value.
     *
     * @param string $statusWord
     * @return int
     */
    function getStatusNumber($statusWord)
    {
      return strtolower($statusWord) === 'active' ? 1 : 0;
    }
  }


  if (!function_exists('getStatusNumber')) {
    /**
     * Undocumented function
     *
     * @param [type] $status
     * @return void
     */
    function convertStatusToBoolean($status)
    {
      $keywords = config('app.status_keywords');

      if (is_bool($status)) {
        return $status;
      }

      if (!in_array($status, $keywords)) {
        throw new \InvalidArgumentException("Invalid status keyword $status");
      }

      return in_array(strtolower($status), $keywords['inactive']) ? false : true;
    }
  }
}
