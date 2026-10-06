<?php
require_once __DIR__ . '/classes/App.php';

$output = '';
$notifications = [];

$app = new App();
$app->init();

if ($app->invalidInput()) {
    $notifications[] = [
        'type' => 'danger',
        'message' => 'Input defined amount does not match number of lines or any line formatting is wrong!',
    ];
}

if (empty($notifications)) {
    // no initial APP errors, we can move on to APP logic and calculations

    for ($x = 0; $x < $app->getCalculations(); $x++) {
        $line = $app->getPreparedLine($x);

        if (!empty($line['a']) && !empty($line['b']) && !empty($line['action'])) {
            // Calculation logic will go here.
        }
    }
}

if (!empty($notifications)) {
    foreach ($notifications as $notification) {
        echo '['.strtoupper($notification['type']).'] '.$notification['message'].PHP_EOL;
    }
}

echo $output;