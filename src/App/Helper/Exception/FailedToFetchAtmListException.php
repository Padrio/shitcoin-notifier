<?php

namespace App\Helper\Exception;

use Exception;

final class FailedToFetchAtmListException extends Exception
{
    public static function fromFailedRequest(int $code): self
    {
        return new self("Failed to fetch atm list, status code: {$code}");
    }

    public static function fromGuzzleException(string $message): self
    {
        return new self("Failed to fetch atm list, guzzle error: {$message}");
    }
}