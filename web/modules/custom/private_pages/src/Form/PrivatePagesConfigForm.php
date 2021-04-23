<?php

namespace Drupal\private_pages\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class PrivatePagesConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'private_pages_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['private_pages.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('private_pages.settings');

    $form['included_roles'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Included Roles'),
      '#description' => $this->t('The roles that can view private pages. Separate multiple values with ",".<br>Note: Make sure to use the machine name for roles.<br><em>Example: administrator, content_editor</em>'),
      '#default_value' => $config->get('included_roles') ?: $this->t(''),
    );
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('private_pages.settings');
    $values = $form_state->getValues();

    $config->set('included_roles', $values['included_roles']);
    
    $config->save();

    parent::submitForm($form, $form_state);
  }
}