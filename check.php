<?php

use App\Helper\Exception\FailedToDecodeAtmList;
use App\Helper\Exception\FailedToFetchAtmListException;
use App\Helper\Fetcher;
use App\Helper\Matcher;
use App\Helper\Notifier;

require_once 'vendor/autoload.php';

\App\Config::$configPath = __DIR__ . '/config.php';
$config = \App\Config::Get('matcher');

$notifier = new Notifier();
$fetcher = new Fetcher();

try {
    $list = $fetcher->getAtmList();
} catch (FailedToFetchAtmListException $e) {
    $notifier->notify('Could not fetch ATM-List, error: '. $e->getMessage());
    exit(1);
} catch (FailedToDecodeAtmList $e) {
    $notifier->notify('Could not decode ATM-List, error: '. $e->getMessage());
    exit(1);
}

$matcher = new Matcher($list);
switch($config['matchBy']) {
    case 'id':
        $location = $config['city']['id'];
        $object = $matcher->matchById($location);
        break;
    case 'city':
        $location = $config['city']['name'];
        $object = $matcher->matchById($location);
        break;
    default:
        echo sprintf('Configured matching-method "%s" is not implemented.', $config['matchBy']);
        exit;
}

if($object['money'] > 0) {
    if(file_exists('notified')) {
        return;
    } else {
        file_put_contents('notified', 'yes');
    }

    $notifier->notify(sprintf('ATM in %s has been refilled, contains: %s€',
        $location,
        number_format($object['money'], 2, ',', '.'),
    ));
} else {
    if (file_exists('notified')) {
        unlink('notified');
    }

    $notifier->notify(sprintf('ATM in %s is empty.', $location));
}

