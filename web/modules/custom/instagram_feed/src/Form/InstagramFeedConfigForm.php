<?php

namespace Drupal\instagram_feed\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class InstagramFeedConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'instagram_feed_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['instagram_feed.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('instagram_feed.settings');

    $form['client_id'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Instagram App Id'),
      '#default_value' => $config->get('client_id') ?: $this->t(''),
    );

    $form['client_secret'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('client_secret') ?: $this->t(''),
    );

    
    $form['auth_redirect_uri'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Authorize Redirect URI'),
      '#default_value' => $config->get('auth_redirect_uri') ?: $this->t(''),
    );
    
    $form['deauth_redirect_uri'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Deauthorize Redirect URI'),
      '#default_value' => $config->get('deauth_redirect_uri') ?: $this->t(''),
    );
    
    $form['code'] = array(
      '#type' => 'textfield',
      '#maxlength' => 600,
      '#title' => $this->t('Code'),
      '#default_value' => $config->get('code') ?: $this->t(''),
    );

    $form['get_code'] = array(
      '#markup' => $this->t('<div><a href="https://api.instagram.com/oauth/authorize?client_id=:client_id&redirect_uri=:redirect&scope=user_profile,user_media&response_type=code" class="button button--primary">Get Code</a><p>The Instagram App Id and Authorize Redirect URI must be filled out and saved before this link will work.</p></div>', [':client_id' => $config->get('client_id'), ':redirect' => $config->get('auth_redirect_uri')]),
      '#states' => array(
        'visible' => array(
          ':input[name="client_id"]' => array('filled' => TRUE),
          ':input[name="auth_redirect_uri"]' => array('filled' => TRUE),
        ),
      ),
    );

    $form['access_token'] = array(
      '#type' => 'textfield',
      '#maxlength' => 600,
      '#title' => $this->t('Access Token'),
      '#default_value' => $config->get('access_token') ?: $this->t(''),
    );

    $form['get_access_token'] = array(
      '#markup' => $this->t('<div><button type="button" id="get-access-token" class="button button--primary">Get Access Token</button><p>The Instagram App Id, App Secret, Code, and Authorize Redirect URI must be filled out and saved before this will work.</p></div>')
    );

    $form['refresh_access_token'] = array(
      '#markup' => $this->t('<div><button type="button" id="refresh-access-token" class="button button--primary">Refresh Access Token</button><p>The Access Token must be filled out before this will work.</p></div>')
    );
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('instagram_feed.settings');
    $values = $form_state->getValues();

    $config->set('client_id', $values['client_id']);
    $config->set('client_secret', $values['client_secret']);
    $config->set('auth_redirect_uri', $values['auth_redirect_uri']);
    $config->set('deauth_redirect_uri', $values['deauth_redirect_uri']);
    $config->set('code', preg_replace('/\#\_$/', '', $values['code']));
    $config->set('access_token', preg_replace('/\#\_$/', '', $values['access_token']));
    
    $config->save();

    parent::submitForm($form, $form_state);
  }
}