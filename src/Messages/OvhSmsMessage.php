<?php /** @noinspection PhpUnused */

namespace Illuminate\Notifications\Messages;

use InvalidArgumentException;

/**
 * Class OvhSmsMessage
 * @package Illuminate\Notifications\Messages
 */
class OvhSmsMessage
{
  /**
   * Very low priority
   */
  const PRIORITY_VERY_LOW = 'veryLow';

  /**
   * Low priority
   */
  const PRIORITY_LOW = 'low';

  /**
   * Medium priority
   */
  const PRIORITY_MEDIUM = 'medium';

  /**
   * High priority
   */
  const PRIORITY_HIGH = 'high';

  /**
   * Coding 7 bit
   */
  const CODING_7BIT = '7bit';

  /**
   * Coding 8 bit
   */
  const CODING_8BIT = '8bit';

  /**
   * Priority list
   * @var array|string[]
   */
  public static array $priorityList = [
    self::PRIORITY_VERY_LOW,
    self::PRIORITY_LOW,
    self::PRIORITY_MEDIUM,
    self::PRIORITY_HIGH,
  ];

  /**
   * Coding list
   * @var array|string[]
   */
  public static array $codingList = [
    self::CODING_7BIT,
    self::CODING_8BIT,
  ];

  /**
   * The message content.
   * @var string
   */
  public string $content;

  /**
   * The message sender.
   * @var string|null
   */
  public ?string $sender = null;

  /**
   * The message priority
   * @var string
   */
  public string $priority = self::PRIORITY_MEDIUM;

  /**
   * The message coding
   * @var string
   */
  public string $coding = self::CODING_7BIT;

  /**
   * The message validity period
   * @var int
   */
  public int $validityPeriod = 2880;

  /**
   * The message receiver
   * @var array<string>
   */
  public array $receivers = [];

  /**
   * The message must include a stop clause ?
   * @var bool
   */
  public bool $withStopClause = false;

  /**
   * The message must use short number to allow response ?
   * @var bool
   */
  public bool $sendForResponse = false;

  /**
   * Create a new message instance.
   * @param string $content
   * @returnvoid
   */
  public function __construct(string $content)
  {
    $this->content = $content;
  }

  /**
   * Set the message content.
   * @param string $content
   * @return $this
   */
  public function withContent(string $content): OvhSmsMessage
  {
    $this->content = $content;
    return $this;
  }

  /**
   * Set the message sender.
   * @param string $sender
   * @return $this
   */
  public function withSender(string $sender): OvhSmsMessage
  {
    $this->sender = $sender;
    return $this;
  }

  /**
   * Set the message priority.
   * @param string $priority
   * @return $this
   */
  public function withPriority(string $priority): OvhSmsMessage
  {
    if (!in_array($priority, self::$priorityList)) {
      throw new InvalidArgumentException("Unknown priority value '$priority'");
    }
    $this->priority = $priority;
    return $this;
  }

  /**
   * Set the message coding.
   * @param string $coding
   * @return $this
   */
  public function withCoding(string $coding): OvhSmsMessage
  {
    if (!in_array($coding, self::$codingList)) {
      throw new InvalidArgumentException("Unknown coding value '$coding'");
    }
    $this->coding = $coding;
    return $this;
  }

  /**
   * Set the message validity period.
   * @param int $validityPeriod
   * @return $this
   */
  public function withValidityPeriod(int $validityPeriod): OvhSmsMessage
  {
    $this->validityPeriod = $validityPeriod;
    return $this;
  }

  /**
   * Set the message stop clause.
   * @param bool $stopClause
   * @return $this
   */
  public function withStopClause(bool $stopClause): OvhSmsMessage
  {
    $this->withStopClause = $stopClause;
    return $this;
  }

  /**
   * Set the message send for response.
   * @param bool $sendForResponse
   * @return $this
   */
  public function withSendForResponse(bool $sendForResponse): OvhSmsMessage
  {
    $this->sendForResponse = $sendForResponse;
    return $this;
  }

  /**
   * Set the message receivers.
   * @param string|array $receivers
   * @return OvhSmsMessage
   */
  public function withReceivers($receivers): OvhSmsMessage
  {
    if (is_string($receivers)) {
      $receivers = [$receivers];
    }
    if (!is_array($receivers)) {
      throw new InvalidArgumentException('receivers must be string|array|min:1');
    }
    $this->receivers = $receivers;
    return $this;
  }
}
