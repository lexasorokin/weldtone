<?php

namespace Drupal\autocomplete_inputs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AutocompleteInputsSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'autocomplete_inputs_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['autocomplete_inputs.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('autocomplete_inputs.settings');
    
    $form['selector'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Input Query Selector'),
      '#description' => $this->t('Input the query selector to the form input you want to target.'),
      '#default_value' => $config->get('selector') ?: $this->t(''),
    );

    $form['include_all_nodes'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Include All Nodes in Autocomplete Options'),
      '#description' => $this->t('If selected, the database will be queried so that all non-private nodes in the user\'s selected language will appear'),
      '#default_value' => $config->get('include_all_nodes') ?: FALSE,
    );

    $form['nodes_to_exclude'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Nodes to Exclude'),
      '#description' => $this->t('Include the name of any node you don\'t want included in the autocomplete inputs. For example, you might want to exclude the page that the input is on. Enter each item on a new line.'),
      '#default_value' => $config->get('nodes_to_exclude') ?: $this->t(''),
      '#states' => array(
        'visible' => array(
          ':input[name="include_all_nodes"]' => array('checked' => TRUE),
        )
      )
    );

    $form['autocomplete_options'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Custom Autocomplete Options'),
      '#description' => $this->t('Enter each search item on a new line. If you would like the result to automatically link to a page, place the link inside of brackets following the test.<br><em><strong>Example:</strong> Starting a Business[/business/starting-business]</em>'),
      '#default_value' => $config->get('autocomplete_options') ?: $this->t(''),
    );

    $form['colors'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Colors'),
    );

    $form['colors']['is_bg_color_striped'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Two-Toned Background Colors'),
      '#default_value' => $config->get('is_bg_color_striped') ?: FALSE,
    );

    $form['colors']['bg_color_odd'] = array(
      '#type' => 'color',
      '#title' => $this->t('Background Color'),
      '#default_value' => $config->get('bg_color_odd') ?: $this->t(''),
    );

    $form['colors']['bg_color_even'] = array(
      '#type' => 'color',
      '#title' => $this->t('Background Color Alternate'),
      '#default_value' => $config->get('bg_color_even') ?: $this->t(''),
      '#states' => array(
        'visible' => array(
          ':input[name="is_bg_color_striped"]' => ['checked' => TRUE]
        )
      )
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('autocomplete_inputs.settings');
    $values = $form_state->getValues();

    $config->set('selector', $values['selector']);
    $config->set('autocomplete_options', $values['autocomplete_options']);
    $config->set('include_all_nodes', $values['include_all_nodes']);
    $config->set('nodes_to_exclude', $values['nodes_to_exclude']);
    $config->set('bg_color_odd', $values['bg_color_odd']);
    $config->set('is_bg_color_striped', $values['is_bg_color_striped']);
    if ($values['is_bg_color_striped']) {
      $config->set('bg_color_even', $values['bg_color_even']);
    } else {
      $config->set('bg_color_even', $values['bg_color_odd']);
    }

    // Get the autocomplete_options textarea, then split the string on each new line
    // Loop through that array and get the text and possible link for each item.
    $processed_options = array();
    $options = explode("\n", $values['autocomplete_options']);
    foreach ($options as $option) {
      $hasLink = preg_match('/\[.*\]/', $option, $matches);
      if ($hasLink) {
        $match = reset($matches);
        $match = trim($match, "[]\n\r");
        array_push($processed_options, array(
          'link' => $match,
          'text' => preg_replace('/\[.*\]\n?\r?/', '', $option),
        ));
      } else {
        array_push($processed_options, array(
          'text' => $option
        ));
      }
    }
    $config->set('autocomplete_options_processed', $processed_options);

    $config->save();

    parent::submitForm($form, $form_state);
  }
}