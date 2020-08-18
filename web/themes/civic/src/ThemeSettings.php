<?php

namespace Drupal\civic;

use Drupal\Core\Form\FormStateInterface;

class ThemeSettings {
  /**
   * Called from THEME_form_system_theme_settings_alter()
   */
  public static function createSettingsForm(&$form, FormStateInterface &$form_state, $form_id) {
    if (isset($form_id)) {
      return;
    }
  
    $form['layout_settings'] = array(
      '#type' => 'fieldset',
      '#title' => t('Layout Settings'),
      '#weight' => -20,
    );
  
    $form['layout_settings']['header_position_class'] = array(
      '#type' => 'select',
      '#title' => t('Header Position Class'),
      '#default_value' => theme_get_setting('header_position_class'),
      '#options' => array(
        'header-relative' => 'Relative',
        'header-absolute' => 'Absolute',
        'header-fixed' => 'Fixed',
      ),
    );
  
    $form['layout_settings']['header_scrolled_bg'] = array(
      '#type' => 'checkbox',
      '#title' => t('Background When Scrolled'),
      '#default_value' => theme_get_setting('header_scrolled_bg'),
      '#states' => array(
        'visible' => array(
          ':input[name="header_position_class"]' => array('value' => 'header-fixed')
        ),
      ),
    );
  
    $form['layout_settings']['header_width_class'] = array(
      '#type' => 'select',
      '#title' => t('Header Width Class'),
      '#default_value' => theme_get_setting('header_width_class'),
      '#options' => array(
        'container-fluid' => 'Container Fluid',
        'container' => 'Container',
        ' ' => 'None',
      ),
    );

    $form['layout_settings']['content_width_class'] = array(
      '#type' => 'select',
      '#title' => t('Main Content Width Class'),
      '#default_value' => theme_get_setting('content_width_class'),
      '#options' => array(
        'container-fluid' => 'Container Fluid',
        'container' => 'Container',
        ' ' => 'None',
      ),
    );

    $form['layout_settings']['related_content_width_class'] = array(
      '#type' => 'select',
      '#title' => t('Related Content Width Class'),
      '#default_value' => theme_get_setting('related_content_width_class'),
      '#options' => array(
        'container-fluid' => 'Container Fluid',
        'container' => 'Container',
        ' ' => 'None',
      ),
    );
  
    $form['layout_settings']['footer_width_class'] = array(
      '#type' => 'select',
      '#title' => t('Footer Width Class'),
      '#default_value' => theme_get_setting('footer_width_class'),
      '#options' => array(
        'container-fluid' => 'Container Fluid',
        'container' => 'Container',
        ' ' => 'None',
      ),
    );
  
    $form['default_images'] = array(
      '#type' => 'fieldset',
      '#title' => t('Default Background Images'),
      '#weight' => -19,
    );
  
    $form['default_images']['default_basic_page_image'] = array(
      '#type' => 'textfield',
      '#title' => t('Default Basic Page Image'),
      '#description' => t('Add the URL to the desired image.') . '<em>' . t('Example: /sites/default/files/default-images/bg-image.jpg') . '</em>',
      '#default_value' => theme_get_setting('default_basic_page_image'),
    );
  
    $form['default_images']['default_event_image'] = array(
      '#type' => 'textfield',
      '#title' => t('Default Event Image'),
      '#description' => t('Add the URL to the desired image.') . '<em>' . t('Example: /sites/default/files/default-images/bg-image.jpg') . '</em>',
      '#default_value' => theme_get_setting('default_event_image'),
    );
  
    $form['default_images']['default_news_image'] = array(
      '#type' => 'textfield',
      '#title' => t('Default News Image'),
      '#description' => t('Add the URL to the desired image.') . '<em>' . t('Example: /sites/default/files/default-images/bg-image.jpg') . '</em>',
      '#default_value' => theme_get_setting('default_news_image'),
    );
  }

  /**
   * Called from THEME_preprocess_page()
   */
  public static function setPageLayoutVars(&$vars) {
    $vars['header_position_class'] = theme_get_setting('header_position_class');
    $vars['header_width_class'] = theme_get_setting('header_width_class');
    $vars['content_width_class'] = theme_get_setting('content_width_class');
    $vars['related_content_width_class'] = theme_get_setting('related_content_width_class');
    $vars['footer_width_class'] = theme_get_setting('footer_width_class');
    $vars['header_bg_class'] = theme_get_setting('header_scrolled_bg') ? 'scrolled-bg' : '';
  }

  /**
   * Called from THEME_preprocess_node()
   */
  public static function setDefaultImageVars(&$vars) {
    $vars['default_basic_page_image'] = theme_get_setting('default_basic_page_image');
    $vars['default_event_image'] = theme_get_setting('default_event_image');
    $vars['default_news_image'] = theme_get_setting('default_news_image');
  }

  public static function getTranslateIcon(&$vars) {
    $vars['translate_icon'] = '/' . drupal_get_path('theme', 'civic') . '/images/translate-icon.png';
  }
}