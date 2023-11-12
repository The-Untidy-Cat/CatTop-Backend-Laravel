<?php
namespace App\Enums;

enum ScreenProperties: string
{
    case SIZE = 'Size';
    case RESOLUTION = 'Resolution';
    case TECHNOLOGY = 'Screen Technology';
    case REFRESH_RATE = 'Refresh Rate';
    case TOUCH = 'Touchscreen';
}
