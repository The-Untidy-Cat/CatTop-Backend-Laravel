<?php
namespace App\Enums;

enum ScreenProperties: string
{
    case SIZE = 'size';
    case RESOLUTION = 'resolution';
    case TECHNOLOGY = 'technology';
    case REFRESH_RATE = 'refresh_rate';
    case TOUCH = 'touch';

}
