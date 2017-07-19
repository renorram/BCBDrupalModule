<?php
$rows = [];
foreach ($data as $rates) {
  $rows[] = [
    $rates['name'],
    $rates['serie_data']['valor'],
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