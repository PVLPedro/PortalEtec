<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SyncCustomIcons extends Command
{
    protected $signature = 'icons:sync';
    protected $description = 'Copia os ícones SVG personalizados para a pasta da Lucide';

    public function handle(): void
    {
        $source = resource_path('svg');
        $destination = base_path('vendor/mallardduck/blade-lucide-icons/resources/svg');

        if (!File::isDirectory($source) || !File::isDirectory($destination)) {
            return;
        }

        foreach (File::files($source) as $file) {
            if ($file->getExtension() === 'svg') {
                File::copy($file->getPathname(), $destination . '/' . $file->getFilename());
            }
        }

        $this->callSilent('icon:clear');
        $this->info('Ícones personalizados sincronizados com sucesso.');
    }
}
