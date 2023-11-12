<?php
namespace App\Enums;

enum ProductState: int
{
    case DRAFT = 0;
    case PUBLISHED = 1;
    case ARCHIVED = 2;
    case OUT_OF_STOCK = 3;
    public static function toArray(): array
    {
        return [
            self::DRAFT,
            self::PUBLISHED,
            self::ARCHIVED,
            self::OUT_OF_STOCK,
        ];
    }
    public function label(): string
    {
        return static::getLabel($this);
    }
    public static function getLabel(self $id): string
    {
        return match ($id) {
            self::DRAFT => __('messages.product.state.draft'),
            self::PUBLISHED => __('messages.product.state.published'),
            self::ARCHIVED => __('messages.product.state.archived'),
            self::OUT_OF_STOCK => __('messages.product.state.out_of_stock'),
        };
    }
}
