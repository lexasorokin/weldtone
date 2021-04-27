<?php

namespace Drupal\text_alert\Controller;

use \Drupal\text_alert\Imap;
use \Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;

class TextAlertController extends ControllerBase {
  protected static $shouldUnpublishNodes = FALSE;
  protected static $clearTextIndex = NULL;

  /**
   * Reads the email acount defined in Imap.
   * Then loops through all the emails, sees if they are from
   * allowed numbers, and then creates a new 'Emergency Alert'
   * from that email if a node does not already exist for it.
   */
  public static function readEmails() {
    $imap = new Imap;
    $message = '';

    $allowed_numbers = self::getAllowedNumbers();

    for ($i = 0; $i <= count($imap->get_inbox()); $i++) {
      $email = $imap->get($i);
      $from_number = preg_replace('/[^0-9]/', '', $email['header']->fromaddress);

      if (in_array($from_number, $allowed_numbers)) {
        $received_date = $email['header']->date;

        $duplicate_nodes = self::getDuplicateNodes($received_date);

        \Drupal::logger('text_alert')->notice($email['body']);
        \Drupal::logger('text_alert')->notice(print_r($duplicate_nodes));

        $isClearMessage = self::checkForClearMessage($email['body'], $received_date);

        if (count($duplicate_nodes) === 0 && !$isClearMessage) {
          self::parseEmailBody($email['body'], $received_date);
        }
      }
    }

    if (self::$shouldUnpublishNodes) {
      self::unpublishNodes();
    }

    $imap->close();
  }

  /**
   * Check the settings form to see which phone numbers are allowed
   * The numbers are sanatized to remove dashes and other characters.
   */
  protected static function getAllowedNumbers() {
    $config = \Drupal::config('text_alert.settings');
    $allowed_numbers = $config->get('phone_numbers');
    $allowed_numbers = preg_split('/\n|\R/', $allowed_numbers);
    $allowed_numbers = array_map(function($n) { return preg_replace('/[^0-9]/', '', $n); }, $allowed_numbers);
    $allowed_numbers = preg_replace('/[^0-9]/', '', $allowed_numbers);

    return $allowed_numbers;
  }

  /**
   * Check if this notification is already created in Drupal
   * by comparing the saved field of when the email was received.
   */
  protected static function getDuplicateNodes($received_date) {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'emergency_alert')
      ->condition('field_email_received_date', $received_date)
      ->execute();

    return $nids;
  }

  /**
   * Check if the message has the text 'CLEARALERT'.
   * If so, check if that clear has already been executed by looking
   * if the custom database table has an entry with a matching date.
   * If so, ignore it. If not, create an entry in the database and
   * set the flag to unpublish all "Emergency Alert" nodes.
   */
  protected static function checkForClearMessage($email_body, $received_date) {
    if (strpos(strtoupper($email_body), 'CLEARALERT') !== FALSE) {
      $database = \Drupal::database();

      $query = $database->select('text_alert_clear_messages', 'tacm');
      $query->fields('tacm', ['email_received_date']);
      $query->condition('email_received_date', $received_date);
      $duplicate_clear_emails = $query->execute()->fetchAll();

      if (count($duplicate_clear_emails) === 0) {
        $database->insert('text_alert_clear_messages')
          ->fields(array('email_received_date' => $received_date))
          ->execute();

        self::$shouldUnpublishNodes = TRUE;
      }

      return TRUE;
    }

    return FALSE;
  }

  /**
   * The message should be formatted '<Alert Type>: <Message>'
   * Example: 'Emergency: Coronavirus cases spread'
   */
  protected static function parseEmailBody($email_body, $received_date) {
    $alert_type = NULL;
    $split_message = explode(':', $email_body);

    if (count($split_message) === 2) {
      $alert_type = $split_message[0];
      $message = $split_message[1];

      self::createNewNode($alert_type, $message, $received_date);
    }
  }

  /**
   * Create a new 'Emergency Alert' node
   */
  protected static function createNewNode($alert_type, $message, $date) {
    $tid = NULL;
        
    if ($alert_type) {
      $tids = \Drupal::entityQuery('taxonomy_term')
        ->condition('vid', 'alert_types')
        ->condition('name', $alert_type)
        ->execute();
      $tid = reset($tids);
    }

    $node = Node::create(array(
        'type' => 'emergency_alert',
        'title' => $message,
        'langcode' => 'en',
        'status' => 1,
        'field_alert_type' => $tid ? $tid : 1,
        'field_email_received_date' => $date
    ));

    $node->save();
  }

  /**
   * Finds all published 'Emergency Alert' nodes
   * and unpublishes them.
   */
  protected static function unpublishNodes() {
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'emergency_alert')
      ->condition('status', 1)
      ->execute();

    $nodes = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadMultiple($nids);

    foreach ($nodes as $node) {
      $node->setUnpublished();
      $node->save();
    }
  }
}