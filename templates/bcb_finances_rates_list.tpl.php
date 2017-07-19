<?php
$rows = [];
foreach ($data as $rates) {
  $rows[] = [
    $rates['name'],
    $rates['serie_data']['valor']
  ];
}

$table_vars = [
  'header' => [],
  'rows' => $rows,
  'attributes' => [],
  'caption' => 'Tabela de Indicadores',
  'colgroups' => [],
  'sticky' => TRUE,
  'empty' => t('No results found.'),
];
print theme_table($table_vars);