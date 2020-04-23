import 'popper.js';
import 'bootstrap';

(function ($, Drupal) {
  'use strict';
  var actions_block = $('.block--views-block--actions-block-1');
  if(actions_block.length > 0) {
    let all = '<li class="list-group-item all active"><a href="/actions">전체</a></li>';
    actions_block.find('.list-group').prepend(all);
    if(actions_block.find('.list-group-item.active').not('.all').length > 0) {
      $('.list-group-item.all').removeClass('active');
    }
  }
})(jQuery, Drupal);
