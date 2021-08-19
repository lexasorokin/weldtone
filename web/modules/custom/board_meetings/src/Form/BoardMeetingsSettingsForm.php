<?php

namespace Drupal\board_meetings\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class BoardMeetingsSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'board_meetings_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['board_meetings.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('board_meetings.settings');

    $form['accordion_color'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Accordion color'),
      '#default_value' => $config->get('accordion_color') ?: $this->t(''),
    );

    $form['registration_webform_id'] = array(
      '#type'          => 'textfield',
      '#title'         => $this->t('Registration webform ID'),
      '#default_value' => $config->get('registration_webform_id') ?: $this->t(''),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('board_meetings.settings');
    $values = $form_state->getValues();

    $config->set('accordion_color', $values['accordion_color']);
    $config->set('registration_webform_id', $values['registration_webform_id']);

    $config->save();

    parent::submitForm($form, $form_state);
  }

}
