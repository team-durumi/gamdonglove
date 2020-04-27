<?php

namespace Drupal\gd9_donate\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Returns responses for gd9_donate routes.
 */
class Gd9DonateController extends ControllerBase {

  public $fields = [];
  public $headers = [
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

  public function __construct() {
    $schema = \Drupal::config('gd9_donate.schema')->get('donations');
    $fields = [];
    foreach ($schema['fields'] as $key => $value) {
      $fields[$key] = $value['description'];
    }
    $this->fields = $fields;
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
    $headers = $this->headers;
    $rows = [];

    $sqlite = Database::getConnection('default', 'donation');
    $results = $sqlite->query('SELECT * FROM gd9_donations ORDER BY did DESC')->fetchAll();

    $encryption_service = \Drupal::service('encryption');
    foreach ($results as $row_key => $result) {
      foreach ($headers as $field_key => $field) {
        if($field_key !== 'did') {
          $$field_key = $encryption_service->decrypt($result->$field_key);
          $rows[$row_key][$field_key] = $$field_key;
        } else {
          $render = [
            '#type' => 'inline_template',
            '#template' => '<a href="/admin/donation/' . $result->did . '">' . $result->did . '</a>',
          ];
          $rows[$row_key]['did'] = [];
          $rows[$row_key]['did']['data'] = $render;
        }
        if($field_key === 'type') {
          $rows[$row_key][$field_key] = '정기';
          if($result->$field_key == 2) $rows[$row_key][$field_key] = '일시';
        }
        if($field_key === 'need_reciept' && !empty($result->account_holder_ssn)) {
          $rows[$row_key][$field_key] = '발급 필요';
        }
        if($field_key === 'created') {
          $rows[$row_key][$field_key] = \Drupal::service('date.formatter')->format($$field_key, 'html_date');
        }
        if($field_key === 'amount') {
          $rows[$row_key][$field_key] .= ' 만원';
        }
        if($field_key === 'signature') {
          $signature = $$field_key;
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

  public function adminDonation(int $did) {
    if(!$did) throw new NotFoundHttpException();

    $donation = [];
    $sqlite = Database::getConnection('default', 'donation');
    $result = $sqlite->query('SELECT * FROM gd9_donations where did = :did;', [':did' => $did])->fetchObject();

    if(!empty($result->did)) {
      $encryption_service = \Drupal::service('encryption');
      foreach ($this->fields as $field_key => $field) {
        if($field_key !== 'did') {
          $donation[$field_key] = $encryption_service->decrypt($result->$field_key);
        } else {
          $donation[$field_key] = $result->did;
        }
        if($field_key === 'type') {
          $donation[$field_key] = '정기';
          if($result->$field_key == 2) $donation[$field_key] = '일시';
        }
        if($field_key === 'created') {
          $donation[$field_key] = \Drupal::service('date.formatter')->format($donation[$field_key], 'html_date');
        }
        if($field_key === 'amount') {
          $donation[$field_key] .= ' 만원';
        }
        if($field_key === 'signature') {
          $signature = $donation[$field_key];
          $image_variables = [
            '#theme' => 'image',
            '#uri' => $signature,
            '#attributes' => [
              'class' => ['img-fluid'],
              'style' => ['width: 320px']
            ]
          ];
          $image = \Drupal::service('renderer')->render($image_variables);
          $render = [
            '#type' => 'inline_template',
            '#template' => $image,
          ];
          $donation[$field_key] = [];
          $donation[$field_key]['data'] = $render;
        }
      }
      $data = [
        'donation' => $donation,
        'lables'=> $this->fields
      ];
    } else {
      throw new NotFoundHttpException();
    }

    return $build['content'] = [
      '#theme' => 'donation',
      '#data' => $data
    ];

  }

}
