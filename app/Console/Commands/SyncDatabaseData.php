<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncDatabaseData extends Command
{
    protected $signature = 'sync:database-data';
    protected $description = 'Sincroniza los datos de la base de datos cada 60 segundos';

    public function handle()
    {
        // Tu lógica de sincronización aquí
        $this->info('Datos sincronizados ucsc: ' . now());
        
        return Command::SUCCESS;
    }
}