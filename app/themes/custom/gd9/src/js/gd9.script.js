import 'popper.js';
import 'bootstrap';

(function ($, Drupal) {
  'use strict';
  Drupal.behaviors.helloWorld = {
    attach: function (context) {
      console.log('Hello World');
    }
  };
})(jQuery, Drupal);
