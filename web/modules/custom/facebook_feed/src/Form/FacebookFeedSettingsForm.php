<?php

namespace Drupal\facebook_feed\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class FacebookFeedSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'facebook_feed_settings_form';
  }
 
  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['facebook_feed.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('facebook_feed.settings');

    $form['access_token'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Access Token'),
      '#maxlength' => 512,
      '#description' => $this->t('You will need to obtain an Access Token from Facebook\'s Developer Portal.'),
      '#default_value' => $config->get('access_token') ?: $this->t(''),
    );

    $form['profile_name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Facebook Profile Name'),
      '#description' => $this->t('If you visit the Facebook Profile being used, it should be the name in the URL after facebook.com.'),
      '#default_value' => $config->get('profile_name') ?: $this->t(''),
    );

    $form['num_of_posts'] = array(
      '#type' => 'number',
      '#title' => $this->t('Number of Posts to Load'),
      '#description' => $this->t('Enter how many posts to load'),
      '#default_value' => $config->Get('num_of_posts') ?: $this->t(''),
    );

    $form['preprocess_hook'] = array(
      '#type' => 'select',
      '#title' => $this->t('Preprocess Hook'),
      '#description' => $this->t('The preprocess hook where the "facebookFeed" variable should be created.'),
      '#options' => array(
        'block' => $this->t('Block'),
        'page' => $this->t('Page'),
        'paragraph' => $this->t('Paragraph'),
      ),
      '#required' => TRUE,
      '#default_value' => $config->get('preprocess_hook') ?: 'page',
    );

    $form['element_id'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Element Id'),
      '#default_value' => $config->get('element_id') ?: $this->t(''),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('facebook_feed.settings');
    $values = $form_state->getValues();

    $config->set('access_token', $values['access_token']);
    $config->set('profile_name', $values['profile_name']);
    $config->set('num_of_posts', $values['num_of_posts']);
    $config->set('element_id', $values['element_id']);
    $config->set('preprocess_hook', $values['preprocess_hook']);
    $config->save();

    drupal_set_message(t('Configuration saved.'));
  }
}