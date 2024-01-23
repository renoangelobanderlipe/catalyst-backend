<?php

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
