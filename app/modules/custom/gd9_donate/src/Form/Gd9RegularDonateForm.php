<?php

namespace Drupal\gd9_donate\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Implements an gd9_regular_donate form.
 */
class Gd9RegularDonateForm extends FormBase {

  public $fields = [];

  public function __construct() {
    $schema = \Drupal::config('gd9_donate.schema')->get('donations');
    $this->fields = $schema['fields'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gd9_regular_donate_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // ksm($this->fields);
    $values = $form_state->getValues();
    // ksm($values);

    $form['#attributes']['novalidate'] = true;

    $inline_form_element = [
      '#wrapper_attributes' => [ 'class' => [ 'form-group', 'row' ] ],
      '#label_attributes' => [ 'class' => ['col-sm-2', 'col-form-label'] ],
      '#field_prefix' => '<div class="col-sm-10">',
      '#field_suffix' => '</div>'
    ];

    $form['title']['#markup'] = '<h2 class="my-3">온라인 후원등록</h2>';
    $form['type'] = [
      '#type' => 'hidden',
      '#default_value' => '1'
    ];
    $form['donor'] = [
      '#type' => 'fieldset',
      '#title' => '후원자 정보'
    ];
    $form['donor']['name'] = $inline_form_element + [
      '#type' => 'textfield',
      '#title' => '성명',
      '#placeholder' => '홍길동',
      '#default_value' => isset($values) ? $values['name'] : '',
      '#required' => true,
    ];
    $form['donor']['name']['#wrapper_attributes']['class'][] = 'required';
    $form['donor']['mobile'] = $inline_form_element + [
      '#type' => 'tel',
      '#title' => '휴대전화번호',
      '#placeholder' => '010-0000-0000',
      '#default_value' => isset($values) ? $values['mobile'] : '',
      '#required' => true,
    ];
    $form['donor']['mobile']['#wrapper_attributes']['class'][] = 'required';
    $form['donor']['email'] = $inline_form_element + [
      '#type' => 'email',
      '#title' => '이메일',
      '#placeholder' => 'hongildong@gmail.com',
      '#default_value' => isset($values) ? $values['email'] : '',
      '#required' => true,
    ];
    $form['donor']['email']['#wrapper_attributes']['class'][] = 'required';
    $form['donor']['address'] = $inline_form_element + [
      '#type' => 'textfield',
      '#title' => '주소',
      '#placeholder' => '서울 서초구 서초대로45길 20, 변호사교육문화관 3층 1-1호',
      '#default_value' => isset($values) ? $values['address'] : '',
      '#required' => true,
    ];
    $form['donor']['address']['#wrapper_attributes']['class'][] = 'required';

    $form['cms'] = [
      '#type' => 'fieldset',
      '#title' => 'CMS 후원신청정보'
    ];
    $form['cms']['amount'] = $inline_form_element + [
      '#type' => 'radios',
      '#title' => '후원금액',
      '#options' => [ 1 => '1 만원', 2 => '2 만원', 3 => '3 만원', 5 => '5 만원', 10 => '10 만원', 'other' => '직접 입력' ],
      '#attributes' => [ 'name' => 'amount' ],
      '#after_build' => [ [$this, 'add_class_to_radios'] ],
      '#default_value' => '1',
      '#required' => true,
    ];
    $form['cms']['amount']['#wrapper_attributes']['class'][] = 'required';
    $form['cms']['custom_amount'] = $inline_form_element + [
      '#type' => 'number',
      '#size' => '5',
      '#placeholder' => '11만원',
      '#attributes' => [ 'id' => 'custom-amount' ],
      '#states' => [
        'visible' => [
          ':input[name="amount"]' => ['value' => 'other'],
        ],
      ],
      '#default_value' => isset($values) ? $values['custom_amount'] : 1,
    ];
    $form['cms']['withdrawal_date'] = $inline_form_element + [
      '#type' => 'radios',
      '#title' => '출금일',
      '#options' => [ 5 => '5일', 15 => '15일', 25 => '25일' ],
      '#default_value' => '5',
      '#after_build' => [ [$this, 'add_class_to_radios'] ],
      '#required' => true,
    ];
    $form['cms']['withdrawal_date']['#wrapper_attributes']['class'][] = 'required';
    $form['cms']['account_holder'] = $inline_form_element + [
      '#type' => 'textfield',
      '#title' => '예금주',
      '#placeholder' => '예 ) 홍길동',
      '#default_value' => isset($values) ? $values['account_holder'] : '',
      '#required' => true,
    ];
    $form['cms']['account_holder']['#wrapper_attributes']['class'][] = 'required';
    $form['cms']['account_holder_ssn'] = $inline_form_element + [
      '#type' => 'textfield',
      '#title' => '주민등록번호',
      '#placeholder' => '예 ) 200628-1234567',
      '#description' => '<span class="ml-3">* 기부금 영수증 발급을 원하시면 주민등록번호 전체를 적어주세요.</span>',
      '#default_value' => isset($values) ? $values['account_holder_ssn'] : '',
      '#required' => true,
    ];
    $form['cms']['account_holder_ssn']['#wrapper_attributes']['class'][] = 'required';
    $form['cms']['bank'] = $inline_form_element + [
      '#type' => 'textfield',
      '#title' => '은행명',
      '#placeholder' => '예 ) 기업은행',
      '#default_value' => isset($values) ? $values['account_holder_ssn'] : '',
      '#required' => true,
    ];
    $form['cms']['bank']['#wrapper_attributes']['class'][] = 'required';
    $form['cms']['bank_account'] = $inline_form_element + [
      '#type' => 'textfield',
      '#title' => '출금계좌번호',
      '#placeholder' => '예 ) 000-000-000000',
      '#default_value' => isset($values) ? $values['account_holder_ssn'] : '',
      '#required' => true,
    ];
    $form['cms']['bank_account']['#wrapper_attributes']['class'][] = 'required';

    $form['agreements'] = [
      '#type' => 'fieldset',
      '#title' => '개인정보 관련 확인사항 (필수)'
    ];

    $form['agreements']['use'] = [
      '#type' => 'checkbox',
      '#title' => '[개인정보 수집 및 이용 동의]',
      '#description' => '수집 및 이용목적 CMS 출금이체를 통한 요금수납｜수집항목 성명, 전화번호, 휴대폰번호, 금융기관명, 계좌번호｜보유 및 이용기간 수집, 이용 동의일로부터 CMS 출금이체 종료일(해지일) 5년까지｜신청자는 개인정보 수집 및 이용을 거부할 권리가 있으며, 권리행사시 출금이체 신청이 거부될 수 있습니다.',
      '#default_value' => isset($values) ? $values['use'] : '',
      '#required' => true,
    ];
    $form['agreements']['use']['#wrapper_attributes']['class'][] = 'required';
    $form['agreements']['use_third_party'] = [
      '#type' => 'checkbox',
      '#title' => '[개인정보  제3자 제공 동의]',
      '#description' => '개인정보를 제공받는 자 사단법인 금융결제원｜개인정보를 제공받는 자의 개인정보 이용 목적 CMS 출금이체 서비스 제공 및 출금동의 확인, 출금이체 신규등록 및 해지 사실 통지｜제공하는 개인정보의 항목 성명, 금융기관명, 계좌번호, 생년월일, 전화번호(은행 등 금융회사 및 이용기관 보유), 휴대폰번호 개인정보를 제공받는 자의 개인정보 보유 및 이용기간 CMS 출금이체 서비스 제공 및 출금동의 확인 목적을 달성할 때까지｜신청자는 개인정보에 대해 금융결제원에 제공하는 것을 거부할 권리가 있으며, 거부시 출금이체 신청이 거부될 수 있습니다.',
      '#default_value' => isset($values) ? $values['use_third_party'] : '',
      '#required' => true,
    ];
    $form['agreements']['use_third_party']['#wrapper_attributes']['class'][] = 'required';
    $form['agreements']['notification'] = [
      '#type' => 'checkbox',
      '#title' => '[출금이체 동의여부 및 해지사실 통지 안내]',
      '#description' => '은행 등 금융회사 및 금융결제원은 CMS 제도의 안정적 운영을 위하여 고객의(은행 등 금융회사 및 이용기관 보유) 연락처 정보를 활용하여 문자메세지, 유선 등으로 고객의 출금이체 동의여부 및 해지사실을 통지할 수 있습니다.
      상기 금융거래정보의 제공 및 개인정보의 수집 및 이용, 제3자 제공에 동의하며 CMS 출금이체를 신청합니다.',
      '#default_value' => isset($values) ? $values['notification'] : '',
      '#required' => true,
    ];
    $form['agreements']['notification']['#wrapper_attributes']['class'][] = 'required';
    $form['agreements']['signature'] = [
      '#theme' => 'signature',
      '#type' => 'hidden',
      '#title' => 'signature',
      '#data' => [
        'title' => '신청인 서명',
        'description' => '이름을 정자로 작성해주세요.',
        'default_value' => isset($values) ? $values['signature'] : ''
      ],
      '#required' => true,
    ];
    $form['agreements']['signature']['#wrapper_attributes']['class'][] = 'required';

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => '신청하기',
      '#button_type' => 'primary',
    ];

    $form['#attached']['library'][] = 'gd9_donate/szimek_signature_pad';
    $form['#attached']['library'][] = 'gd9_donate/gd9_donate';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // if (strlen($form_state->getValue('name')) == 3) {
    //   ksm($form_state);
    //   $form_state->setErrorByName('name', $this->t('name: @name', ['@name' => $form_state->getValue('name')]));
    // }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $excludes = ['submit' => 0, 'form_build_id' => 0, 'form_token' => 0, 'form_id' => 0, 'op' => 0 ];
    $values2 = array_diff_key($values, $excludes);
    $values2['amount'] = (!empty($values['custom_amount'])) ? $values['custom_amount'] : $values['amount'];
    unset($values2['custom_amount']);
    $values2['created'] = \Drupal::time()->getRequestTime();
    array_walk($values2, function(&$value) {
      $encryption_service = \Drupal::service('encryption');
      $value = $encryption_service->encrypt($value);
    });

    $connection = Database::getConnection('default', 'donation');
    $result = $connection->insert('gd9_donations')->fields($values2)->execute();

    $this->messenger()->addStatus('정상적으로 신청하셨습니다. 감사합니다.');
  }

  function add_class_to_radios(array $element, FormStateInterface $form_state) {
    foreach ($element['#options'] as $key => $value) {
      $element[$key]['#wrapper_attributes']['class'][] = 'form-check-inline';
    }
    return $element;
  }

}

