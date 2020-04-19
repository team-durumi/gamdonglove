<?php

/**
 * @file
 * Custom theme hooks.
 */

use \Drupal\Component\Utility\Html;

function gd9_preprocess_html(&$vars) {
    $current_route_name = \Drupal::service('current_route_match')->getRouteName();
    $slug = Html::getClass($current_route_name);
    $vars['attributes']['class'][] = $slug;
}