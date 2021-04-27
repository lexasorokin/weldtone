<?php

namespace Drupal\civic;

class Views {
  /**
   * Called from THEME_theme_suggestions_views_view_alter()
   */
  public static function addViewsViewTemplateSuggestions(&$suggestions, &$vars) {
    $suggestions[] = 'views_view__' . $vars['view']->getDisplay()->getPluginId();
    $suggestions[] = 'views_view__' . $vars['view']->storage->id();
    $suggestions[] = 'views_view__' . $vars['view']->storage->id() . '__' . $vars['view']->current_display;
  }
}
