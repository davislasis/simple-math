<?php
require_once __DIR__ . '/../classes/App.php';
require_once __DIR__ . '/../classes/Calculation.php';

$output = [];

$total = 0;
$pass = 0;
$fail = 0;

$app = new App();
$app->init();

$calculation = new Calculation();

echo PHP_EOL;

foreach ($app->getLines() as $key => $line) {
    $total++;

    $line_parts = $app->getPreparedLine($key);

    if (!empty($line_parts['valid'])) {
        $result = (int) $calculation->calculate($line_parts['a'], $line_parts['b'], $line_parts['action']);

        $expected = match ($line_parts['action']) {
            '+' => ((int) $line_parts['a'] + (int) $line_parts['b']),
            '-' => ((int) $line_parts['a'] - (int) $line_parts['b']),
            '*' => ((int) $line_parts['a'] * (int) $line_parts['b']),
            default => 0,
        };

        if ($result === $expected) {
            $pass++;
            echo '[PASS] '.$line_parts['a'].$line_parts['action'].$line_parts['b'].'='.$result.PHP_EOL;
        } else {
            $fail++;
            echo '[FAIL] '.$line_parts['a'].$line_parts['action'].$line_parts['b'].'='.$result.PHP_EOL;
        }
    } else {
        $fail++;
        echo '[FAIL] '.$line_parts['a'].$line_parts['action'].$line_parts['b'].PHP_EOL;
    }
}

echo PHP_EOL . 'Tests passed: '.$pass.'/'.$total . PHP_EOL;
