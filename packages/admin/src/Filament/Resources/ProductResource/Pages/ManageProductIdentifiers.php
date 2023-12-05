<?php

namespace Lunar\Admin\Filament\Resources\ProductResource\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Lunar\Admin\Filament\Resources\ProductResource;
use Lunar\Admin\Support\Pages\BaseEditRecord;
use Lunar\Models\ProductVariant;

class ManageProductIdentifiers extends BaseEditRecord
{
    protected static string $resource = ProductResource::class;

    public ?string $sku = null;

    public ?string $gtin = null;

    public ?string $mpn = null;

    public ?string $ean = null;

    public function getTitle(): string|Htmlable
    {
        return __('lunarpanel::product.pages.identifiers.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('lunarpanel::product.pages.identifiers.label');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return $parameters['record']->variants()->count() == 1;
    }

    public function getBreadcrumb(): string
    {
        return __('lunarpanel::product.pages.identifiers.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('lunar::product-identifiers');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $variant = $this->getVariant();

        $this->sku = $variant->sku;
        $this->gtin = $variant->gtin;
        $this->mpn = $variant->mpn;
        $this->ean = $variant->ean;

    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $variant = $this->getVariant();

        $variant->update($data);

        return $record;
    }

    protected function getVariant(): ProductVariant
    {
        return $this->getRecord()->variants()->first();
    }

    public function form(Form $form): Form
    {
        $variant = $this->getVariant();

        return $form->schema([
            Section::make()->schema([
                TextInput::make('sku')
                    ->label(
                        __('lunarpanel::product.pages.identifiers.form.sku.label')
                    )
                    ->live()->unique(
                        table: fn () => $variant->getTable(),
                        ignorable: $variant,
                        ignoreRecord: true,
                    ),
                TextInput::make('gtin')->label(
                    __('lunarpanel::product.pages.identifiers.form.gtin.label')
                ),
                TextInput::make('mpn')->label(
                    __('lunarpanel::product.pages.identifiers.form.mpn.label')
                ),
                TextInput::make('ean')->label(
                    __('lunarpanel::product.pages.identifiers.form.ean.label')
                ),
            ])->columns(1),
        ])->statePath('');
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
