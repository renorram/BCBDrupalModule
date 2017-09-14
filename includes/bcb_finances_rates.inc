<?php
/**
 * Created by PhpStorm.
 * User: renorram
 * Date: 19/07/17
 * Time: 11:15
 */
/** Includes base data file */
module_load_include('inc', 'bcb_finances', 'includes/bcb_finances_data');
/**
 * Get all rates, optional $conditions arguments to filter
 *
 * @param array $conditions
 *
 * @return mixed
 */
function _bcb_finances_get_simple_rates($conditions = []) {
  $select = db_select('bcb_finances_simple_rates', 'b')
    ->fields('b');
  if (!empty($conditions)) {
    foreach ($conditions as $field => $value) {
      $select->condition($field, $value);
    }
  }

  return $select->execute()->fetchAll();
}

/**
 * Insert new simple rate on database
 *
 * @param $data
 *
 * @return bool|int
 */
function _bcb_finances_simple_rate_insert($data) {
  if (_bcb_finances_check_rate_serie_exists('rate_serie_code', $data->serie_code)) {
    $insert = db_insert('bcb_finances_simple_rates')
      ->fields((array) $data)
      ->execute();
    return $insert != NULL;
  }
  watchdog('bcb_finances', 'Não foi possível salvar, código de Série @code inválido.', ['@code' => $data->serie_code]);
  return FALSE;
}

/**
 * Delete a rate of the database
 *
 * @param $id
 *
 * @return bool
 */
function _bcb_finances_delete_simple_rate($id) {
  return db_delete('bcb_finances_simple_rates')
      ->condition('id', $id)
      ->execute() > 0;
}

/**
 * Callback function for delete a rate on database
 *
 * @param $id
 */
function bcb_finances_simple_rate_delete_action($id) {
  if (_bcb_finances_delete_simple_rate($id)) {
    drupal_set_message('Registro excluido com sucesso!');
  }
  else {
    drupal_set_message('Erro ao tentar excluir registro!', 'error');
  }
  drupal_goto('admin/bcb_finances/rates');
}

/**
 * Get simple rate prefix and suffix
 *
 * @param $id
 *
 * @return array|bool
 */
function bcb_finances_simple_rate_get_prefix_suffix($id) {
  $rateResult = _bcb_finances_get_simple_rates(['id' => $id]);
  // rate id not found result
  if (empty($rateResult)) {
    return FALSE;
  }
  $rate = current($rateResult);
  return ['prefix' => $rate->prefix, 'suffix' => $rate->suffix];
}

/**
 * Get the html of the taxes table for display on frontend
 *
 * @return string
 */
function bcb_finances_get_rates_table() {
  $data = _bcb_finances_get_rates_cached_data();
  return theme('bcb_finances_rates_list', ['data' => $data]);
}

/**
 * function to return the array with the form elements
 *
 * @return mixed
 */
function bcb_finances_admin_simple_rates_form() {

  $form = [];

  $financial_capital_interest_rates = _bcb_finances_get_rates_series_options_array('financial_capital_interest_rates');
  $economic_activity_prices_consumer = _bcb_finances_get_rates_series_options_array('economic_activity_prices_consumer');
  $economic_activity_prices_general = _bcb_finances_get_rates_series_options_array('economic_activity_prices_general');
  $values = $financial_capital_interest_rates + $economic_activity_prices_consumer + $economic_activity_prices_general;

  $form['rate_name'] = [
    '#type' => 'textfield',
    '#title' => 'Nome do Indicador',
    '#required' => TRUE,
  ];

  $form['serie_code'] = [
    '#type' => 'select',
    '#title' => 'Indicador',
    '#options' => $values,
    '#description' => 'Escolha o indicador a ser usado.',
    '#required' => TRUE,
  ];

  $form['prefix'] = [
    '#type' => 'textfield',
    '#title' => 'Prefixo',
  ];

  $form['suffix'] = [
    '#type' => 'textfield',
    '#title' => 'Sufixo',
  ];


  // Set a submit handler manually because the default submit handler
  // gets overridden by the system_settings_form() submit handler.
  $form['#submit'][] = 'bcb_finances_admin_simple_rates_form_submit';

  return system_settings_form($form);
}

/**
 * Handles the submit from admin form
 *
 * @param $form
 * @param $form_state
 */
function bcb_finances_admin_simple_rates_form_submit($form, &$form_state) {
  $values = $form_state['values'];

  $data = new stdClass();
  $data->rate_name = $values['rate_name'];
  $data->serie_code = $values['serie_code'];
  $data->prefix = $values['prefix'];
  $data->suffix = $values['suffix'];
  if (_bcb_finances_simple_rate_insert($data)) {
    cache_clear_all('bcb_finances_rates', 'cache');
    drupal_set_message('Registro salvo com sucesso!.');
  }
  else {
    drupal_set_message('Erro ao tentar salvar.', 'error');
  }

}