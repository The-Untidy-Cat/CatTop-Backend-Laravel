<?php
namespace App\Enums;

enum ProductVariantState: string
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
            self::DRAFT => __('messages.product_variant.state.draft'),
            self::PUBLISHED => __('messages.product_variant.state.published'),
            self::ARCHIVED => __('messages.product_variant.state.archived'),
            self::OUT_OF_STOCK => __('messages.product_variant.state.out_of_stock'),
        };
    }

}
