<?php

/**
 * @file
 * Custom theme hooks.
 */

use \Drupal\Component\Utility\Html;
use \Drupal\Core\Render\Markup;
use \Drupal\Core\Url;

function gd9_preprocess_html(&$vars) {
    $current_route_name = \Drupal::service('current_route_match')->getRouteName();
    $slug = Html::getClass($current_route_name);
    $vars['attributes']['class'][] = $slug;
    if($slug == 'entitynodecanonical') {
        $vars['attributes']['class'][] = 'page--node';
        $vars['attributes']['class'][] = Html::getClass('node-type--' . $vars['node_type']);
    }
}

function gd9_preprocess_page_title(&$vars) {
    if ($node = \Drupal::routeMatch()->getParameter('node')) {
        switch ($node->bundle()) {
            case 'references':
                $category = $node->get('field_references')->first()->view();
                $category['#url'] = Url::fromUri('internal:/references/'.$category['#options']['entity']->id());
                $category['#attributes'] = [
                    'class' => 'badge badge-info ml-3',
                    'style' => 'font-size: 1.2rem; position: absolute;'
                ];
                $vars['category'] = $category;
                break;
            case 'actions':
                $category = $node->get('field_actions')->first()->view();
                $category['#url'] = Url::fromUri('internal:/actions/'.$category['#options']['entity']->id());
                $category['#attributes'] = [
                    'class' => 'badge badge-info ml-3',
                    'style' => 'font-size: 1.2rem; position: absolute;'
                ];
                $vars['category'] = $category;
                break;
        }
    }
}