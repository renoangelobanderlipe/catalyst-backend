<?php

namespace App\Mailer;

use App\Jobs\SendEmail;
use App\Traits\NotificationConfig;

class BaseMailable
{
  use NotificationConfig;

  public $to;
  public $address;
  public $cc;
  public $bcc;
  public $content;
  public $mailUrl;
  public $verifyOption;
  public $subject;
  public $mailData;
  public $replyTo = 'accounts.@noreply.com';

  /**
   * Queue the email for normal sending.
   *
   * @param mixed $data The data for constructing the email.
   *
   * @return $this
   */
  public function queueNormal($data)
  {
    dispatch((new SendEmail($data)));

    return $this;
  }

  /**
   * Queue the SendEmail job if a specified condition is met.
   *
   * @param bool $condition The condition to determine whether to dispatch the job.
   * @param mixed $data     The data to be passed to the SendEmail job.
   *
   * @return $this
   */
  public function queueDispatchIf(bool $condition, $data)
  {
    SendEmail::dispatchIf($condition, $data);

    return $this;
  }

  /**
   * Queue the SendEmail job unless a specified condition is met.
   *
   * @param bool $condition The condition to determine whether not to dispatch the job.
   * @param mixed $data     The data to be passed to the SendEmail job.
   *
   * @return $this
   */
  public function queueDispatchUnless(bool $condition, $data)
  {
    SendEmail::dispatchUnless($condition, $data);

    return $this;
  }

  /**
   * Queue the email for delayed sending.
   *
   * @param int   $delay The delay (in seconds) before sending the email.
   * @param mixed $data  The data for constructing the email.
   *
   * @return $this
   */
  public function queueLater($delay, $data)
  {
    dispatch((new SendEmail($data)))->delay($delay);

    return $this;
  }
}
