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
class ModerationalReminderIsBeingEvaluatedEvent extends Event {

  const EVENT_NAME = 'moderational_reminder_is_being_evaluated_event';

  /**
   * The node which is being scheduled and saved.
   *
   * @var \Drupal\node\NodeInterface
   */
  public $node;
  public $timestamp;
  public $date_string;
  public $modify_link;
  public $user;
  public $reminder_title;

  /**
   * Constructs the object.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node which is being scheduled and saved.
   */
  public function __construct(NodeInterface $node, $timestamp, $date_string, $modify_link, $user, $reminder_title) {
    $this->node = $node;
    $this->timestamp = $timestamp;
    $this->date_string = $date_string;
    $this->modify_link = $modify_link;
    $this->user = $user;
    $this->reminder_title = $reminder_title;
  }

}
