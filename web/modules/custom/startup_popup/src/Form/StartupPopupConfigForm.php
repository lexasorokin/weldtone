<?php

namespace Drupal\startup_popup\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Form\FormBase;


class StartupPopupConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'startup_popup_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['startup_popup.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('startup_popup.settings');

    $form['popup_enabled'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Module Functionality'),
      '#description' => $this->t('This allows you to enable/disable the popup and redirect functionality, without having to add/remove the module.'),
      '#default_value' => $config->get('popup_enabled') ?: TRUE,
    );

    $form['use_redirect_anon'] = array(
      '#type' => 'checkbox',
      '#title' => 'Use Redirect Instead of Popup',
      '#description' => $this->t('If selected this module will redirect all users on non-ignored routes to a set URL'),
      '#default_value' => $config->get('use_redirect_anon') !== NULL ? $config->get('use_redirect_anon') : FALSE,
    );

    $form['use_redirect_auth'] = array(
      '#type' => 'checkbox',
      '#title' => 'Apply Redirect to Authenticated Users',
      '#description' => $this->t('If not selected, the redirect will only apply to anonymus users.'),
      '#default_value' => $config->get('use_redirect_auth') !== NULL ? $config->get('use_redirect_auth') : FALSE,
      '#states' => array(
        'visible' => array(
          ':input[name="use_redirect_anon"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['redirect_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Redirect URL'),
      '#description' => $this->t('The URL that users will be redirected to.'),
      '#default_value' => $config->get('redirect_url') ?: '',
      '#states' => array(
        'visible' => array(
          ':input[name="use_redirect_anon"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['popup_body'] = array(
      '#type' => 'text_format',
      '#title' => $this->t('Popup Body'),
      '#description' => $this->t('The content of the popup'),
      '#format' => 'full_html',
      '#default_value' => $config->get('popup_body')['value'] ?: $this->t(''),
      '#states' => array(
        'visible' => array(
          ':input[name="use_redirect_anon"]' => array('checked' => FALSE),
        ),
      ),
    );

    $form['auth_users'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Authenticated Users'),
    );

    $form['auth_users']['frequency_auth'] = array(
      '#type' => 'select',
      '#title' => $this->t('Frequency for Authenticated Users'),
      '#description' => $this->t('How frequently the popup message should be shown to authenticated users.'),
      '#options' => [
        'never' => 'Never',
        'once' => 'Once',
        'daily' => 'Once Per Day',
        'always' => 'Always',
      ],
      '#default_value' => $config->get('frequency_auth') ?: NULL,
      '#states' => array(
        'visible' => array(
          ':input[name="use_redirect_anon"]' => array('checked' => FALSE),
        ),
      ),
    );

    $form['auth_users']['dismissable_auth'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Dismissable for Authenticated Users'),
      '#description' => $this->t('Whether or not the popup can be dismissed by authenticated users'),
      '#default_value' => $config->get('dismissable') !== NULL ? $config->get('dismissable') : TRUE,
      '#states' => array(
        'visible' => array(
          ':input[name="use_redirect_anon"]' => array('checked' => FALSE),
        ),
      ),
    );

    $form['auth_users']['routes_to_ignore_auth'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Routes to Ignore for Anonymous Users'),
      '#description' => $this->t("Relative routes to not include the popup. Typically you would want your login route to not have the popup.<br>Example: /user/login"),
      '#default_value' => $config->get('routes_to_ignore_auth') ?: '',
    );

    $form['anon_users'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Anonymous Users'),
    );

    $form['anon_users']['frequency_anon'] = array(
      '#type' => 'select',
      '#title' => $this->t('Frequency for Anonymous Users'),
      '#description' => $this->t('How frequently the popup message should be shown to anonymous users.'),
      '#options' => [
        'once' => 'Once',
        'daily' => 'Once Per Day',
        'always' => 'Always',
      ],
      '#default_value' => $config->get('frequency_anon') ?: NULL,
      '#states' => array(
        'visible' => array(
          ':input[name="use_redirect_anon"]' => array('checked' => FALSE),
        ),
      ),
    );

    $form['anon_users']['dismissable_anon'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Dismissable for Anonymous Users'),
      '#description' => $this->t('Whether or not the popup can be dismissed by anonymous users'),
      '#default_value' => $config->get('dismissable') !== NULL ? $config->get('dismissable') : TRUE,
      '#states' => array(
        'visible' => array(
          ':input[name="use_redirect_anon"]' => array('checked' => FALSE),
        ),
      ),
    );

    $form['anon_users']['routes_to_ignore_anon'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Routes to Ignore for Anonymous Users'),
      '#description' => $this->t("Relative routes to not include the popup. Typically you would want your login route to not have the popup.<br>Example: /user/login"),
      '#default_value' => $config->get('routes_to_ignore_anon') ?: '',
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('startup_popup.settings');
    $values = $form_state->getValues();

    $config->set('popup_enabled', $values['popup_enabled']);
    $config->set('use_redirect_anon', $values['use_redirect_anon']);
    $config->set('use_redirect_auth', $values['use_redirect_auth']);
    $config->set('redirect_url', $values['redirect_url']);
    $config->set('popup_body', $values['popup_body']);
    $config->set('frequency_auth', $values['frequency_auth']);
    $config->set('frequency_anon', $values['frequency_anon']);
    $config->set('dismissable_auth', $values['dismissable_auth']);
    $config->set('dismissable_anon', $values['dismissable_anon']);
    $config->set('routes_to_ignore_auth', $values['routes_to_ignore_auth']);
    $config->set('routes_to_ignore_anon', $values['routes_to_ignore_anon']);

    $config->save();

    parent::submitForm($form, $form_state);
  }
}
