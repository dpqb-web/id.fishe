<?php
if ($DB['TYPE'] != 'sqlite') {
    $DB['ARGS'] = implode(';', array_map(fn($k, $v) => "$k=$v", array_keys($DB['OPTS']), $DB['OPTS']));
}

$SQL = new PDO($DB['TYPE'] .':'. $DB['ARGS'], $DB['USER'], $DB['PASS'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

if ($DB['TYPE'] == 'sqlite') {
    $SQL->exec(file_get_contents(__DIR__ . '/new.sql'));
    // $SQL->exec(file_get_contents(__DIR__ . '/demo.sql'));
}
