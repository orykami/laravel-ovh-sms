<?php

namespace Illuminate\Notifications\Messages;

use InvalidArgumentException;

/**
 * Class OvhSmsMessage
 * @package Illuminate\Notifications\Messages
 */
class OvhSmsMessage
{
  /**
   * High priority
   */
  const PRIORITY_HIGH = 'high';

  /**
   * Medium priority
   */
  const PRIORITY_MEDIUM = 'medium';

  /**
   * Low priority
   */
  const PRIORITY_LOW = 'low';

  /**
   * Very low priority
   */
  const PRIORITY_VERY_LOW = 'veryLow';

  /**
   * Coding 7 bit
   */
  const CODING_7BIT = '7bit';

  /**
   * Coding 8 bit
   */
  const CODING_8BIT = '8bit';

  /**
   * The message content.
   * @var string
   */
  public string $content;

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
   * @param  string $content
   * @return $this
   */
  public function withContent(string $content): OvhSmsMessage
  {
    $this->content = $content;
    return $this;
  }

  /**
   * Set the message priority.
   * @param string $priority
   * @return $this
   */
  public function withPriority(string $priority): OvhSmsMessage
  {
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
