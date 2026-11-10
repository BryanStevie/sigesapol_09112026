<?php namespace App\Services;

namespace App\Services;

class ServerResourceMonitor
{
    public function getCpuUsage()
    {
        if (function_exists('sys_getloadavg')) {
            $load = sys_getloadavg();
            $cpuCores = $this->getCpuCores();
            return $cpuCores > 0 ? ($load[0] / $cpuCores) * 100 : 0;
        }

        return $this->getWindowsCpuUsage();
    }

    private function getWindowsCpuUsage()
    {
        $output = shell_exec('wmic cpu get loadpercentage');
        $lines = explode("\n", trim($output));
        return isset($lines[1]) ? (float)$lines[1] : 0;
    }

    public function getMemoryUsage()
    {
        $memoryInfo = shell_exec('wmic OS get FreePhysicalMemory,TotalVisibleMemorySize');
        $lines = explode("\n", trim($memoryInfo));

        if (count($lines) >= 2) {
            $memory = preg_split('/\s+/', $lines[1]);
            $freeMemory = (float)$memory[0]; // Memoria libre
            $totalMemory = (float)$memory[1]; // Memoria total
            return $totalMemory > 0 ? (($totalMemory - $freeMemory) / $totalMemory) * 100 : 0;
        }
        
        return 0; // En caso de fallo
    }

    private function getCpuCores()
    {
        // Devuelve la cantidad de núcleos de CPU
        return shell_exec('nproc') ?: 1; // Devuelve 1 si no se puede determinar
    }
}
