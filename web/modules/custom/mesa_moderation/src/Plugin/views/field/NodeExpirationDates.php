<?php

/**
 * @file
 * Definition of Drupal\mesa_moderation\Plugin\views\field\RelatedUsers.
 */

namespace Drupal\mesa_moderation\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("node_expiration_dates")
 */
class NodeExpirationDates extends FieldPluginBase {

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * Define the available options
   * @return array
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    return $options;
  }

  /**
   * Provide the options form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {


    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {

    $dates = [];

    if (!empty($values->_entity)) {
      $node = $values->_entity;

      if ($node->hasField('field_moderational_reminders')) {

        $reminder_ids = [];

        $reminders_v = $node->get('field_moderational_reminders')->getValue();

        if ($reminders_v) {
          foreach ($reminders_v as $r_item) {
            $reminder_ids[] = $r_item['target_id'];
          }
        }

        if ($reminder_ids) {
          $reminders = \Drupal::entityTypeManager()->getStorage('moderational_reminder')->loadMultiple($reminder_ids);

          $dates = [];

          if ($reminders) {
            foreach ($reminders as $reminder) {
              $date_v = $reminder->get('field_mr_expiration_date')->getValue();

              if ($date_v) {
                $dates[] = $date_v[0]['value'];
              }
            }
          }
        }
      }


      return [
        '#type'     => 'inline_template',
        '#template' => implode(', ', $dates),
      ];
    }
    return '';
  }

}
