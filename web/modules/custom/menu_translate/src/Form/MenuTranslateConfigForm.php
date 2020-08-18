<?php

namespace Drupal\menu_translate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class MenuTranslateConfigForm extends ConfigFormBase {
  protected $lang_arr = array();

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'menu_translate_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['menu_translate.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('menu_translate.settings');
    
    $this->lang_manager = \Drupal::languageManager();

    foreach ($this->lang_manager->getLanguages() as $lang_code => $lang) {
      if ($lang_code !== 'en') {
        $this->lang_arr[$lang_code] = $lang->getName();
      }
    }

    $form['language_select'] = array(
      '#type' => 'select',
      '#title' => $this->t('Language Select'),
      '#options' => $this->lang_arr,
      '#default_value' => $config->get('language_select') ?: NULL,
    );

    foreach ($this->lang_arr as $lang_code => $lang) {
      $form['menu_items_' . $lang_code] = array(
        '#type' => 'textarea',
        '#title' => $this->t($lang . ' Menu Items'),
        '#description' => $this->t('Add the English title of the menu item to translate, then the symbol "^", then the professionally translated title. Place each menu item on a new line.<br><em>Example: Home ^ Inicio</em><br><br>The cache must be cleared after updating these values for the changes to take effect.'),
        '#default_value' => $config->get('menu_items_' . $lang_code) ?: $this->t(''),
        '#states' => array(
          'visible' => array(
            ':input[name="language_select"]' => array('value' => $lang_code),
          )
        )
      );
    }
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('menu_translate.settings');
    $values = $form_state->getValues();

    $config->set('language_select', $values['language_select']);

    $menu_translations = array();

    foreach ($this->lang_arr as $lang_code => $lang) {
      $config->set('menu_items_' . $lang_code, $values['menu_items_' . $lang_code]);
  
      /** Create The Menu Translations Array from the Menu Items textarea value */
      $menu_items_array = array();
      $menu_items_lines = preg_split('/\r\n|\r|\n/', $values['menu_items_' . $lang_code]);
  
      foreach ($menu_items_lines as $menu_item) {
        $menu_item_arr = explode('^', $menu_item);
        if (count($menu_item_arr) == 2) {
          $menu_item_key = trim($menu_item_arr[0]);
          $menu_item_value = trim($menu_item_arr[1]);
          $menu_items_array[$menu_item_key] = $menu_item_value;
        }
      }
  
      $menu_translations[$lang_code] = $menu_items_array;
    }

    $config->set('menu_translations', $menu_translations);
    $config->save();

    parent::submitForm($form, $form_state);
  }
}