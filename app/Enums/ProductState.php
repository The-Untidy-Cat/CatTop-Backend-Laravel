<?php
namespace App\Enums;

enum ProductState: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case OUT_OF_STOCK = 'out_of_stock';
    public static function toArray(): array
    {
        return [
            self::DRAFT->value,
            self::PUBLISHED->value,
            self::ARCHIVED->value,
            self::OUT_OF_STOCK->value,
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
