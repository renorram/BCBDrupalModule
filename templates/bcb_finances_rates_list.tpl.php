<?php
$rows = [];
foreach ($data as $idRate => $rate) {
  $additional = bcb_finances_simple_rate_get_prefix_suffix($idRate);
  $valor = $additional['prefix'] . $rate['serie_data']['valor'] . $additional['suffix'];
  $rows[] = [
    $rate['name'],
    $valor,
  ];
}

$table_vars = [
  'header' => [],
  'rows' => $rows,
  'attributes' => ['class' => ['table', 'table-striped', 'table-hover']],
  'caption' => '',
  'colgroups' => [],
  'sticky' => FALSE,
  'empty' => t('No results found.'),
];
print theme_table($table_vars);

$lastUpdate = variable_get('bcb_finances_rates_last_update', time());
$date = (new DateTime())->setTimestamp($lastUpdate)
  ->setTimezone(new DateTimeZone('America/Fortaleza'))
  ->format('d/m/Y H:i');
$l = l('Banco Central do Brasil', 'https://www.bcb.gov.br/pt-br/', [
  'external' => TRUE,
  'attributes' => ['target' => '_blank'],
]);
print "<small>Última atualização ás $date.<br>Fonte: $l</small>";