<?php

namespace Drupal\instagram_feed\Controller;

use Drupal\Core\Controller\ControllerBase;

class InstagramFeedController extends ControllerBase {

  public function authorize() {
    $markup = '';
    $markup .= '<h2>Authorize</h2>';
    return ['#markup' => $markup];
  }

  public function deauthorize() {
    $markup = '';
    $markup .= '<h2>Deauthorize</h2>';
    return ['#markup' => $markup];
  }
}