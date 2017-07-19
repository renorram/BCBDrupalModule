<?php
/**
 * Created by PhpStorm.
 * User: renorram
 * Date: 17/07/17
 * Time: 11:26
 */
module_load_include('inc', 'bcb_finances', 'includes/bcb_finances_data');
/**
 * Get all taxes, optional $conditions arguments to filter
 *
 * @param array $conditions
 *
 * @return mixed
 */
function _bcb_finances_get_taxes($conditions = []) {
  $select = db_select('bcb_finances_tax', 'b')
    ->fields('b');
  if (!empty($conditions)) {
    foreach ($conditions as $field => $value) {
      $select->condition($field, $value);
    }
  }

  return $select->execute()->fetchAll();
}

/**
 * Insert new tax on database
 *
 * @param $data
 *
 * @return bool|int
 */
function _bcb_finances_tax_insert($data) {
  if (_bcb_finances_check_rate_serie_exists('rate_serie_code', $data->buy_serie_code) && _bcb_finances_check_rate_serie_exists('rate_serie_code', $data->sell_serie_code)) {
    return drupal_write_record('bcb_finances_tax', $data);
  }
  watchdog('bcb_finances', 'Não foi possível salvar, código de Série @code inválido.', ['@code' => $data->buy_serie_code]);
  return FALSE;
}

/**
 * Delete a tax of the database
 *
 * @param $id
 *
 * @return bool
 */
function _bcb_finances_delete_tax($id) {
  return db_delete('bcb_finances_tax')->condition('id', $id)->execute() > 0;
}

/**
 * Callback function for delete a tax on database
 *
 * @param $id
 */
function bcb_finances_tax_delete_action($id) {
  if (_bcb_finances_delete_tax($id)) {
    drupal_set_message('Registro excluido com sucesso!');
  }
  else {
    drupal_set_message('Erro ao tentar excluir registro!', 'error');
  }
  drupal_goto('admin/bcb_finances/rates');
}

/**
 * Get the html of the taxes table for display on frontend
 *
 * @return string
 */
function bcb_finances_get_taxes_table() {
  $data = _bcb_finances_get_taxes_cached_data();
  return theme('bcb_finances_taxes_list', ['data' => $data]);
}

/**
 * function to return the array with the form elements
 *
 * @return mixed
 */
function bcb_finances_admin_tax_form() {

  $form = [];

  $values = _bcb_finances_get_rates_series_options_array('rates_administered_free');

  $form['tax_name'] = [
    '#type' => 'textfield',
    '#title' => 'Nome da Taxa',
    '#required' => TRUE,
  ];

  $form['buy_serie_code'] = [
    '#type' => 'select',
    '#title' => 'Taxa de Compra',
    '#options' => $values,
    '#description' => 'Escolha a taxa de compra.',
    '#required' => TRUE,
  ];

  $form['sell_serie_code'] = [
    '#type' => 'select',
    '#title' => 'Taxa de Venda',
    '#options' => $values,
    '#description' => 'Escolha a taxa de venda.',
    '#required' => TRUE,
  ];


  // Set a submit handler manually because the default submit handler
  // gets overridden by the system_settings_form() submit handler.
  $form['#submit'][] = 'bcb_finances_admin_tax_form_submit';

  return system_settings_form($form);
}

/**
 * Handles the submit from admin form
 *
 * @param $form
 * @param $form_state
 */
function bcb_finances_admin_tax_form_submit($form, &$form_state) {
  $values = $form_state['values'];
  if ($values['buy_serie_code'] != $values['sell_serie_code']) {
    $data = new stdClass();
    $data->tax_name = $values['tax_name'];
    $data->buy_serie_code = $values['buy_serie_code'];
    $data->sell_serie_code = $values['sell_serie_code'];
    if (_bcb_finances_tax_insert($data) == SAVED_NEW) {
      cache_clear_all('bcb_finances_taxes', 'cache');
      drupal_set_message('Registro salvo com sucesso!.');
    }
    else {
      drupal_set_message('Erro ao tentar salvar.', 'error');
    }
  }
  else {
    drupal_set_message('Taxa de venda e compra iguais!', 'error');
  }
}


