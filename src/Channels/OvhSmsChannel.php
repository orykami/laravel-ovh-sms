<?php

namespace Illuminate\Notifications\Channels;

use Illuminate\Notifications\Messages\OvhSmsMessage;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;
use Ovh\Api as OvhApi;

/**
 * Class OvhSmsChannel
 * @package Illuminate\Notifications\Channels
 */
class OvhSmsChannel
{
  /**
   * The OvhApi client instance.
   * @var OvhApi
   */
  protected OvhApi $client;

  /**
   * The OvhApi SMS account to use (Ex: sms-xxxxxxx-x)
   * @var string
   */
  protected string $account;

  /**
   * The phone number notifications should be sent from.
   * @var string
   */
  protected string $from;

  /**
   * Create a new OvhSms channel instance.
   * @param OvhApi $client
   * @param string $account
   * @param string $from
   */
  public function __construct(OvhApi $client, string $account, string $from)
  {
    $this->from = $from;
    $this->account = $account;
    $this->client = $client;
  }

  /**
   * Send the given notification.
   * @param  mixed  $notifiable
   * @param Notification $notification
   * @return array|null
   */
  public function send($notifiable, Notification $notification): ?array
  {
    $receivers = $notifiable->routeNotificationFor('ovhSms', $notification);
    // If no receivers specified, we don't go further
    if (null === $receivers || empty($receivers)) {
      return null;
    }
    // If notification cannot be cast into OvhSms
    if (!method_exists($notification, 'toOvhSms')) {
      return null;
    }
    $message = $notification->toOvhSms($notifiable);
    if (is_string($message)) {
      $message = (new OvhSmsMessage($message))->withFrom($this->from)->withReceivers($receivers);
    }
    // Ensure message is an instance of OvhSmsMessage
    if (!$message instanceof OvhSmsMessage) {
      throw new InvalidArgumentException('Message must be an instanceof OvhSmsMessage, ' . gettype($message) . 'given');
    }
    $content = (object) [
      'charset' => 'UTF-8',
      'class' => 'phoneDisplay',
      'coding' => '7bit',
      'message' => $message->content,
      'noStopClause' => !$message->withStopClause,
      'priority' => 'medium',
      'receivers' => $message->receivers,
      'senderForResponse' => true,
      'validityPeriod' => 2880,
    ];

    return $this->client->post("/sms/{$this->account}/jobs", $content);
  }
}
