<?php
namespace App\Enums;

enum CPUProperties: string
{
    case NAME = 'name';
    case CORES = 'cores';
    case THREADS = 'threads';
    case BASE_CLOCK = 'base_clock';
    case TURBO_CLOCK = 'turbo_clock';
    case CACHE = 'cache';

    public static function toArray(): array
    {
        return [
            self::NAME->value,
            self::CORES->value,
            self::THREADS->value,
            self::BASE_CLOCK->value,
            self::CACHE->value,
            self::TURBO_CLOCK->value,
        ];
    }
}
