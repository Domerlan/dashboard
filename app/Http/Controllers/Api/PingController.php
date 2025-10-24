<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DashboardPing;
use Symfony\Component\Process\Process;

class PingController extends Controller
{
    public function index()
    {
        $targets = DashboardPing::where('active', true)
            ->orderBy('sort_order')
            ->take(5)
            ->get(['label','host']);

        $results = [];
        foreach ($targets as $t) {
            $ms = $this->pingHost($t->host);
            $results[] = [
                'label' => $t->label,
                'host' => $t->host,
                'ms' => $ms,
            ];
        }

        return response()->json($results);
    }

    private function pingHost(string $host): ?int
    {
        $family = PHP_OS_FAMILY;
        if ($family === 'Windows') {
            $cmd = ['ping', '-n', '1', '-w', '1000', $host];
        } else {
            $cmd = ['ping', '-c', '1', '-W', '1', $host];
        }
        try {
            $process = new Process($cmd, timeout: 3);
            $process->run();
            $out = $process->getOutput() . $process->getErrorOutput();
            if (preg_match('/time[=<]([0-9\.,]+)\s*ms/i', $out, $m)) {
                $val = str_replace(',', '.', $m[1]);
                return (int) round((float)$val);
            }
            if (preg_match('/Average\s*=\s*([0-9]+)ms/i', $out, $m)) {
                return (int) $m[1];
            }
        } catch (\Throwable $e) {
            // ignore
        }
        return null; // недоступно
    }
}

