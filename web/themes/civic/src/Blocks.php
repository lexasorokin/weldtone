<?php

namespace Drupal\civic;

use \Drupal\block\Entity\Block;
use \Drupal\block_content\BlockContentInterface;
use \Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;

class Blocks {

  public static function loadBreadcrumbBlock(&$vars) {
    // Load breadcrumb block and set to a variable to be accessed in the Twig template
    $breadcrumb_block = Block::load('civic_breadcrumbs');
    $breadcrumb_block_content = \Drupal::entityTypeManager()
      ->getViewBuilder('block')
      ->view($breadcrumb_block);
    $vars['breadcrumbs'] = $breadcrumb_block_content;
  }

  public static function addBlockTypeSuggestion(&$suggestions, &$vars) {
    $content = $vars['elements']['content'];
    if (isset($content['#block_content']) && $content['#block_content'] instanceof BlockContentInterface) {
      $block_type_suggestion = 'block__' . $content['#block_content']->bundle();
      $suggestions_rev = array_reverse($suggestions); // Taking care of observed duplication of block__block_content
      $index = array_search('block__', $suggestions_rev);
      if (is_numeric($index)) {
        array_splice($suggestions_rev, $index, 0, $block_type_suggestion);
        $suggestions = array_reverse($suggestions_rev);
      }
      else {
        $suggestions[] = $block_type_suggestion;
      }
    }
  }
}