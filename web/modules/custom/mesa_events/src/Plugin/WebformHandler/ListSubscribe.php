<?php

namespace Drupal\mesa_events\Plugin\WebformHandler;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\webformSubmissionInterface;
use Drupal\commerce_promotion\Entity\Coupon;
use Drupal\Core\Routing\TrustedRedirectResponse;

/**
 * Form submission handler.
 *
 * @WebformHandler(
 *   id = "webform_payment_estimator_form_handler",
 *   label = @Translation("Email list subscribe"),
 *   category = @Translation("Form Handler"),
 *   description = @Translation("Subscribes an entered Email to a certain list"),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 * )
 */
class ListSubscribe extends WebformHandlerBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'list_id'     => '1',
      'email_field' => 'email',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form,
      FormStateInterface $form_state) {


    $form['list_id'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('List ID'),
      '#description'   => $this->t('ID of the list'),
      '#default_value' => $this->configuration['list_id'],
      '#required'      => TRUE,
    ];

    $form['email_field'] = [
      '#type'          => 'textfield',
      '#title'         => $this->t('ID of email field'),
      '#description'   => $this->t('Machine name of the email field'),
      '#default_value' => $this->configuration['email_field'],
      '#required'      => TRUE,
    ];

    return $form;
  }

  public function submitConfigurationForm(array &$form,
      FormStateInterface $form_state) {
    parent::submitConfigurationForm($form, $form_state);
    parent::applyFormStateToConfiguration($form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state,
      WebformSubmissionInterface $webform_submission) {

    $config = $this->configuration;
    $values = $webform_submission->getData();

    $email_field_name = $config['email_field'];

    if (!empty($values[$email_field_name])) {

      mesa_events_list_subscribe($config['list_id'], $values[$email_field_name]);
    }


    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function overrideSettings(array &$settings,
      WebformSubmissionInterface $webform_submission) {
    
  }

}
?>

