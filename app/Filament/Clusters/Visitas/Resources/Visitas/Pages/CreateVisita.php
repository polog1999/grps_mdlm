<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Pages;

use App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource;
use App\Models\PersonaUno;
use App\Models\Proveedor;
use App\Models\Visita;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateVisita extends CreateRecord
{
    protected static string $resource = VisitaResource::class;
    protected ?string $heading = 'Registrar Visita';

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {

            // 1. IMPORTANTE: Obtenemos TODOS los datos del formulario, 
            // incluso los que tienen dehydrated(false) como 'lista_trabajadores'
            $rawState = $this->form->getRawState();


            // GENERAR EL ID DE GRUPO AQUÍ
            $grupoUid = Str::uuid();
            // 2. Preparar datos maestros de la Visita
            $visitaData = [
                'grupo_uid' => $grupoUid, // <--- ASIGNAR EL MISMO ID A TODOS
                'sede_id'                => auth()->user()->sede_id ?? 1,
                'area_id'                => $data['area_id'],
                'area'                   => $data['area'],
                'oficina_id'             => $data['oficina_id'] ?? null,
                'oficina'                => $data['oficina'] ?? null,
                'trabajador_id_autoriza' => $data['trabajador_id_autoriza'],
                'trabajador_autoriza'    => $data['trabajador_autoriza'],
                'trabajador_id_cita'     => $data['trabajador_id_cita'],
                'trabajador_cita'        => $data['trabajador_cita'],
                'motivo'                 => $data['motivo'],
                'detalle_motivo'         => mb_strtoupper($data['detalle_motivo']),
                'sistema'                => $data['sistema'],
                'fecha_ingreso'          => now(),
                'user_id_ingreso'        => auth()->id(),
                'es_manual'              => false,
                'es_empresa'             => $data['es_empresa'], // Guardamos el flag
            ];

            // 3. Lógica si es EMPRESA
            if ($data['es_empresa'] == 1) {
                $proveedor = Proveedor::updateOrCreate(
                    ['ruc' => $data['ruc']],
                    ['nombre' => mb_strtoupper($data['nombres']), 'direccion' => $data['direccion'] ?? null]
                );
                $visitaData['proveedor_id'] = $proveedor->id_proveedor;
                $visitaData['proveedor'] = $proveedor->nombre;
                $visitaData['ruc'] = $proveedor->ruc;
            }

            // 4. Crear la visita
            // $visita = Visita::create($visitaData);

            // 5. PROCESAR ASISTENTES A LA TABLA PIVOTE
            if ($data['es_empresa'] == 0) {
                // CASO PERSONAS: Viene de 'lista_visitantes'
                $visitantes = $data['lista_visitantes'] ?? [];
                foreach ($visitantes as $v) {
                    $visita = Visita::create($visitaData);
                    if (empty($v['tipo_documento']) && !empty($v['tipo_documento_id'])) {
                        $v['tipo_documento'] = \App\Models\TipoDocumento::where('id_tipo_documento', $v['tipo_documento_id'])->value('abreviatura');
                    }
                    $persona = PersonaUno::updateOrCreate(
                        [
                            'numero_documento' => $v['numero_documento'],
                            'tipo_documento_id' => $v['tipo_documento_id']
                        ],
                        [
                            'tipo_documento' => $v['tipo_documento'],
                            'nombres'           => mb_strtoupper($v['nombres']),
                            'apellido_paterno'  => mb_strtoupper($v['apellido_paterno']),
                            'apellido_materno'  => mb_strtoupper($v['apellido_materno']),
                            'foto_url'          => $v['foto_url'] ?? null,
                            'user_id_modi'      => auth()->id(),
                            'user_id_creo'      => PersonaUno::where('numero_documento', $v['numero_documento'])->value('user_id_creo') ?? auth()->id(),
                        ]
                    );
                    $visita->personas()->attach($persona->id);
                }
            } else {
                // CASO EMPRESA: Usamos rawState para obtener 'lista_trabajadores'
                $trabajadores = $rawState['lista_trabajadores'] ?? [];
                foreach ($trabajadores as $t) {
                    $visita = Visita::create($visitaData);
                    if (empty($t['tipo_documento']) && !empty($t['tipo_documento_id'])) {
                        $t['tipo_documento'] = \App\Models\TipoDocumento::where('id_tipo_documento', $t['tipo_documento_id'])->value('abreviatura');
                    }
                    $persona = PersonaUno::updateOrCreate(

                        [
                            'numero_documento' => $t['numero_documento'],
                            'tipo_documento_id' => $t['tipo_documento_id']
                        ],
                        [
                            'tipo_documento' => $t['tipo_documento'],
                            'nombres'           => mb_strtoupper($t['nombres']),
                            'apellido_paterno'  => mb_strtoupper($t['apellido_paterno']),
                            'apellido_materno'  => mb_strtoupper($t['apellido_materno']),
                            'user_id_modi'      => auth()->id(),
                            'user_id_creo'      => PersonaUno::where('numero_documento', $t['numero_documento'])->value('user_id_creo') ?? auth()->id(),
                        ]
                    );
                    // Guardamos en la tabla pivote con su cargo
                    $visita->personas()->attach($persona->id, ['cargo' => mb_strtoupper($t['cargo'] ?? null)]);
                }
            }

            return $visita;
        });
    }

    protected function getRedirectUrl(): string
    {
        // Redirigimos a la página de resumen pasando el ID del registro recién creado
        return $this->getResource()::getUrl('resumen', ['uuid' => $this->record->grupo_uid]);
    }

    public function getTitle(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return 'Registro de Visita';
    }
}
