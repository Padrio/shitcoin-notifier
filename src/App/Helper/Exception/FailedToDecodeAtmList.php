<?php

namespace App\Helper\Exception;

use Exception;

final class FailedToDecodeAtmList extends Exception
{
    public static function fromFailedDecode(string $message): self
    {
        return new self("Failed to decode atm list, error: {$message}");
    }
}