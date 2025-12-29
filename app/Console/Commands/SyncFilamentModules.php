<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Module;
use App\Models\Permission; // <--- IMPORTANTE: Tu modelo de permisos
use Filament\Facades\Filament;
use Illuminate\Support\Str;

class SyncFilamentModules extends Command
{
    protected $signature = 'modules:sync';

    protected $description = 'Sincroniza los Resources de Filament y vincula sus permisos automáticamente';

    public function handle()
    {
        $this->info('⏳ Iniciando sincronización de módulos y permisos...');

        // ======================================================
        // 1. EJECUTAR SEEDER DE PERMISOS (PRIMERO)
        // ======================================================
        if ($this->confirm('¿Deseas ejecutar el seeder de permisos primero?', true)) {
            $this->newLine();
            $this->info('📦 Ejecutando RolesAndPermissionsSeeder...');
            $this->call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
            $this->newLine();
        }

        // 2. Obtener recursos del panel 'admin'
        $resources = Filament::getPanel('admin')->getResources();

        $countModules = 0;
        $activeClasses = []; // Lista para rastrear qué clases siguen vivas

        foreach ($resources as $resourceClass) {
            // Validar que la clase PHP exista
            if (!class_exists($resourceClass)) {
                continue;
            }

            // Guardamos la clase en la lista de "activos"
            $activeClasses[] = $resourceClass;

            // 2. OBTENER EL NOMBRE AMIGABLE
            try {
                $name = $resourceClass::getNavigationLabel()
                    ?? $resourceClass::getPluralModelLabel()
                    ?? Str::headline(class_basename($resourceClass));
            } catch (\Exception $e) {
                $name = Str::headline(class_basename($resourceClass));
            }
            $name = Str::ucfirst($name);

            // 3. DETECTAR EL CLUSTER
            $clusterName = null;
            if (Str::contains($resourceClass, 'Clusters\\')) {
                $parts = explode('\\', $resourceClass);
                $clusterIndex = array_search('Clusters', $parts);
                if ($clusterIndex !== false && isset($parts[$clusterIndex + 1])) {
                    $clusterName = $parts[$clusterIndex + 1];
                }
            }

            // 4. GUARDAR EL MÓDULO EN BD
            $module = Module::updateOrCreate(
                ['filament_class' => $resourceClass],
                [
                    'name' => $name,
                    'cluster' => $clusterName,
                ]
            );

            // ======================================================
            // 5. NUEVA LÓGICA: VINCULAR PERMISOS AUTOMÁTICAMENTE
            // ======================================================
            try {
                // Verificamos si el Resource tiene un Modelo asociado (casi todos tienen)
                if (method_exists($resourceClass, 'getModel')) {
                    $modelClass = $resourceClass::getModel();

                    if ($modelClass) {
                        // Obtenemos el "key" del modelo en snake_case
                        // Ej: "App\Models\CertificadoInspeccion" -> "certificado_inspeccion"
                        // Ej: "App\Models\User" -> "user"
                        $modelKey = Str::snake(class_basename($modelClass));

                        // Buscamos permisos con el nuevo formato (view::user, create::user, etc.)
                        // O el formato antiguo (view_user, create_user, etc.)
                        $permissions = Permission::where(function ($query) use ($modelKey) {
                            $query->where('name', 'LIKE', "%::{$modelKey}")
                                ->orWhere('name', 'LIKE', "%::{$modelKey}s")
                                ->orWhere('name', 'LIKE', "%_{$modelKey}");
                        })->get();

                        if ($permissions->count() > 0) {
                            foreach ($permissions as $permission) {
                                $permission->module_id = $module->id;
                                $permission->save();

                                $this->line("   🔗 <fg=cyan>{$permission->name}</> → <fg=green>{$name}</>");
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Si falla la vinculación, no detenemos el proceso, solo avisamos
                $this->warn("   ⚠️ No se pudieron vincular permisos para: $name");
            }

            $this->info("✅ Sincronizado: " . ($clusterName ? "[$clusterName] " : "") . $name);
            $countModules++;
        }

        // ======================================================
        // 6. LIMPIEZA DE BASURA (Garbage Collection)
        // ======================================================
        // Borramos de la BD los módulos que ya no existen en el código (archivos borrados o renombrados)
        $deleted = Module::whereNotIn('filament_class', $activeClasses)->delete();

        if ($deleted > 0) {
            $this->warn("🗑️ Se eliminaron $deleted módulos obsoletos de la base de datos.");
        }

        $this->newLine();
        $this->info("🎉 ¡Proceso terminado! $countModules módulos activos sincronizados.");
    }
}