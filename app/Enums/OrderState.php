<?php
namespace App\Enums;

enum OrderState: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case DELIVERING = 'delivering';
    case DELIVERED = 'delivered';
    case CANCELLED = 'cancelled';
    case REFUNDED = 'refunded';
    case FAILED = 'failed';
    public static function toArray(): array
    {
        return [
            self::DRAFT->value,
            self::PENDING->value,
            self::CONFIRMED->value,
            self::DELIVERING->value,
            self::DELIVERED->value,
            self::CANCELLED->value,
            self::REFUNDED->value,
            self::FAILED->value,
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
