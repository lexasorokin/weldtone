<?php

namespace Drupal\json_to_node\Controller;

use Drupal\Core\Controller\ControllerBase;

class JsonToNodeController extends ControllerBase {
  public static function processJsonFeed() {
    $config = \Drupal::config('json_to_node.settings');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $config->get('json_feed_url') . '?_format=json');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  
    $json = curl_exec($ch);
    curl_close($ch);
  
    $response = json_decode($json, TRUE);
    
    self::loopThroughFeedItems($response);
  }

  public static function getExistingNode($database, $item) {
    $existing_nodes = $database->select('json_to_node_created_nodes', 'j')
      ->condition('j.incoming_uuid', $item['uuid'][0]['value'])
      ->fields('j', ['local_node_id'])
      ->execute()
      ->fetchAll();

    $existing_node = reset($existing_nodes);
    return $existing_node;
  }

  public static function loopThroughFeedItems($items) {
    $config = \Drupal::config('json_to_node.settings');
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $database = \Drupal::database();

    foreach ($items as $item) {
      $incoming_type = $item['type'][0]['target_id'];
  
      if ($map_to_type = $config->get($incoming_type . '_content_type_mapping')) {
        $node_data = array(
          'title' => $item['title'],
          'type' => $map_to_type['map_field_to'],
          'langcode' => $item['langcode'],
          'moderation_state' => $item['moderation_state'],
        );
        
        $field_mapping = $config->get($incoming_type . '_field_mapping');
        foreach ($field_mapping as $field) {
          if (isset($item[$field['map_field_from']])) {
            $node_data[$field['map_field_to']] = $item[$field['map_field_from']];
          }
        }
  
        $existing_node = self::getExistingNode($database, $item);

        // kint($existing_node);
  
        if ($existing_node) {
          self::updateExistingNode($existing_node, $node_data, $node_storage, $database);
        } else {
          self::createNewNode($node_data, $item, $database);
        }
      }
    }
  }

  protected static function updateExistingNode($existing_node, $node_data, $node_storage, $database) {
    $changes_made = FALSE;

    $node = $node_storage->load($existing_node->local_node_id);
    foreach($node_data as $field_name => $field_value) {
      $current_value = $node->get($field_name)->getValue();
      if ($current_value != $field_value) {
        // kint($field_value);
        $changes_made = TRUE;
        $node->set($field_name, $field_value);
      }
    }

    // If a change was made, save the updated node.
    if ($changes_made) {
      $node->save();
      $database->update('json_to_node_created_nodes')
        ->fields(array(
          'last_updated_time' => time(),
        ))
        ->condition('local_node_id', $existing_node->local_node_id)
        ->execute();
    }
  }

  protected static function createNewNode($node_data, $item, $database) {
    $node = Node::create($node_data);
    $node->save();

    $database->insert('json_to_node_created_nodes')
      ->fields(array(
        'incoming_uuid' => $item['uuid'][0]['value'],
        'local_node_id' => $node->id(),
        'creation_time' => time(),
        'last_updated_time' => time(),
      ))
      ->execute();
  }
}