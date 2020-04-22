<?php
/**
 * @file
 * Theme and preprocess functions for nodes.
 */
function gd9_preprocess_node(&$vars) {
    // 노드에 조회수 변수 추가
    $page_views = \Drupal::service('statistics.storage.node')->fetchView($vars['node']->id());
    if($page_views) {
        $vars['page_views'] = $page_views->getTotalCount();
    }
    
}
