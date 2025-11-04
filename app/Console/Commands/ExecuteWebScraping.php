<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ExecuteWebScraping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:execute-web-scraping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ejecuta script para realizar Web Scraping';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pythonPath = env('PYTHON_PATH');
        $scriptPath = base_path('scripts/coolmod_scraping.py');

        $process = new Process([$pythonPath, $scriptPath]);
        $process->setTimeout(null);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Error al ejecutar el script: ' . $process->getErrorOutput());
            return 1;
        }
        
        $this->info('Script ejecutado correctamente');
        return 0;
    }
}
