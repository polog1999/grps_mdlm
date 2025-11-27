<?php

namespace App\Filament\Clusters\Sil\Resources\CertificadoLicenciaFuncionamientos\Schemas\Sections;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Log;
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
                TextInput::make('coduca')->label('Código Catastral')->maxLength(50)->extraAttributes(['inputmode' => 'numeric'])->rule('regex:/^[0-9]+$/')->disabled(fn ($get) => $get('_section_catastro_saved'))->dehydrated(),
                TextInput::make('codpredio')->label('Código Predial')->maxLength(50)->rule('regex:/^[0-9]+$/')->disabled(fn ($get) => $get('_section_catastro_saved'))->dehydrated(),
                TextInput::make('descurb')->label('Urbanización')->maxLength(255)->columnSpanFull()
                    ->live(onBlur: true) 
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved'))
                    ->dehydrated(),
                
                TextInput::make('via_completa')->label('Vía')->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved'))
                    ->dehydrated(),
                
                TextInput::make('numvia')->label('Número')->maxLength(20)->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved'))
                    ->dehydrated(),
                
                TextInput::make('intdpto')->label('Dpto.')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved'))
                    ->dehydrated(),
                
                TextInput::make('blockedif')->label('Bloque')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved'))
                    ->dehydrated(),
                
                TextInput::make('mz')->label('Manzana')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved'))
                    ->dehydrated(),
                
                TextInput::make('lote')->label('Lote')->maxLength(20)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($get, $set) => self::actualizarDireccion($get, $set))
                    ->disabled(fn ($get) => $get('_section_catastro_saved'))
                    ->dehydrated(),
                
                TextInput::make('zonificacion')->label('Zonificación')->maxLength(100)->disabled(fn ($get) => $get('_section_catastro_saved'))->dehydrated(),
                TextInput::make('area_economica')->label('Área Económica')->numeric()->step(0.01)->suffix('m²')->formatStateUsing(fn ($state) => $state ? number_format((float)$state, 2, '.', '') : null)->extraInputAttributes(['onchange' => "if(this.value) this.value = parseFloat(this.value).toFixed(2)"])->disabled(fn ($get) => $get('_section_catastro_saved'))->dehydrated(),
            ])
            ->headerActions(SectionHeaderActions::make('catastro'))
            ->columnSpanFull()
            ->columns(3);
    }

    /**
     * Esta función se ejecuta SOLO cuando el usuario escribe manualmente
     */
    private static function actualizarDireccion(callable $get, callable $set): void
    {
        // Log::info se ve en storage/logs/laravel.log incluso en producción
        Log::info('Iniciando actualización de dirección...');

        $componentes = [
            'via_completa' => 'AV',
            'descurb' => 'URB',
            'numvia' => 'NRO',
            'intdpto' => 'DPTO',
            'blockedif' => 'BLQ',
            'mz' => 'MZ',
            'lote' => 'LT',
        ];

        $parts = [];
        foreach ($componentes as $campo => $prefijo) {
            // Usamos $get para obtener el estado actual del formulario
            $valor = trim($get($campo) ?? '');
            
            if (!empty($valor)) {
                $parts[] = $prefijo . ' ' . strtoupper($valor);
            }
        }

        $direccion = implode(' ', $parts);
        
        Log::info('Dirección calculada: ' . $direccion);
        
        $set('direccion', $direccion);
    }
}
