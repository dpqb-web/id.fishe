<?php
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
  'ARGS' => __DIR__ .'/db.sqlite',
  'USER' => null,
  'PASS' => null
];

$DIR = [
  'STORAGE' => __DIR__ .'/storage/'
];
