<?php
/**
 * Created by PhpStorm.
 * User: renorram
 * Date: 17/07/17
 * Time: 16:18
 */
/** @var array $data */
/** @var array $link_attributes default link attributes */
$link_attributes = [
  'attributes' => [
    'class' => [
      'btn',
      'btn-primary',
      'button',
    ],
  ],
];


$taxes_table_vars = [
  'header' => [
    ['data' => 'ID', 'field' => 'id', 'sort' => 'ASC'],
    ['data' => 'Nome', 'field' => 'tax_name', 'sort' => 'ASC'],
    ['data' => 'Compra', 'field' => 'buy_serie_code', 'sort' => 'ASC'],
    ['data' => 'Venda', 'field' => 'sell_serie_code', 'sort' => 'ASC'],
    ['data' => '']
  ],
  'rows' => $data['taxes'],
  'attributes' => [],
  'caption' => 'Tabela de Taxas',
  'colgroups' => [],
  'sticky' => TRUE,
  'empty' => t('No results found.'),
];

$link_new_tax = l('Nova Taxa', 'admin/bcb_finances/rates/tax/new', $link_attributes);
$tax_content = $link_new_tax . theme_table($taxes_table_vars);
print bcb_finances_get_fieldset($tax_content, 'Taxas de Câmbio');

$rates_table_vars = [
  'header' => [
    ['data' => 'ID', 'field' => 'id', 'sort' => 'ASC'],
    ['data' => 'Nome', 'field' => 'rate_name', 'sort' => 'ASC'],
    ['data' => 'Indicador', 'field' => 'serie_code', 'sort' => 'ASC'],
    ['data' => 'Prefixo',  'field' => 'prefix', 'sort' => 'ASC'],
    ['data' => 'Sufixo',  'field' => 'suffix', 'sort' => 'ASC'],
    ['data' => '']
  ],
  'rows' => $data['rates'],
  'attributes' => [],
  'caption' => 'Tabela de Indicadores',
  'colgroups' => [],
  'sticky' => TRUE,
  'empty' => t('No results found.'),
];

$link_new_rate = l('Novo Indicador', 'admin/bcb_finances/rates/simple-rate/new', $link_attributes);
$rate_content = $link_new_rate . theme_table($rates_table_vars);
print bcb_finances_get_fieldset($rate_content, 'Outros Indicadores');

