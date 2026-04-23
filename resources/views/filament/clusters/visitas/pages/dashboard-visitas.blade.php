<x-filament-panels::page>
    <style>
@media print {
    /* 1. Ocultar interfaz de navegación y acciones */
    .fi-sidebar, 
    .fi-topbar,
    .fi-header-actions,
    .fi-footer,
    .fi-breadcrumbs,
    ::-webkit-scrollbar {
        display: none !important;
    }

    /* 2. Ajuste de contenedores principales y eliminación de scrolls */
    body {
        background-color: white !important;
    }

    .fi-main, 
    .fi-content, 
    .fi-section, 
    .fi-ta-content {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        overflow: visible !important;
        display: block !important;
    }

    /* 3. Estilo de Widgets y Secciones */
    .fi-wi-widget {
        width: 100% !important;
        margin-bottom: 2rem !important;
        break-inside: avoid; /* Evita que los gráficos se corten entre páginas */
    }

    .fi-section {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }

    /* 4. Optimización de Tablas para ancho A4 */
    table {
        table-layout: auto !important;
        width: 100% !important;
    }

    .fi-ta-cell {
        white-space: normal !important;
        word-wrap: break-word !important;
        font-size: 0.85rem; /* Opcional: mejora el encaje de textos largos */
    }
}

</style>

    {{-- Filament renderiza automáticamente getHeaderWidgets y getFooterWidgets aquí --}}
</x-filament-panels::page>