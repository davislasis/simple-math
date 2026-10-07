<?php
// initial loader
require_once __DIR__ . '/functions/Helpers.php';

require_once __DIR__ . '/classes/App.php';
require_once __DIR__ . '/classes/Calculation.php';

$output = '';

$app = new App();
$app->init();

// business / app logic
if ($app->invalidInput()) {
    $app->addNotification('danger', 'Input defined amount does not match number of lines!');
} else {
    // no initial app errors, we can move on to app calculations

    $calculation = new Calculation();

    for ($x = 0; $x < $app->getCalculations(); $x++) {
        $line = $app->getPreparedLine($x);

        if (!empty($line['valid'])) {
            // Calculation logic
            $result = $calculation->calculate($line['a'], $line['b'], $line['action']);
            $output .= $calculation->format($line['a'], $line['b'], $line['action'], $result).PHP_EOL;
        } else {
            $app->addNotification('warning', 'Invalid line data #'.$x.'. Unsupported operator: ' . $line['action'].'. Line: '.$app->getLine($x));
        }
    }
}

// output / view renderer
if ($app->hasNotifications()) {
    foreach ($app->getNotifications() as $notification) {
        echo '['.strtoupper($notification['type']).'] '.$notification['message'].PHP_EOL;
    }

    echo '<hr />'.PHP_EOL;
}

if (PHP_SAPI === 'cli') {
    echo $output;
} else {
    echo '<pre>' . htmlspecialchars($output) . '</pre>';
}
