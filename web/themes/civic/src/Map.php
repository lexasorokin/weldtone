<?php

namespace Drupal\civic;

use Drupal\civic\Images;
use Drupal\Core\Form\FormStateInterface;

class Map {
  
  /**
   * Called from THEME_preprocess_html()
   */
  public static function initalizeMap(&$vars) {
    if ($map = self::getMap()) {
      $vars['map'] = array(
        'start_lat' => $map->get('field_latitude')->getValue()[0]['value'],
        'start_lng' => $map->get('field_longitude')->getValue()[0]['value'],
        'start_zoom' => $map->get('field_zoom')->getValue()[0]['value'],
      );
    }
  }

  /**
   * Called from THEME_preprocess_paragraph()
   */
  public static function getFilterOptions(&$vars) {
    if ($vars['paragraph']->bundle() === 'map') {
      $filter1Vocab = $vars['paragraph']->get('field_filter_1')->getValue()[0]['target_id'];
      $vars['filter_1_options'] = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($filter1Vocab);
    }
  }

  /**
   * Called from THEME_page_attachments_alter()
   */
  public static function getMapLocations(array &$page) {
    $taxonomy_storage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');

    if ($map = self::getMap()) {
      $paragraph_storage = \Drupal::entityTypeManager()->getStorage('paragraph');
      $map_locations = $map->get('field_map_locations');

      $locations_arr = [];

      for ($i = 0; $i < $map_locations->count(); $i++) {
        $pid = $map_locations->get($i)->getValue()['target_id'];
        $paragraph = $paragraph_storage->load($pid);

        $location = array(
          'name' => $paragraph->get('field_location_name')->getValue()[0]['value'],
          'addr' => $paragraph->get('field_location_address')->getValue()[0]['value'],
          'lat' => $paragraph->get('field_latitude')->getValue()[0]['value'],
          'lng' => $paragraph->get('field_longitude')->getValue()[0]['value'],
          'city' => $paragraph->get('field_location_city')->getValue()[0]['value'],
          'state' => $paragraph->get('field_location_state')->getValue()[0]['value'],
          'zip' => $paragraph->get('field_location_zip')->getValue()[0]['value'],
          'tags' => [],
        );
        
        foreach ($paragraph->get('field_tags')->getValue() as $tag) {
          $tax_term = $taxonomy_storage->load($tag['target_id']);
          
          if ($tax_term->hasField('field_location_type_icon') && $icon = $tax_term->get('field_location_type_icon')->getValue()) {
            $location['tags'][] = array(
              'iconType' => 'icon',
              'iconValue' => $icon[0]['value'],
              'tid' => $tag['target_id'],
            );
          }
          else if ($tax_term->hasField('field_location_type_image') && $tax_term->get('field_location_type_image')->getValue()) {
            $image_field = $tax_term->get('field_location_type_image');
            $field_img_url = Images::getImageUrl($image_field->getEntity(), 'field_location_type_image');

            $location['tags'][] = array(
              'iconType' => 'image',
              'iconValue' => $field_img_url,
              'iconAlt' => $image_field->getValue()[0]['alt'],
              'tid' => $tag['target_id'],
            );
          }
          else {
            $location['tags'][] = array(
              'iconType' => NULL,
              'iconValue' => NULL,
              'tid' => $tag['target_id'],
            );
          }
        }

        $locations_arr[] = $location;
      }

      $page['#attached']['drupalSettings']['mapLocations'] = $locations_arr;
    }
  }

  /**
   * Called from other methods in this Class
   */
  public static function getMap() {
    $node = \Drupal::routeMatch()->getParameter('node');

    if (empty($node) || $node->bundle() != 'modular_page') {
      return NULL;
    }

    $sections = $node->get('field_sections');
    $paragraph_storage = \Drupal::entityTypeManager()->getStorage('paragraph');

    for ($i = 0; $i < $sections->count(); $i++) {
      $pid = $sections->get($i)->getValue()['target_id'];

      $paragraph = $paragraph_storage->load($pid);

      if ($paragraph->bundle() === 'map') {
        return $paragraph;
      }
    }

    return NULL;
  }
}