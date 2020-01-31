<?php

namespace App\Factory\Pushover;

use App\Config;
use LeonardoTeixeira\Pushover\Client as PushoverClient;

final class Client
{
    public static function createFromConfig(): PushoverClient
    {
        $auth = Config::Get('pushover.auth');
        return new PushoverClient($auth['user'], $auth['token']);
    }
}