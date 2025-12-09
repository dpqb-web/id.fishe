<?php
$DIR = [
  'DATABASE' => __DIR__ . '/db/',
  'MODULE' => __DIR__ . '/module/',
  'STORAGE' => __DIR__ . '/storage/',
  'TEMPLATE' => __DIR__ . '/app/template/'
];

// $DB = [
//   'TYPE' => 'mysql',
//   'OPTS' => [
//     'host' => 'localhost',
//     'port' => '3306',
//     'dbname' => 'fishe'
//   ],
//   'USER' => 'tes1',
//   'PASS' => 'tes123'
// ];

// demo
$DB = [
  'TYPE' => 'sqlite',
  'ARGS' => $DIR['DATABASE'] .'db.sqlite',
  'USER' => null,
  'PASS' => null
];
