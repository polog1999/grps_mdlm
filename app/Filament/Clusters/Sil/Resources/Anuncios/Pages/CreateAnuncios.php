<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Pages;
use App\Filament\Clusters\Sil\Resources\Anuncios\AnunciosResource;
use App\Filament\Clusters\Sil\Resources\Anuncios\Traits\ValidatesAnuncioRules;
use App\Models\ExpedientesAnuncios;
use App\Models\ReciboPago;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class CreateAnuncios extends CreateRecord
{
    use ValidatesAnuncioRules;

    protected static string $resource = AnunciosResource::class;

    /**
     * Campos del formulario que son auxiliares (no columnas directas de anuncios.anuncios).
     * Se limpiarán de $data antes de crear el registro principal.
     */
    private array $auxiliaryFields = [
        'n_expediente_search',
        'tiene_licencia',
        'n_pago',
        'monto',
        'n_expediente',
        'folios',
        // Snapshots gestionados en expediente, no en anuncios
        'snapshot_solicitante_dni',
        'snapshot_solicitante_nombre_completo',
        'form_domicilio_fiscal',
        'snapshot_solicitante_telefono',
        'snapshot_persona_legal_dni',
        'snapshot_persona_legal_nombre_completo',
        'snapshot_persona_legal_telefono',
        'snapshot_persona_legal_distrito',
        // Snapshots de licencia ya no necesarios en anuncios
        'snapshot_lic_tipo',
        'form_direccion_predio',
        'zonificacion_id',
        'n_resolucion_subgerencial',
        'fecha_resolucion_subgerencial',
        'fecha_expediente',
    ];

    private array $coloresParaSync = [];
    private array $documentosParaSync = [];

    protected function handleRecordCreation(array $data): Model
    {
        // Validaciones de reglas de negocio (trait)
        $this->validarReglasDeNegocio($data);

        $this->coloresParaSync = $data['colores'] ?? [];
        $this->documentosParaSync = $data['documentos'] ?? [];
        unset($data['colores'], $data['documentos']);

        Log::info('Iniciando transacción de Base de Datos...');

        $anuncio = DB::transaction(function () use (&$data) {

            $recibo = ReciboPago::create([
                'n_recibo_pago' => $data['n_pago'] ?? null,
                'monto' => $data['monto'] ?? 0,
            ]);
            $expediente = ExpedientesAnuncios::firstOrCreate(
                ['n_expediente' => $data['n_expediente']],
                [
                    'snapshot_solicitante_nombre_completo' => $data['snapshot_solicitante_nombre_completo'] ?? null,
                    'snapshot_solicitante_dni' => $data['snapshot_solicitante_dni'] ?? null,
                    'snapshot_solicitante_telefono' => $data['snapshot_solicitante_telefono'] ?? null,
                    'snapshot_solicitante_direccion' => $data['form_direccion_predio'] ?? null,
                    'snapshot_legal_nombre' => $data['snapshot_persona_legal_nombre_completo'] ?? null,
                    'snapshot_legal_dni_ruc' => $data['snapshot_persona_legal_dni'] ?? null,
                    'snapshot_legal_telefono' => $data['snapshot_persona_legal_telefono'] ?? null,
                    'snapshot_legal_direccion' => $data['form_domicilio_fiscal'] ?? null,
                    'snapshot_legal_distrito' => $data['snapshot_persona_legal_distrito'] ?? null,
                    'folios' => $data['folios'] ?? 0,
                    'recibo_pago_id' => $recibo->id,
                    'zonificacion_id' => $data['zonificacion_id'] ?? null,
                    'n_resolucion_subgerencial' => $data['n_resolucion_subgerencial'] ?? null,
                    'fecha_resolucion_subgerencial' => $data['fecha_resolucion_subgerencial'] ?? null,
                    'fecha_expediente' => $data['fecha_expediente'] ?? null,
                ]
            );
            if (!$expediente->wasRecentlyCreated) {
                $expediente->update([
                    'recibo_pago_id' => $recibo->id,
                    'zonificacion_id' => $data['zonificacion_id'] ?? $expediente->zonificacion_id,
                    'n_resolucion_subgerencial' => $data['n_resolucion_subgerencial'] ?? $expediente->n_resolucion_subgerencial,
                    'fecha_resolucion_subgerencial' => $data['fecha_resolucion_subgerencial'] ?? $expediente->fecha_resolucion_subgerencial,
                    'fecha_expediente' => $data['fecha_expediente'] ?? $expediente->fecha_expediente,
                ]);
            }

            $data['expediente_id'] = $expediente->id;
            $data['created_by_user_id'] = auth()->id();

            foreach ($this->auxiliaryFields as $field) {
                unset($data[$field]);
            }

            Log::info('Ejecutando INSERT de Anuncios con data final.');
            return static::getModel()::create($data);
        });

        Log::info('--- FIN DE CREACIÓN DE ANUNCIO CON ÉXITO ---');
        return $anuncio;
    }
    /**
     * Después de crear el Anuncio principal, sincronizar relaciones y actualizar secuencia.
     */
    protected function afterCreate(): void
    {
        // Actualizar la secuencia del correlativo de anuncios para asegurar que el siguiente sea correcto
        DB::statement("SELECT setval('anuncios.anuncio_correlativo_seq', (SELECT MAX(CAST(n_anuncio AS INTEGER)) FROM anuncios.anuncios))");

        // Sincronizar colores (BelongsToMany)
        if (!empty($this->coloresParaSync)) {
            $this->record->colores()->sync($this->coloresParaSync);
        }

        // Crear documentos (HasMany)
        if (!empty($this->documentosParaSync)) {
            foreach ($this->documentosParaSync as $documento) {
                $this->record->documentos()->create([
                    'tipo_documento' => $documento['tipo_documento'] ?? null,
                    'n_documento' => $documento['n_documento'] ?? null,
                    'fecha_emision' => $documento['fecha_emision'] ?? null,
                ]);
            }
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


}
