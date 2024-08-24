<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HealthCheckController extends Controller
{
    public function index()
    {
        // Verificar conexão com a base de dados
        try {
            DB::connection()->getPdo();
            $databaseConnection = 'OK';
        } catch (\Exception $e) {
            $databaseConnection = 'Fail';
        }

        // Última vez que o cron foi executado
        $lastCronRun = Cache::get('last_cron_run', 'Nunca executado');

        if($lastCronRun != 'Nunca executado'){
            // Converte a data ISO para um objeto Carbon
            $date = Carbon::parse($lastCronRun);

            // Formata a data no padrão brasileiro
            $lastCronRun = $date->format('H:i:s d/m/Y');
        }

        // Tempo online e uso de memória
        $uptime = shell_exec('uptime');
        $memoryUsage = memory_get_usage();

        return response()->json([
            'database_connection' => $databaseConnection,
            'last_cron_run' => $lastCronRun,
            'uptime' => trim($uptime),
            'memory_usage' => $this->formatMemoryUsage($memoryUsage)
        ]);
    }

    public function formatMemoryUsage($sizeInBytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unitIndex = 0;

        while ($sizeInBytes >= 1024 && $unitIndex < count($units) - 1) {
            $sizeInBytes /= 1024;
            $unitIndex++;
        }

        return number_format($sizeInBytes, 2) . ' ' . $units[$unitIndex];
    }
}
