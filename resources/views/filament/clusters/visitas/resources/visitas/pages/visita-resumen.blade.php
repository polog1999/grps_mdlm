<x-filament-panels::page>
    <div class="space-y-6">
        @if ($record->first()->es_empresa)
            <x-filament::section>
                <x-slot name="heading">Empresa</x-slot>
                <p>Razón Social: <strong>{{ $record->first()->proveedor }}</strong></p>
                <p>RUC: <strong>{{ $record->first()->ruc }}</strong></p>
            </x-filament::section>
            <br>
        @endif
        <x-filament::section>

            <x-slot name="heading">Personas que Ingresaron</x-slot>

            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Nombre</th>
                        @if ($record->first()->es_empresa)
                            <th class="py-2">Cargo</th>
                        @endif

                        <th class="py-2">Tipo Documento</th>
                        <th class="py-2">Documento</th>
                        <th class="py-2">Hora Ingreso</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Aquí iteras tu relación de acompañantes o el grupo --}}
                    @foreach ($record as $visitante)
                        <tr class="border-b">
                            <td class="py-2" style="text-align:center;">{{ $visitante->nombres_completos }}</td>
                            @if ($record->first()->es_empresa)
                                <td class="py-2" style="text-align:center;">{{ $visitante->cargo }}</td>
                            @endif
                            <td class="py-2" style="text-align:center;">{{ $visitante->tipo_documento }}</td>
                            <td class="py-2" style="text-align:center;">{{ $visitante->numero_documento }}</td>
                            <td class="py-2" style="text-align:center;">{{ $visitante->hora_ingreso }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-filament::section>
        <br>
        <x-filament::section>
            <x-slot name="heading">Detalle de la Visita</x-slot>
            <p>Fecha: <strong>{{ Carbon\Carbon::parse($record->first()->fecha)->format('d/m/Y') }}</strong></p>
            <p>Sede: <strong>{{ $record->first()->sede->nombre }}</strong></p>
            <p>Área Destino: <strong>{{ $record->first()->area }}</strong></p>
            @if ($record->first()?->oficina)
                <p>Oficina: <strong>{{ $record->first()?->oficina }}</strong></p>
            @endif
            <p>Autorizado por: <strong>{{ $record->first()->autorizado_por }}</strong></p>
            <p>Cita con: <strong>{{ $record->first()->trabajador_cita }}</strong></p>
            <p>Motivo de la visita: <strong>{{ $record->first()->motivo }} -
                    {{ $record->first()->detalle_motivo }}</strong></p>
            <p>Sistema: <strong>{{ $record->first()->sistema }}</strong></p>

        </x-filament::section>



        <br>
        {{-- BOTÓN PARA VOLVER --}}
        <div class="flex justify-center">
            <x-filament::button color="gray" tag="a"
                href="{{ App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource::getUrl('index') }}">
                Volver a la Tabla Principal
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
