<?php

namespace Drupal\json_to_node\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class JSONToNodeConfigForm extends ConfigFormBase {

  protected $taxonomy_arr = array();

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'json_to_node_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['json_to_node.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('json_to_node.settings');

    $node_types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();

    $uids = \Drupal::entityQuery('user')->execute();
    $users = user_load_multiple($uids);
    $user_options = array();
    foreach ($users as $uid => $user) {
      if ($uid > 0) {
        $user_options[$uid] = $user->getUsername();
      }
    }

    $form['general_settings'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('General Settings'),
    );

    $form['general_settings']['json_feed_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('JSON Feed Url'),
      '#default_value' => $config->get('json_feed_url') ?: $this->t(''),
    );

    $form['general_settings']['file_save_author'] = array(
      '#type' => 'select',
      '#title' => $this->t('File Save Author'),
      '#options' => $user_options,
      '#description' => $this->t('When new files are created, the selected user will be the author.'),
      '#default_value' => $config->get('file_save_author') ?: NULL
    );

    $form['general_settings']['file_save_location'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('File Save Location:') . '<br><span class="fw-400">' . \Drupal::service('file_system')->realpath('public://') . '/' . '</span>',
      '#description' => $this->t('If the feed comes across files (such as images) they will be saved to this location. Ensure this location exists and that it is writable.'),
      '#default_value' => $config->get('file_save_location') ?: $this->t(''),
    );

    $form['general_settings']['handle_existing_nodes'] = array(
      '#type' => 'select',
      '#title' => $this->t('How to Handle Existing Nodes'),
      '#options' => array(
        'ignore' => 'Always Ignore',
        'update_text' => 'Update When Text Field Data Changes',
        'update_all' => 'Update When Any Incoming Data Changes'
      ),
      '#default_value' => $config->get('handle_existing_nodes') ?: NULL,
    );

    $form['map_nodes'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Map Content Types (Nodes)'),
    );

    foreach ($node_types as $node_type) {
      $type = $node_type->get('type');


      $form['map_nodes']['include_' . $type] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Include Mapping for ' . $node_type->get('name')),
        '#default_value' => $config->get('include_' . $type) ?: FALSE,
      );

      $form['map_nodes'][$type .'_map_content_type'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Map Content Type "' . $type . '" From Incoming Content Type:'),
        '#default_value' => $config->get($type . '_map_content_type') ?: $this->t(''),
        '#states' => array(
          'visible' => array(
            ':input[name="include_' . $type . '"]' => array(
              'checked' => TRUE,
            )
          )
        )
      );

