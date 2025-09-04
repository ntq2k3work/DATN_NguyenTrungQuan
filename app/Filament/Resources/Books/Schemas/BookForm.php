<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Support\Str;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Thông tin cơ bản')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Tên sách')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Nhập tên sách')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('tên-sách-tự-động'),
                            ]),

                        RichEditor::make('description')
                            ->label('Mô tả')
                            ->required()
                            ->columnSpanFull()
                            ->placeholder('Nhập mô tả chi tiết về sách'),
                    ]),

                Section::make('Thông tin chi tiết')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('price')
                                    ->label('Giá bán')
                                    ->required()
                                    ->numeric()
                                    ->prefix('₫')
                                    ->minValue(0)
                                    ->placeholder('0'),

                                TextInput::make('quantity')
                                    ->label('Số lượng')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(1)
                                    ->placeholder('0'),

                                Select::make('status')
                                    ->label('Trạng thái')
                                    ->options([
                                        'active' => 'Hoạt động',
                                        'inactive' => 'Không hoạt động',
                                    ])
                                    ->required()
                                    ->default('active'),
                            ]),

                        Grid::make(3)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Danh mục')
                                    ->options(Category::all()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->placeholder('Chọn danh mục'),

                                Select::make('publisher_id')
                                    ->label('Nhà xuất bản')
                                    ->options(Publisher::all()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->placeholder('Chọn nhà xuất bản'),

                                Select::make('author_id')
                                    ->label('Tác giả')
                                    ->options(Author::all()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->placeholder('Chọn tác giả'),
                            ]),
                    ]),

                Section::make('Hình ảnh')
                    ->schema([
                        FileUpload::make('image_url')
                            ->label('Hình ảnh sách')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('400')
                            ->imageResizeTargetHeight('300')
                            ->directory('books')
                            ->columnSpanFull()
                            ->placeholder('Tải lên hình ảnh sách'),
                    ])
                    ->collapsible(),
            ]);
    }
}
