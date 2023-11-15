<?php
namespace App\Enums;

enum PaymentState: string
{
    case UNPAID = 'unpaid';
    case PARTIALLY_PAID = 'partially_paid';
    case PAID = 'paid';

    public static function toArray()
    {
        return [
            self::UNPAID->value,
            self::PARTIALLY_PAID->value,
            self::PAID->value,
        ];
    }
    public function label(): string
    {
        return static::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            self::UNPAID => __("messages.payment.state.unpaid"),
            self::PARTIALLY_PAID => __("message.payment.state.partially_paid"),
            self::PAID => __("messages.payment.state.paid"),
        };
    }

}
