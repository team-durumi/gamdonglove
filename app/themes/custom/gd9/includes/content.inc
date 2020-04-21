<?php
/**
 * @file
 * Theme and preprocess functions for nodes.
 */
function gd9_preprocess_node(&$vars) {
    // 노드에 조회수 변수 추가
    $vars['page_views'] = \Drupal::service('statistics.storage.node')->fetchView($vars['node']->id())->getTotalCount();
}
