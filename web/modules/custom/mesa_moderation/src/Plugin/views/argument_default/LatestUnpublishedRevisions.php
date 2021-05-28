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

    $current_uid = \Drupal::currentUser()->id();

    $result = \Drupal::database()->query("SELECT vid FROM node_field_revision WHERE vid IN (SELECT MAX(revision_id) as maxrev FROM node_revision__field_user WHERE field_user_target_id=$current_uid GROUP BY entity_id) AND status=0");

    $result = $result->fetchAll();

    $vids = [];

    foreach ($result as $item) {
      $vids[] = $item->vid;
    }
    if (!$vids) {
      return 0;
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
