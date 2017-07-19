<?php
/**
 * Created by PhpStorm.
 * User: renorram
 * Date: 19/07/17
 * Time: 11:17
 */

/**
 * Get a rate serie name by its code
 *
 * @param $rate_code
 *
 * @return bool
 */
function _bcb_finances_get_rate_serie_name($rate_code) {
  if (_bcb_finances_check_rate_serie_exists('rate_serie_code', $rate_code)) {
    return db_select('bcb_finances_rate_serie', 'b')
      ->fields('b', ['rate_serie_name'])
      ->condition('rate_serie_code', $rate_code)->execute()->fetchColumn();
  }
  return FALSE;
}

/**
 * Checks if a serie exists
 *
 * @param $field
 * @param $value
 *
 * @return bool
 */
function _bcb_finances_check_rate_serie_exists($field, $value) {
  $select = db_select('bcb_finances_rate_serie', 'b')
    ->fields('b', ['id'])
    ->condition($field, $value)
    ->countQuery();

  $result = $select->execute();

  return $result->fetchField() != 0;
}

/**
 * Get rates series for array to use in form select
 *
 * @param string $rate_serie_type
 *
 * @return array
 */
function _bcb_finances_get_rates_series_options_array($rate_serie_type) {
  $values = [];

  $data = db_select('bcb_finances_rate_serie', 'b')
    ->fields('b')
    ->condition('rate_serie_type', _bcb_finances_get_rate_serie_type_id($rate_serie_type))
    ->orderBy('rate_serie_name')
    ->execute();

  foreach ($data as $taxe) {
    $values[$taxe->rate_serie_code] = $taxe->rate_serie_name;
  }

  return $values;
}

/**
 * Get the rate serie type by short name
 *
 * @param $short_name
 *
 * @return mixed
 */
function _bcb_finances_get_rate_serie_type_id($short_name) {
  return db_select('bcb_finances_rate_type', 'b')
    ->fields('b', ['id'])
    ->condition('rate_type_short_name', $short_name)->execute()->fetchColumn();
}