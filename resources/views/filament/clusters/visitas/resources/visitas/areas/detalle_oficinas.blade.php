<div class="px-6 py-3 bg-gray-50/50 dark:bg-gray-900/50">
    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Oficinas y Anexos:</h4>
    <ul class="space-y-1">
        @foreach($oficinas as $oficina)
            <li class="text-sm text-gray-700 dark:text-gray-300">
                <span class="font-medium text-primary-600">• {{ $oficina->nombre }}</span> 
                — <span class="text-gray-500">Anexo: {{ $oficina->anexo ?? 'N/A' }}</span>
            </li>
        @endforeach
    </ul>
</div>