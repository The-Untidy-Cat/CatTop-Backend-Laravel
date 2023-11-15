<?php
namespace App\Enums;

enum ScreenProperties: string
{
    case SIZE = 'screen.size';
    case RESOLUTION = 'screen.resolution';
    case TECHNOLOGY = 'screen.technology';
    case REFRESH_RATE = 'screeen.refresh_rate';
    case TOUCH = 'screen.touch';

}
