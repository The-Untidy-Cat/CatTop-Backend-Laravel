<?php
namespace App\Enums;

enum VariantType: string
{
    case PROCESSOR = 'CPU';
    case RAM = 'RAM';
    case GPU = 'Graphics Card';
    case STORAGE = 'Storage';
    case SCREEN = 'Display';
    case PORTS = 'Ports';
    case KEYBOARD = 'Keyboard';
    case TOUCHPAD = 'Touchpad';
    case WEBCAM = 'Webcam';
    case BATTERY = 'Battery';
    case WEIGHT = 'Weight';
    case OPERATING_SYSTEM = 'Operating System';
    case WARRANTY = 'Warranty Period';
}
