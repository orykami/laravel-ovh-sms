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
   * The message content.
   * @var string
   */
  public string $content;

  /**
   * The message sender
   * @var string
   */
  public string $from;

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
   * Set the message sender.
   * @param  string $from
   * @return $this
   */
  public function withFrom(string $from): OvhSmsMessage
  {
    $this->from = $from;
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
