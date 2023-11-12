<?php
namespace App\Enums;

enum OrderState: int
{
    case DRAFT = 0;
    case PENDING = 1;
    case CONFIRMED = 2;
    case DELIVERING = 3;
    case DELIVERED = 4;
    case CANCELLED = 5;
    case REFUNDED = 7;
    case FAILED = 8;
    public static function toArray(): array
    {
        return [
            self::DRAFT,
            self::PENDING,
            self::CONFIRMED,
            self::DELIVERING,
            self::DELIVERED,
            self::CANCELLED,
            self::REFUNDED,
            self::FAILED,
        ];
    }
    public function label(): string
    {
        return static::getLabel($this);
    }
    public static function getLabel(self $id): string
    {
        return match ($id) {
            self::DRAFT => __('messages.order.state.draft'),
            self::PENDING => __('messages.order.state.pending'),
            self::CONFIRMED => __('messages.order.state.confirmed'),
            self::DELIVERING => __('messages.order.state.delivering'),
            self::DELIVERED => __('messages.order.state.delivered'),
            self::CANCELLED => __('messages.order.state.cancelled'),
            self::REFUNDED => __('messages.order.state.refunded'),
            self::FAILED => __('messages.order.state.failed'),
        };
    }
}
