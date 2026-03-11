<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteDniFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-dni-folder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   public function handle()
{
    $path = public_path('uploads/foto_dni');

    exec('rm -rf ' . escapeshellarg($path));

    $this->info('Carpeta eliminada');
}

}
