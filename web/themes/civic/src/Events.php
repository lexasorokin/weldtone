<?php

namespace Drupal\civic;

use \Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Entity\Paragraph;
use DateTimeZone;

class Events {

  /**
   * Called from THEME_preprocess_node()
   */
  public static function parseDateQueryToVars(&$vars) {
    if (!empty($vars['node']) && $vars['node']->bundle() == 'event') {
      $vars['#cache']['max-age'] = 0;
      $date_field = $vars['node']->get('field_event_date');
  
      if (!$date_field->isEmpty()) {
        $vars['date_query'] = array();
        $first_occurence = $date_field->first()->getValue();
  
        // Check if a start date is supplied in the query
        // If not, set the first occurence's start date
        if (isset($_GET['start'])) {
          $start_date = new DrupalDateTime($_GET['start']);
  
          self::getDateSpecificContent($vars, $start_date);
          
          if (count($start_date->getErrors()) > 0) {
            $start_date = new DrupalDateTime($first_occurence['value'], new DateTimeZone('UTC'));
            $start_date->setTimezone(new DateTimeZone($first_occurence['timezone']));  
          }
        } else {
          $start_date = new DrupalDateTime($first_occurence['value'], new DateTimeZone('UTC'));
          $start_date->setTimezone(new DateTimeZone($first_occurence['timezone']));
        }
  
        // Check if an end query is supplied in the query
        // If not, set the first occurence's end date
        if (isset($_GET['end'])) {
          $end_date = new DrupalDateTime($_GET['end']);
  
          if (count($end_date->getErrors()) > 0) {
            $end_date = new DrupalDateTime($first_occurence['end_value'], new DateTimeZone('UTC'));
            $end_date->setTimezone(new DateTimeZone($first_occurence['timezone']));
          }
        } else {
          $end_date = new DrupalDateTime($first_occurence['end_value'], new DateTimeZone('UTC'));
          $end_date->setTimezone(new DateTimeZone($first_occurence['timezone']));
        }
  
        $vars['date_query']['start'] = $start_date;
        $vars['date_query']['end'] = $end_date;
      }
    }
  }

  /**
   * Called from self::parseDateQueryToVars()
   */
  protected static function getDateSpecificContent(&$vars, $start_date) {
    $dscIds = $vars['node']->get('field_date_specific_content')->getValue();

    if (!isset($dscIds)) {
      return;
    }

    foreach ($dscIds as $dscId) {
      $dateSpecificContent = Paragraph::load($dscId['target_id']);
      $specificDate = $dateSpecificContent->get('field_dsc_date')->getValue()['0']['value'];

      if ($specificDate === $start_date->format('Y-m-d')) {
        $vars['date_query']['date_specific_content'] = $dateSpecificContent->get('field_dsc_content')->getValue()['0']['value'];
      }
    }
  }
}