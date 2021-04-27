<?php

namespace Drupal\text_alert\Form;

use Drupal\Core\Url;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class TextAlertConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'text_alert_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['text_alert.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('text_alert.settings');

    $form['help'] = array(
      '#markup' => $this->t('For more information about how this module works, head to the <a href=":help">help page</a>.', [':help' => Url::fromRoute('help.page', ['name' => 'text_alert'])->toString()]),
    );

    $form['phone_numbers'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Phone Numbers'),
      '#default_value' => $config->get('phone_numbers') ?: $this->t(''),
    );
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('text_alert.settings');
    $values = $form_state->getValues();

    $config->set('phone_numbers', $values['phone_numbers']);
    
    $config->save();

    parent::submitForm($form, $form_state);
  }
}