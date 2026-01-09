<div class="space-y-4">
    <div class="text-sm text-gray-600 dark:text-gray-400">
        <p>SML: <span class="font-semibold">{{ $sml }}</span></p>
    </div>

    {{-- Lista de licencias relacionadas --}}
    <div class="mt-4">
        @if($licencias && $licencias->count() > 0)
            <div class="space-y-2">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Se encontraron {{ $licencias->count() }} licencia(s):
                </p>
                <ul class="space-y-2">
                    @foreach($licencias as $licencia)
                        <li class="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="font-mono text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ $licencia->lic_numlic }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    No se encontraron licencias relacionadas a este SML.
                </p>
            </div>
        @endif
    </div>
</div>