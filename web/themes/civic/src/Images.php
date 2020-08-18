<?php

namespace Drupal\civic;

class Images {

  /**
   * Called from Map::getMapLocations()
   */
  public static function getImageUrl($entity, $fieldName) {
    $image_uri = NULL;
    if ($entity->hasField($fieldName)) {
      try {
        $field = $entity->{$fieldName}; // Try loading from field values first.
        if ($field && $field->target_id) {
          $file = \Drupal\file\Entity\File::load($field->target_id);
          if ($file) {
            $image_uri = $file->getFileUri();
          }
        }
      } catch(\Exception $e) {
        \Drupal::logger('get_image_uri')->notice($e->getMessage(), []);
      }
      // If a set value above wasn't found, try the default image.
      if (is_null($image_uri)) {
        try {
          $field = $entity->get($fieldName); // Loading from field definition
          if ($field) {
            // From the image module /core/modules/image/ImageFormatterBase.php
            // $default_image = $test->fieldDefinition->getFieldStorageDefinition()->getSetting('default_image');
            $default_image = $field->getSetting('default_image');
            if ($default_image && $default_image['uuid']) {
              // $defaultImageFile = \Drupal::entityManager()->loadEntityByUuid('file', $default_image['uuid']));
              // See https://www.drupal.org/node/2549139  entityManager is deprecated.
              // Use entity.repository instead.
              $entityrepository = Drupal::service('entity.repository');
              $defaultImageFile = $entityrepository->loadEntityByUuid('file', $default_image['uuid']);
              if ($defaultImageFile) {
                $image_uri = $defaultImageFile->getFileUri();
              }
            }
          }
        } catch(\Exception $e) {
          \Drupal::logger('get_image_uri')->notice($e->getMessage(), []);
        }
      }
    }
    $image_url = file_create_url($image_uri);
    return $image_url;
    // return $image_uri;
  }
}