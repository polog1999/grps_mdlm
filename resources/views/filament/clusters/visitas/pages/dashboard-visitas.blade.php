<x-filament-panels::page>
   <style>
@media print {
    /* 1. Ocultar absolutamente toda la interfaz de navegación (Escritorio y Móvil) */
    aside.fi-sidebar,                    /* Menú lateral izquierdo (Desktop) */
    div[class*="fi-sidebar"],            /* Cualquier contenedor de sidebar */
    .fi-sidebar-close-overlay,           /* Overlay de sidebar móvil */
    .fi-topbar,                    /* Barra superior */
    .fi-breadcrumbs,                     /* Migas de pan */
    .fi-header-actions,                  /* Botones del header */
    .fi-footer,                          /* Footer */
    ::-webkit-scrollbar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
        opacity: 0 !important;
        visibility: hidden !important;
    }

    /* 2. Resetear el Layout de Filament */
    /* Por defecto, Filament usa CSS Grid/Flex con márgenes izquierdos para respetar el menú. */
    /* Al imprimir, obligamos a que el contenido ocupe el 100% real sin "paddings" de sidebar. */
    html, body {
        background-color: white !important;
        width: 100% !important;
        height: auto !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
    }

    /* Forzar al contenedor principal a ignorar el espacio del sidebar oculto */
    .fi-layout,
    .fi-main, 
    .fi-content, 
    main {
        padding: 0 !important;
        margin: 0 !important;
        margin-left: 0 !important; /* Vital para quitar el espacio vacío del menú izquierdo */
        width: 100% !important;
        max-width: 100% !important;
        display: block !important;
        overflow: visible !important;
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
        font-size: 0.85rem;
    }
}
</style>

    {{-- Filament renderiza automáticamente getHeaderWidgets y getFooterWidgets aquí --}}
</x-filament-panels::page>