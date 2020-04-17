<?php
/**
 * @file
 * Theme and preprocess functions for views.
 */

function gd9_preprocess_views_view_summary($vars) {
  // 활동, 자료실 게시판 사이드메뉴용 링크 tid가 아니라 제목을 출력합니다.
  $boards = ['actions', 'references'];
  if(in_array($vars['view']->id(), $boards) && strpos($vars['view']->current_display, 'block_1') !== false) {
    foreach ($vars['rows'] as $id => $row) {
      $term = Drupal\taxonomy\Entity\Term::load($row->link);
      $vars['rows'][$id]->link = $term->getName();
    }
  }
}

function gd9_preprocess_views_view(&$vars) {
  // 첫 화면 최신 글 뷰
  if($vars['id'] == 'content_recent') {
    switch($vars['display_id']) {
      case 'annual_report':
        $vars['report'] = $vars['rows'][0]['#rows'][0]['#row']->_entity;
      break;
      case 'actions':
      break;
      case 'notices':
      break;

    }
  }
}
