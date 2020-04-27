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

    $regular_form = \Drupal::formBuilder()->getForm('Drupal\gd9_donate\Form\Gd9RegularDonateForm');
    $temp_form = \Drupal::formBuilder()->getForm('Drupal\gd9_donate\Form\Gd9TempDonateForm');
    
    $build['content'] = [
      '#theme' => 'donate',
      '#data' => [
        'regular_form' => $regular_form,
        'temp_form' => $temp_form
      ]
    ];

    return $build;
  }

}
