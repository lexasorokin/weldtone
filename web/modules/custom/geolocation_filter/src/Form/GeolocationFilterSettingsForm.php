<?php

namespace Drupal\geolocation_filter\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class GeolocationFilterSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'geolocation_filter_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['geolocation_filter.settings'];
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('geolocation_filter.settings');

    $form['blocked_countries'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Blocked Countries'),
      '#description' => $this->t('Add the 2 letter country code for each country you would like blocked. Seperate each entry with a comma ",". You can find country codes at <a href="@link" target="_blank">Nations Online</a>.', array('@link' => 'https://nationsonline.org/oneworld/country_code_list.htm')),
      '#default_value' => $config->get('blocked_countries') ?: $this->t("CN, KP, MX, RU"),
    );

    $form['blocked_ip_addresses'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Blocked IP Addresses'),
      '#description' => $this->t('Add specific IP addresses to block. Seperate each entry with a comma ","'),
      '#default_value' => $config->get('blocked_ip_addresses') ?: $this->t(''),
    );

    $form['should_redirect'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Redirect Users on Blocked Submission'),
      '#default_value' => $config->get('should_redirect') ?: FALSE,
    );

    $form['redirect_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Redirect URL'),
      '#description' => $this->t('The url to redirect users to on a blocked submission.'),
      '#default_value' => $config->get('redirect_url') ?: '/',
      '#states' => array(
        'visible' => array(
          ':input[name="should_redirect"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['message_type_on_blocked_submission'] = array(
      '#type' => 'select',
      '#title' => 'Message Type on Blocked Submission',
      '#description' => 'The type of message to show (or not show) to users when their submission is blocked. You may not want users to know that there submission was blocked.',
      '#default_value' => $config->get('message_type_on_blocked_submission') ?: $this->t('no_message'),
      '#options' => array(
        'no_message' => 'No Message',
        'status' => 'Success Message',
        'error' => 'Error Message',
      ),
    );

    $form['error_message'] = array(
      '#type' => 'textfield',
      '#title' => 'Error Message',
      '#description' => 'The error message to display to users on a blocked submission.',
      '#default_value' => $config->get('error_message') ?: $this->t('There was a problem with your submission. Please try again later.'),
      '#states' => array(
        'visible' => array(
          ':input[name="message_type_on_blocked_submission"]' => array('value' => 'error'),
        ),
      ),
    );

    $form['success_message'] = array(
      '#type' => 'textfield',
      '#title' => '"Success" Message',
      '#description' => 'The "success" message to display to users on a blocked submission.',
      '#default_value' => $config->get('success_message') ?: $this->t('Your message has been sent.'),
      '#states' => array(
        'visible' => array(
          ':input[name="message_type_on_blocked_submission"]' => array('value' => 'status'),
        ),
      ),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('geolocation_filter.settings');
    $values = $form_state->getValues();

    $config->set('blocked_countries', $values['blocked_countries']);
    $config->set('blocked_ip_addresses', $values['blocked_ip_addresses']);
    $config->set('should_redirect', $values['should_redirect']);
    $config->set('redirect_url', $values['redirect_url']);
    $config->set('message_type_on_blocked_submission', $values['message_type_on_blocked_submission']);
    $config->set('error_message', $this->t($values['error_message']));
    $config->set('success_message', $this->t($values['success_message']));
    $config->save();

    parent::submitForm($form, $form_state);
  }
}