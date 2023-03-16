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
   * The OvhApi default sender to use
   * @var string|null
   */
  protected ?string $defaultSender;

  /**
   * The OvhApi run with sandbox mode (sms sending are disabled)
   * @var bool
   */
  protected bool $sandbox;

  /**
   * Create a new OvhSms channel instance.
   * @param OvhApi $client
   * @param string $account
   * @param string|null $defaultSender
   * @param bool $sandbox
   */
  public function __construct(OvhApi $client, string $account, ?string $defaultSender = null, bool $sandbox = false)
  {
    $this->client = $client;
    $this->account = $account;
    $this->defaultSender = $defaultSender;
    $this->sandbox = $sandbox;
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
    if (empty($receivers)) {
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
      // Load default sender if message is not using sendForResponse and defaultSender is set
      if (!$message->sendForResponse && null === $message->sender) {
        if (null === $this->defaultSender) {
          throw new InvalidArgumentException('You must specify a valid sender for this message');
        }
        $message->withSender($this->defaultSender);
      }
    }
    // If sandbox mode, sms sending are disabled
    if ($this->sandbox) {
      return null;
    }
    // Create OVH request payload
    $content = (object) [
      'charset' => 'UTF-8',
      'class' => 'phoneDisplay',
      'sender' => $message->sender,
      'coding' => $message->coding,
      'message' => $message->content,
      'noStopClause' => !$message->withStopClause,
      'priority' => $message->priority,
      'receivers' => $message->receivers,
      'senderForResponse' => $message->sendForResponse,
      'validityPeriod' => $message->validityPeriod,
    ];
    return $this->client->post("/sms/$this->account/jobs", $content);
  }
}
