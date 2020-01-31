<?php

namespace App\Helper;

final class Matcher
{
    /**
     * @var array
     */
    private $list;

    public function __construct(array $list)
    {
        $this->list = $list;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }

    public function matchById(int $id): array
    {
        foreach($this->getList() as $entry) {
            if($entry['id'] === $id) {
                return $entry;
            }
        }

        return [];
    }

    public function matchByCity(string $city): array
    {
        foreach($this->getList() as $entry) {
            if($entry['city'] === $city) {
                return $entry;
            }
        }

        return [];
    }
}