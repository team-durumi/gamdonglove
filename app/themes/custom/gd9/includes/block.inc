<?php
/**
 * @file
 * Theme and preprocess functions for blocks.
 */
use \Drupal\Core\Url;

 function gd9_preprocess_block(&$vars) {
    switch ($vars['plugin_id']) {
        // 활동 사이드바 서브메뉴
        case 'views_block:actions-block_1':
            $rows = $vars['content']['#view']->result;
            $field = $vars['content']['#view']->field;
            $current_path = \Drupal::service('path.current')->getPath();
            $title = '<a href="/actions">활동</a>';
            // foreach ($rows as $index => $row) {
            //     $render = reset($field['field_actions']->getItems($row))['rendered'];
            //     if($current_path == str_replace('taxonomy/term', '/actions', $render['#url']->getInternalPath())){
            //         $title = $render['#title'];
            //     }
            // }
            $vars['label']['#markup'] = $title;
            break;
    }
 }