<?php

namespace Drupal\board_meetings\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for dashboard output.
 */
class ListUnsubscribe extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function output() {

    if (!empty($_GET['token']) && !empty($_GET['email'])) {
      $token = $_GET['token'];
      $email = $_GET['email'];

      $valid_tokens = [];

      $list_ids = \Drupal::entityQuery('mails_list')->execute();

      $valid_list_id = NULL;

      if ($list_ids) {
        foreach ($list_ids as $list_id) {
          $gen_token = hash('sha256', $list_id . $email);

          if ($gen_token == $token) {
            $valid_list_id = $list_id;
          }
        }

        if ($valid_list_id) {

          board_meetings_list_unsubscribe($valid_list_id, $email);
          return [
            '#markup' => t("You have been successfully unsubscribed"),
          ];
        }
       
      }
    }

    return [
      '#markup' => t("The unsubscribe link is not valid"),
    ];
  }

}
