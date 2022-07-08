<?php

namespace Drupal\mesa_moderation\Event;

use Drupal\node\NodeInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * An existing node is scheduled for publishing.
 *
 * This event is fired when an existing node is updated/saved and it has a
 * scheduled publishing date.
 */
class NodeNotificationIsBeingEvaluated extends Event {

  const EVENT_NAME = 'node_notification_is_being_evaluated_event';

  /**
   * The node which is being scheduled and saved.
   *
   * @var \Drupal\node\NodeInterface
   */
  public $node;
  public $timestamp;
  public $days;
  public $days_word;

  /**
   * Constructs the object.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node which is being scheduled and saved.
   */
  public function __construct(NodeInterface $node, $timestamp, $days, $days_word) {
    $this->node = $node;
    $this->timestamp = $timestamp;
    $this->days = $days;
    $this->days_word = $days_word;
  }

}
