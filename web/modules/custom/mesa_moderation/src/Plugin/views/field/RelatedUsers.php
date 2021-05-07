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
 * @ViewsField("related_users")
 */
class RelatedUsers extends FieldPluginBase {

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
    if (!empty($this->view->users_loaded)) {
      $users_present = [];

      $node = $values->_entity;

      if ($node->hasField('field_user')) {
        $users_value = $node->get('field_user')->getValue();

        if ($users_value) {
          foreach ($users_value as $user_item) {
            $users_present[$user_item['target_id']] = $user_item['target_id'];
          }
        }
      }

      $user_links = [];

      foreach ($users_present as $uid) {

        if (!empty($this->view->users_loaded[$uid])) {

          $cur_user = $this->view->users_loaded[$uid];

          $cur_user_name = $cur_user->getUsername();

          $link = \Drupal\Core\Link::fromTextAndUrl($cur_user_name,
                  \Drupal\Core\Url::fromUserInput('/mw_login/' . $uid));

          if ($link) {

            $link = $link->toString()->__toString();
          }

          $user_links[] = $link;
        }
      }

      return [
        '#type'     => 'inline_template',
        '#template' => implode(', ', $user_links),
      ];
    }
    return '';
  }

}
