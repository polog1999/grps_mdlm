<!DOCTYPE html>
<html lang="es" class="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitas</title>
    
   
    @vite(['resources/css/app.css'])
    @filamentStyles
    @livewireStyles
</head>
<body class="fi-body antialiased bg-gray-50">

    <div class="fi-layout flex min-h-screen w-full flex-col">
        <main class="fi-main mx-auto w-full max-w-full px-4 py-8 md:px-6 lg:px-12">
            
            <div class="fi-section w-full rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 p-6">
                <header class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-950">Visitas</h1>
                </header>

                {{-- El componente Livewire --}}
                @livewire('public-visitas-table')
            </div>

        </main>
    </div>

    @livewireScripts
    @filamentScripts
    @vite('resources/js/app.tsx')
</body>
</html>