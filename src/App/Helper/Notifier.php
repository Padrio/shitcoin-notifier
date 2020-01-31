<?php

namespace App\Helper;

use App\Config;
use App\Factory\Pushover\Client as ClientFactory;
use LeonardoTeixeira\Pushover\Client;
use LeonardoTeixeira\Pushover\Exceptions\PushoverException;
use LeonardoTeixeira\Pushover\Message;

final class Notifier
{
    /**
     * @var Client|null
     */
    private $client;

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $device;

    /**
     * @var string|null
     */
    private $lastError = null;

    public function __construct(?Client $client = null)
    {
        $config = Config::Get('pushover');
        $this->title = $config['title'];
        $this->device = $config['device'];

        if($client === null) {
            $client = ClientFactory::createFromConfig();
        }

        $this->client = $client;
    }

    /**
     * @return string|null
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function notify($message, ?string $title = null, ?string $device = null): bool
    {
        if($title === null) {
            $title = $this->title;
        }

        $message = new Message($message, $title ?? $this->title);

        try {
            $this->client->push($message, $device ?? $this->device);
        } catch (PushoverException $e) {
            $this->lastError = $e->getMessage();
            return false;
        }

        return true;
    }
}
