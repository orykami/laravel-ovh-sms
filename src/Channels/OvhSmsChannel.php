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
   * Create a new OvhSms channel instance.
   * @param OvhApi $client
   * @param string $account
   */
  public function __construct(OvhApi $client, string $account)
  {
    $this->account = $account;
    $this->client = $client;
  }

  /**
   * Send the given notification.
   * @param  mixed  $notifiable
   * @param Notification $notification
   * @return array|null
   * @note This method return OVH API payload as response
   * Exemple :
   * [
   *   'ids' => [10001,11001],
   *   'invalidReceivers' => [+33600000000],
   *   'totalCreditsRemoved' => 1,
   *   'validReceivers' => [+33615000090]
   * ]
   * @see https://eu.api.ovh.com/console/#/sms/%7BserviceName%7D/jobs#POST
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
    /** @var OvhSmsMessage|string $message */
    $message = $notification->toOvhSms($notifiable);
    if (is_string($message)) {
      $message = new OvhSmsMessage($message);
    }
    // Ensure message is an instance of OvhSmsMessage
    if (!$message instanceof OvhSmsMessage) {
      throw new InvalidArgumentException(
        'message must be an instanceof OvhSmsMessage|string, ' . gettype($message) . 'given',
      );
    } else {
      $message->withReceivers($receivers);
    }
    // Create OVH request payload
    $content = (object) [
      'charset' => 'UTF-8',
      'class' => 'phoneDisplay',
      'coding' => $message->coding,
      'message' => $message->content,
      'noStopClause' => !$message->withStopClause,
      'priority' => $message->priority,
      'receivers' => $message->receivers,
      'senderForResponse' => $message->sendForResponse,
      'validityPeriod' => $message->validityPeriod,
    ];
    return $this->client->post("/sms/{$this->account}/jobs", $content);
  }
}
