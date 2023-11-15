<?php
namespace App\Enums;

enum VariantType: string
{
    case PROCESSOR = 'cpu';
    case RAM = 'ram';
    case GPU = 'gpu';
    case STORAGE = 'storage';
    case SCREEN = 'display';
    case PORTS = 'ports';
    case KEYBOARD = 'keyboard';
    case TOUCHPAD = 'touchpad';
    case WEBCAM = 'webcam';
    case BATTERY = 'battery';
    case WEIGHT = 'weight';
    case OS = 'os';
    case WARRANTY = 'warranty';

    public function label(): string
    {
        return static::getLabel($this);
    }
    public static function getLabel(self $id): string
    {
        return match ($id) {
            self::PROCESSOR => __('messages.specifications.cpu'),
            self::RAM => __('messages.specifications.ram'),
            self::GPU => __('messages.specifications.gpu'),
            self::STORAGE => __('messages.specifications.storage'),
            self::SCREEN => __('messages.specifications.screen'),
            self::PORTS => __('messages.specifications.ports'),
            self::KEYBOARD => __('messages.specifications.keyboard'),
            self::TOUCHPAD => __('messages.specifications.touchpad'),
            self::WEBCAM => __('messages.specifications.webcam'),
            self::BATTERY => __('messages.specifications.battery'),
            self::WEIGHT => __('messages.specifications.battery'),
            self::OS => __('messages.specifications.os'),
            self::WARRANTY => __('messages.specifications.warranty')
        };
    }
}
