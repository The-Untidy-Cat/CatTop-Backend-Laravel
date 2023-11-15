<?php
namespace App\Enums;

enum CPUProperties: string
{
    case NAME = 'cpu.name';
    case CORES = 'cpu.cores';
    case THREADS = 'cpu.threads';
    case BASE_CLOCK = 'cpu.base_clock';
    case TURBO_CLOCK = 'cpu.turbo_clock';
    case CACHE = 'cpu.cache';

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
