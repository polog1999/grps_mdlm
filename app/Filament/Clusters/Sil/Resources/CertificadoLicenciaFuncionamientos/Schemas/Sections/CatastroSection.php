<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Actions\SectionHeaderActions;

class CatastroSection
{
    public static function make(): Section
    {
        return Section::make('Catastro')
            ->description('Información catastral del predio')
            ->icon('heroicon-o-map-pin')
            ->collapsible()
            ->schema([
                TextInput::make('coduca')->label('Código Catastral')->maxLength(50)->extraAttributes(['inputmode' => 'numeric'])->rule('regex:/^[0-9]+$/')->disabled()->dehydrated(),
                TextInput::make('codpredio')->label('Código Predial')->maxLength(50)->rule('regex:/^[0-9]+$/')->disabled()->dehydrated(),
                TextInput::make('descurb')->label('Urbanización')->maxLength(255)->columnSpanFull()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('via_completa')->label('Vía')->maxLength(255)
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('numvia')->label('Número')->maxLength(20)->numeric()
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('intdpto')->label('Dpto.')->maxLength(20)
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('blockedif')->label('Bloque')->maxLength(20)
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('mz')->label('Manzana')->maxLength(20)
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('lote')->label('Lote')->maxLength(20)
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('zonificacion')->label('Zonificación')->maxLength(100)->disabled()->dehydrated(),
                TextInput::make('area_economica')->label('Área Económica')->numeric()->step(0.01)->suffix('m²')->formatStateUsing(fn($state) => $state ? number_format((float) $state, 2, '.', '') : null)->extraInputAttributes(['onchange' => "if(this.value) this.value = parseFloat(this.value).toFixed(2)"])->disabled()->dehydrated(),
<<<<<<< HEAD
=======
                TextInput::make('fiu_id')->label('')->maxLength(20)->disabled()->dehydrated(),
>>>>>>> feature/licencias
            ])
            ->headerActions(SectionHeaderActions::make('catastro'))
            ->columnSpanFull()
            ->columns(3);
    }


}
