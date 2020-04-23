<?php

namespace Drupal\gd9_donate\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for gd9_donate routes.
 */
class Gd9DonateController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#theme' => 'donate',
      '#data' => [],
    ];

    return $build;
  }

}
