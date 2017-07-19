<?php
$rows = [];
foreach ($data as $tax) {
  $rows[] = [
    $tax['name'],
    'R$ ' . number_format($tax['buy_data']['valor'], 4,',','.'),
    'R$ ' . number_format($tax['sell_data']['valor'],4, ',','.'),
  ];
}

$table_vars = [
  'header' => [
    ['data' => ''],
    ['data' => 'Compra'],
    ['data' => 'Venda'],
  ],
  'rows' => $rows,
  'attributes' => [],
  'caption' => 'Tabela de Taxas',
  'colgroups' => [],
  'sticky' => TRUE,
  'empty' => t('No results found.'),
];
print theme_table($table_vars);