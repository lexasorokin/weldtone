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
 *   id = "current_year_date",
 *   title = @Translation("Current year")
 * )
 */
class CurrentYearDate extends ArgumentDefaultPluginBase
    implements CacheableDependencyInterface {

  /**
   * {@inheritdoc}
   */
  public function getArgument() {

    return '2021';
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
