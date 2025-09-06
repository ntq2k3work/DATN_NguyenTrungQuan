<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\Schemas\DiscountForm;
use App\Filament\Resources\DiscountResource\Tables\DiscountsTable;
use App\Models\Discount;
use App\Enums\NavigationGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::PRODUCT_MANAGEMENT;

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return DiscountForm::make($schema);
    }

    public static function table(Table $table): Table
    {
        return DiscountsTable::make($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}
