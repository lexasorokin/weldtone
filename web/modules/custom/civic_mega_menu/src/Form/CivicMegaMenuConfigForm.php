<?php

namespace Drupal\civic_mega_menu\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CivicMegaMenuConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'civic_mega_menu_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['civic_mega_menu.settings'];
  }

  protected $parentLinkOptions = array();
  protected $availableBlocks;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $menu_tree = \Drupal::menuTree();
    $menu_parameters = new \Drupal\Core\Menu\MenuTreeParameters();
    $menu_parameters->onlyEnabledLinks();
    
    // If you need to set depth of the tree
    $menu_parameters->setMaxDepth(4);

    $tree = $menu_tree->load('main', $menu_parameters);
    $this->parentLinkOptions = $this->buildParentLinkOptions($tree);

    $block_ids = \Drupal::entityQuery('block')->execute();
    array_unshift($block_ids, '- no block -');
    $this->availableBlocks = $block_ids;

    $this->buildFormLayoutChoices($form);
    $this->buildFormTopLevelMenuItems($form);
    
    return parent::buildForm($form, $form_state);
  }

  /**
   * First loops through and set the value to the weight
   * Then sorts by the weight value
   * Once the array is order, the tree is looped through again
   * and the desired values are added in.
   */
  protected function buildParentLinkOptions($tree, &$linkOptions = array(), $depth = 0) {
    foreach ($tree as $branch) {
      $uuid = $branch->link->getPluginId();
      $linkOptions[$uuid] = $branch->link->getWeight();
    }

    asort($linkOptions);

    foreach ($tree as $branch) {
      $title = $branch->link->getTitle();
      $uuid = $branch->link->getPluginId();
      $enabled = $branch->link->isEnabled();
      
      if ($enabled) {
        $linkOptions[$uuid] = array(
          'label' => $title,
          'weight' => $branch->link->getWeight(),
        );
  
        // For some reason, some branches have subtrees even though 'hasChildren' is FALSE
        if ($branch->hasChildren && isset($branch->subtree) && count($branch->subtree) > 0) {
          $linkOptions[$uuid]['submenu'] = array();
          $this->buildParentLinkOptions($branch->subtree, $linkOptions[$uuid]['submenu'], $depth + 1);
        }
      }
    }

    return $linkOptions;
  }

  /**
   * 
   */
  protected function buildFormTopLevelMenuItems(&$form) {
    $config = $this->config('civic_mega_menu.settings');

    $form['links'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Links'),
    );

    foreach ($this->parentLinkOptions as $uuid => $link) {
      $form['links'][$uuid] = array(
        '#type' => 'fieldset',
        '#title' => $this->t($link['label']),
      );

      if (isset($link['submenu'])) {
        $form['links'][$uuid][$uuid . '_submenu'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t($link['label'] . ' | Submenu (Level 2)'),
        );
  
        $this->buildSecondLevelMenuItems($form['links'][$uuid][$uuid . '_submenu'], $uuid, $link['submenu']);
      }
    }
  }

  /**
   * 
   */
  protected function buildSecondLevelMenuItems(&$form, $uuid, $submenu) {
    $config = $this->config('civic_mega_menu.settings');

    $form[$uuid . '_block_1'] = array(
      '#type' => 'select',
      '#title' => 'First Column Block',
      '#options' => $this->availableBlocks,
      '#default_value' => $config->get($uuid . '_block_1'),
      '#states' => array(
        'visible' => array(
          array(
            ':input[name="layout"]' => array(
              'value' => 2,
            ),
          ),
          array(
            ':input[name="layout"]' => array(
              'value' => 3,
            ),
          )
        ),
      ),
    );

    $form[$uuid . '_block_2'] = array(
      '#type' => 'select',
      '#title' => 'Last Column Block',
      '#options' => $this->availableBlocks,
      '#default_value' => $config->get($uuid . '_block_2'),
      '#states' => array(
        'visible' => array(
          array(
            ':input[name="layout"]' => array(
              'value' => 2,
            ),
          ),
          array(
            ':input[name="layout"]' => array(
              'value' => 4,
            ),
          )
        ),
      ),
    );
    
    foreach ($submenu as $uuid => $link) {
      if (isset($link['submenu'])) {
        $form[$uuid][$uuid . '_submenu'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t($link['label'] . ' | Submenu (Level 3)'),
        );

        $this->buildFormThirdLevelMenuItems($form[$uuid][$uuid . '_submenu'], $uuid, $link['submenu']);
      } else {
        $form[$uuid][$uuid . '_menu_item'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t($link['label']),
        );
      }
    }
  }

  /**
   * 
   */
  protected function buildFormThirdLevelMenuItems(&$form, $uuid, $submenu) {
    $config = $this->config('civic_mega_menu.settings');

    $form[$uuid . '_block_1'] = array(
      '#type' => 'select',
      '#title' => 'Column Block',
      '#options' => $this->availableBlocks,
      '#default_value' => $config->get($uuid . '_block_1'),
      '#states' => array(
        'visible' => array(
          array(
            ':input[name="layout"]' => array(
              'value' => 3,
            ),
          ),
        ),
      ),
    );

    foreach ($submenu as $uuid => $link) {
      // if (isset($link['submenu'])) {
      //   $form[$uuid][$uuid . '_submenu'] = array(
      //     '#type' => 'fieldset',
      //     '#title' => $this->t($link['label'] . ' | Submenu (Level 4)'),
      //   );

      //   $this->buildFormFourthLevelMenuItems($form[$uuid][$uuid . '_submenu'], $uuid, $link['submenu']);
      // } else {
        $form[$uuid][$uuid . '_menu_item'] = array(
          '#type' => 'fieldset',
          '#title' => $this->t($link['label']),
        );


      // }
    }
  }

    /**
   * 
   */
  protected function buildFormFourthLevelMenuItems(&$form, $uuid, $submenu) {
    $config = $this->config('civic_mega_menu.settings');

    $form[$uuid . '_block_1'] = array(
      '#type' => 'select',
      '#title' => 'Column Block',
      '#options' => $this->availableBlocks,
      '#default_value' => $config->get($uuid . '_block_1'),
      '#states' => array(
        'visible' => array(
          array(
            ':input[name="layout"]' => array(
              'value' => 3,
            ),
          ),
        ),
      ),
    );

    $sublinksList = '';
    foreach ($submenu as $link) {
      $sublinksList .= '<li>' . $link['label'] . '</li>';
    }

    $form[$uuid . '_sublinks']['#markup'] = '<ul>' . $sublinksList . '</ul>';
  }

  /**
   * 
   */
  protected function buildFormLayoutChoices(&$form) {
    $config = $this->config('civic_mega_menu.settings');
    $module_handler = \Drupal::service('module_handler');
    $module_path = $module_handler->getModule('civic_mega_menu')->getPath();

    $form['basic_settings'] = array(
      '#type' => 'fieldset',
      '#title' => 'Basic Settings'
    );

    $form['basic_settings']['use_mega_menu'] = array(
      '#type' => 'checkbox',
      '#title' => 'Use Civic Mega Menu',
      '#description' => 'Toggle using the Mega Menu, this is an alternative to uninstalling the module.',
      '#default_value' => $config->get('use_mega_menu') ?: TRUE,
    );

    $form['basic_settings']['hide_private_page_links'] = array(
      '#type' => 'checkbox',
      '#title' => 'Hide Links to Private Pages from Menu',
      '#description' => $this->t('Checking this prevent the caching of the some menu items, which could hurt performance.'),
      '#default_value' => $config->get('hide_private_page_links') ?: FALSE,
    );

    $form['basic_settings']['layout'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Layout'),
      '#options' => array(
        1 => '<img class="menu-layout-option" src="/' . $module_path . '/images/layout-1.png">',
        2 => '<img class="menu-layout-option" src="/' . $module_path . '/images/layout-2.png">',
        3 => '<img class="menu-layout-option" src="/' . $module_path . '/images/layout-3.png">',
        4 => '<img class="menu-layout-option" src="/' . $module_path . '/images/layout-4.png">',
      ),
      '#description' => $this->t('Red = Level 1 Menu Item.&nbsp;&nbsp;Dark Orange = Level 2 Menu Item.&nbsp;&nbsp;Light Orange = Level 3 Menu Item.&nbsp;&nbsp;Green = Block.'),
      '#default_value' => $config->get('layout') ?: 0,
    );

    $form['basic_settings']['include_level2_title'] = array(
      '#type' => 'checkbox',
      '#title' => 'Include Submenu Title',
      '#default_value' => $config->get('include_level2_title'),
      '#description' => 'Includes the top level menu item name as a header for the first submenu.'
    );

    $form['basic_settings']['hover_or_click'] = array(
      '#type' => 'select',
      '#title' => 'Expand Mode',
      '#options' => array(
        'hover' => $this->t('On Hover'),
        'click' => $this->t('On Click'),
      ),
      '#default_value' => $config->get('hover_or_click') ?: 'hover',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('civic_mega_menu.settings');
    $config->delete();

    $values = $form_state->getValues();

    foreach ($values as $key => $value) {
      if ($key != 'submit' && $key != 'form_build_id' && $key != 'form_token' && $key != 'form_id' && $key != 'op') {
        $config->set($key, $value);
      }
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }
}