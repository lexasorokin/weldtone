<?php

namespace Drupal\mesa_moderation\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class MesaModerationTestForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mesa_moderation_test_form';
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

    $form['date'] = array(
      '#type'          => 'date',
      '#title'         => $this->t('Date'),
      '#default_value' => date('Y-m-d', time()),
    );

    $form['submit'] = [
      '#type'  => 'submit',
      '#value' => t('Evaluate reminders for the selected date'),
    ];

    return $form;

    //return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    if (!empty($values['date'])) {
      $timestamp = strtotime($values['date']);
      
      mesa_moderation_select_pending_reminders($timestamp);

    }
    parent::submitForm($form, $form_state);
  }

}
