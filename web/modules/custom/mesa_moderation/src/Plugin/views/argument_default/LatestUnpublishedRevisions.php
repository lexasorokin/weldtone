<?php

namespace Drupal\mesa_moderation\Plugin\views\argument_default;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\views\Plugin\views\argument_default\ArgumentDefaultPluginBase;

/**
 * Default argument plugin to extract the current user
 *
 * This plugin actually has no options so it does not need to do a great deal.
 *
 * @ViewsArgumentDefault(
 *   id = "latest_unpublished_revisions",
 *   title = @Translation("Latest unpublished revisions")
 * )
 */
class LatestUnpublishedRevisions extends ArgumentDefaultPluginBase
    implements CacheableDependencyInterface {

  /**
   * {@inheritdoc}
   */
  public function getArgument() {
    //return \Drupal::currentUser()->id();
    /*
      $query = \Drupal::database()->select('commerce_order__order_items', 'c');
      $query->fields('c',
      ['order_items_target_id', 'entity_id', 'revision_id', 'delta']);
      $query->condition('entity_id', $order_ids, 'IN');
      $result = $query->execute();
      $result = $result->fetchAll();
     */

    $current_uid = \Drupal::currentUser()->id();

    $current_uid = 59;

    $result = \Drupal::database()->query("SELECT vid FROM node_field_revision WHERE vid IN (SELECT MAX(revision_id) as maxrev FROM node_revision__field_user WHERE field_user_target_id=$current_uid GROUP BY entity_id) AND status=0");

    $result = $result->fetchAll();

    $vids = [];

    foreach ($result as $item) {
      $vids[] = $item->vid;
    }

    return implode('+', $vids);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return Cache::PERMANENT;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return ['user'];
  }

}
