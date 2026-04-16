<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas\Pages;

use App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource;
use Filament\Resources\Pages\Page;
use App\Models\Visita;
use App\Models\VisitaHistorico;
use Illuminate\Support\Collection;

class VisitaResumen extends Page
{
    protected static string $resource = VisitaResource::class; // Esta SÍ es static

protected string $view = 'filament.clusters.visitas.resources.visitas.pages.visita-resumen'; // Esta NO es static

    public Collection $record;

    public function mount($uuid): void
    {
        // Cargamos la visita con sus relaciones (acompañantes, trabajadores, etc.)
        $this->record = VisitaHistorico::where('grupo_uid',$uuid)->get();
    }
}