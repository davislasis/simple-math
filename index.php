<?php
require_once __DIR__ . '/classes/App.php';

$output = '';

$app = new App();
$app->init();

if ($app->invalidInput()) {
    $app->addNotification('danger', 'Input defined amount does not match number of lines!');
}

if (!$app->hasNotifications()) {
    // no initial APP errors, we can move on to APP logic and calculations

    for ($x = 0; $x < $app->getCalculations(); $x++) {
        $line = $app->getPreparedLine($x);

        if (!empty($line['valid'])) {
            // Calculation logic will go here.

            // action / calculation switcher

        } else {
            $app->addNotification('warning', 'Invalid line data: #' . $x.' ::: '.$app->getLine($x));
        }
    }
}

if ($app->hasNotifications()) {
    foreach ($app->getNotifications() as $notification) {
        echo '['.strtoupper($notification['type']).'] '.$notification['message'].PHP_EOL;
    }
}

echo $output;