<?php
// $SQL = new PDO($DB['TYPE'] . ':' . implode(';', array_map(fn($k, $v) => "$k=$v", array_keys($DB['OPTS']), $DB['OPTS'])), $DB['USER'], $DB['PASS'], [
//     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
// ]);

// sementara
$SQL = new PDO('sqlite:' . __DIR__ . '/db.sqlite', null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
