<?php

namespace Drupal\gd9_donate\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Returns responses for gd9_donate routes.
 */
class Gd9DonateController extends ControllerBase {

  public $fields = [];

  public function __construct() {
    $schema = \Drupal::config('gd9_donate.schema')->get('donations');
    $this->fields = $schema['fields'];
  }

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

  public function admin() {
    $headers = [
      'did' => 'ID',
      'type' => '유형',
      'name' => '성명',
      'mobile' => '전화번호',
      'email' => '이메일',
      'address' => '주소',
      'amount' => '금액',
      'withdrawal_date' => '출금일',
      'account_holder' => '예금주',
      'need_reciept' => '영수증',
      'signature' => '서명',
      'created' => '신청일'
    ];
    $rows = [];

    $sqlite = Database::getConnection('default', 'donation');
    $results = $sqlite->query('SELECT * FROM gd9_donations ORDER BY created DESC LIMIT 0,10;')->fetchAll();
    foreach ($results as $row_key => $result) {
      foreach ($headers as $field_key => $field) {
        $rows[$row_key][$field_key] = $result->$field_key;
        if($field_key === 'type') {
          $rows[$row_key][$field_key] = '정기';
          if($result->$field_key == 2) $rows[$row_key][$field_key] = '일시';
        }
        if($field_key === 'need_reciept' && !empty($result->account_holder_ssn)) {
          $rows[$row_key][$field_key] = '발급 필요';
        }
        if($field_key === 'created') {
          $rows[$row_key][$field_key] = \Drupal::service('date.formatter')->format($rows[$row_key][$field_key], 'html_date');
        }
        if($field_key === 'amount') {
          $rows[$row_key][$field_key] .= ' 만원';
        }
        if($field_key === 'signature') {
          $signature = $rows[$row_key][$field_key];
          $image_variables = [
            '#theme' => 'image',
            '#uri' => $signature,
            '#attributes' => [
              'class' => ['img-fluid'],
              'style' => ['width: 100px']
            ]
          ];
          $image = \Drupal::service('renderer')->render($image_variables);
          $render = [
            '#type' => 'inline_template',
            '#template' => $image,
          ];
          $rows[$row_key][$field_key] = [];
          $rows[$row_key][$field_key]['data'] = $render;
        }
      }
    }

    $build['content'] = [
      '#type' => 'table',
      '#attributes' => ['class' => ['table']],
      '#wrapper_attributes' => [ 'class' => [ 'table-responsive' ] ],
      '#caption' => '후원 정보 목록',
      '#header' => $headers,
      '#rows' => $rows
    ];
    return $build;

  }

}