      $form['map_nodes'][$type] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('Map Fields For ' . $node_type->get('name')),
        '#states' => array(
          'visible' => array(
            ':input[name="include_' . $type . '"]' => array(
              'checked' => TRUE,
            )
          )
        )
      );

      // $form['map_nodes'][$type][$type . '_overwrite_all'] = array(
      //   '#type' => 'checkbox',
      //   '#title' => $this->t('Overwite all fields when incoming field data changes.'),
      //   '#default_value' => $config->get($type . '_overwrite_all') ?: FALSE,
      // );

      $fields = \Drupal::service('entity_field.manager')->getFieldDefinitions('node', $type);
      foreach ($fields as $field_name => $field) {
        if ($field_name == 'body' || preg_match('/^field/', $field_name)) {
          $form['map_nodes'][$type][$type .'_map_' . $field_name] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Set Field "' . $field_name . '" from Incoming Field:'),
            '#default_value' => $config->get($type . '_map_' . $field_name) ?: $this->t(''),
          );

          // $form['map_nodes'][$type][$type . '_overwrite_' . $field_name] = array(
          //   '#type' => 'checkbox',
          //   '#title' => $this->t(' Overwrite "' . $field_name . '" when incoming field data changes?'),
          //   '#default_value' => $config->get($type . '_overwrite_' . $field_name) ?: FALSE,
          //   '#states' => array(
          //     'checked' => array(
          //       ':input[name="' . $type . '_overwrite_all"]' => array(
          //         'checked' => TRUE,
          //       )
          //     )
          //   )
          // );
        }
      }
    }

    $form['map_taxonomy'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Map Taxonomy Terms'),
      '#description' => $this->t('Taxonomy references in the incoming feed will only have an id. In this section you can map those ids to your existing taxonomy terms.'),
    );

    $tids = \Drupal::entityQuery('taxonomy_term')->execute();
    $taxonomy_terms = taxonomy_term_load_multiple($tids);

    foreach ($taxonomy_terms as $taxonomy_term) {
      if (!isset($this->taxonomy_arr[$taxonomy_term->bundle()])) {
        $this->taxonomy_arr[$taxonomy_term->bundle()] = array();
      }

      $this->taxonomy_arr[$taxonomy_term->bundle()][$taxonomy_term->id()] = $taxonomy_term->getName();
    }

    foreach ($this->taxonomy_arr as $vocabulary => $tax_terms) {
      $form['map_taxonomy']['map_' . $vocabulary] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Map Vocabulary "') . $vocabulary . '"',
        '#default_value' => $config->get('map_' . $vocabulary) ?: FALSE,
      );

      foreach($tax_terms as $tid => $tax_term_name) {
        $form['map_taxonomy']['map_tid_' . $tid] = array(
          '#type' => 'number',
          '#title' => $this->t('Map Taxonomy Term "' . $tax_term_name . '" From Incoming Term Id:'),
          '#default_value' => $config->get('map_tid_' . $tid) ?: NULL,
          '#states' => array(
            'visible' => array(
              ':input[name="map_' . $vocabulary . '"]' => array(
                'checked' => TRUE,
              )
            )
          )
        );
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $node_types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();

    $config = $this->config('json_to_node.settings');
    $config->delete();

    $values = $form_state->getValues();

    $config->set('json_feed_url', $values['json_feed_url']);
    $config->set('file_save_author', $values['file_save_author']);
    $config->set('handle_existing_nodes', $values['handle_existing_nodes']);

    // Ensure the file save location ends with a trailing slash
    if (!preg_match('/\/$/', $values['file_save_location'])) {
      $config->set('file_save_location', $values['file_save_location'] . '/');
    } else {
      $config->set('file_save_location', $values['file_save_location']);
    }

    $taxonomy_mapping = array();
    foreach($this->taxonomy_arr as $vocabulary => $tax_terms) {
      $config->set('map_' . $vocabulary, $values['map_' . $vocabulary]);

      foreach ($tax_terms as $tid => $term_name) {
        $config->set('map_tid_' . $tid, $values['map_tid_' . $tid]);
        if (isset($values['map_tid_' . $tid]) && $values['map_tid_' . $tid] != '') {
          $taxonomy_mapping[$values['map_tid_' . $tid]] = array(
            'map_field_to' => $tid,
            'map_field_from' => $values['map_tid_' . $tid],
          );
        }
      }
    }

    $config->set('taxonomy_mapping', $taxonomy_mapping);

    foreach ($node_types as $node_type) {
      $type = $node_type->get('type');
      
      $config->set('include_' . $type, $values['include_' . $type]);
      $config->set($type . '_map_content_type', $values[$type . '_map_content_type']);
      // $config->set($type . '_overwrite_all', $values[$type . '_overwrite_all']);

      $fields = \Drupal::service('entity_field.manager')->getFieldDefinitions('node', $type);
      $content_mapping = array();
      foreach ($fields as $field_name => $field) {
        if ($field_name == 'body' || preg_match('/^field/', $field_name)) {
          $config->set($type . '_map_' . $field_name, $values[$type . '_map_' . $field_name]);
          // $config->set($type . '_overwrite_' . $field_name, $values[$type . '_overwrite_' . $field_name]);
          $content_mapping[] = array(
            'map_field_to' => $field_name,
            'map_field_from' => $values[$type . '_map_' . $field_name],
          );
        }
      }

      // It's important we name this based on the expected incoming content type
      if ($values['include_' . $type]) {
        $config->set($values[$type . '_map_content_type'] . '_field_mapping', $content_mapping);
        $config->set($values[$type . '_map_content_type'] . '_content_type_mapping', array(
          'map_field_to' => $type,
          'map_field_from' => $values[$type . '_map_content_type'],
        ));
      }
    }
    
    $config->save();

    parent::submitForm($form, $form_state);
  }
}