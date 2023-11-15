<?php
namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case BANKING = 'banking';

    public static function toArray()
    {
        return [
            self::CASH->value,
            self::BANKING->value
        ];
    }

    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            self::CASH => __("messages.payment.method.cash"),
            self::BANKING => __("message.payment.method.banking"),
        };
    }

}
