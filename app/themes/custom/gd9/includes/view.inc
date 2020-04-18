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
    $result_rows = array_column($vars['rows'][0]['#rows'], '#row');
    $fields = $vars['view']->field;
    $vars['actions'] = $vars['notices'] = [];

    switch($vars['display_id']) {
      case 'annual_report':
        $vars['classes'] = [ 'js-view-dom-id-' . $vars['dom_id'], 'ipost', 'clearfix' ];
        $row = reset($result_rows);
        foreach ($fields as $key => $field) {
          $vars['row'][$key] = ($key == 'totalcount') ? $field->last_render_text : reset($field->getItems($row))['rendered'];
        }
      break;
      case 'actions':
        $vars['attributes']['class'] += [ 'widget', 'owl-carousel', 'posts-carousel', 'carousel-widget' ];
        $vars['attributes']['id'] = ['content-recent-actions'];
        $vars['attributes'] += [
          'data-margin' => '20',
          'data-nav' => 'true',
          'data-pagi' => 'true',
          'data-items-xs' => '1',
          'data-items-xl' => '2',
        ];
        foreach ($result_rows as $index => $view_row) {
          foreach ($fields as $key => $field) {
            $vars['actions'][$index][$key] = ($key == 'totalcount') ? $field->last_render_text : reset($field->getItems($result_rows[$index]))['rendered'];
          }
        }
      break;
      case 'notices':
        $vars['attributes']['class'] += [ 'widget', 'owl-carousel', 'posts-carousel', 'carousel-widget' ];
        $vars['attributes']['id'] = ['content-recent-actions'];
        $vars['attributes'] += [
          'data-margin' => '20',
          'data-nav' => 'true',
          'data-pagi' => 'true',
          'data-items-xs' => '1',
          'data-items-xl' => '2',
        ];
        foreach ($result_rows as $index => $view_row) {
          foreach ($fields as $key => $field) {
            $vars['actions'][$index][$key] = ($key == 'totalcount') ? $field->last_render_text : reset($field->getItems($result_rows[$index]))['rendered'];
          }
        }
      break;

    }
  }
}
