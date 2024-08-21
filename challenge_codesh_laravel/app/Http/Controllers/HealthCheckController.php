<?php

namespace App\Http\Controllers;

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

        // Tempo online e uso de memória
        $uptime = shell_exec('uptime');
        $memoryUsage = memory_get_usage();

        return response()->json([
            'database_connection' => $databaseConnection,
            'last_cron_run' => $lastCronRun,
            'uptime' => trim($uptime),
            'memory_usage' => $memoryUsage,
        ]);
    }
}
