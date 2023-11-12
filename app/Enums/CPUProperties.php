<?php
namespace App\Enums;

enum CPUProperties: string
{
    case NAME = 'CPU Name';
    case CORES = 'Cores';
    case THREADS = 'Threads';
    case BASE_CLOCK = 'Base Clock Speed';
    case TURBO_CLOCK = 'Max Turbo Speed';
    case CACHE = 'Cache';
}
