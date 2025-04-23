<?php

namespace MagicBox\LaraQuickKit\Console\Commands;

use Illuminate\Console\Command;

class AddMiddlewareCommand extends Command
{
    protected $signature = 'laraquick:add-middleware';
    protected $description = 'Menambahkan EnsureUserHasPermission ke Kernel.php secara otomatis';

    public function handle()
    {
        $kernelPath = app_path('Http/Kernel.php');

        if (!file_exists($kernelPath)) {
            $this->error('Kernel.php tidak ditemukan! Pastikan Anda menjalankan perintah ini dalam proyek Laravel yang valid.');
            return;
        }

        $kernelContent = file_get_contents($kernelPath);
        $middlewareClass = '\\MagicBox\\LaraQuickKit\\Http\\Middleware\\EnsureUserHasPermission::class';

        if (strpos($kernelContent, $middlewareClass) !== false) {
            $this->info('Middleware sudah ada di Kernel.php. Tidak ada perubahan yang dilakukan.');
            return;
        }

        $search = 'protected $middlewarePriority = [';
        $replace = "protected \$middlewarePriority = [\n $middlewareClass,";

        if (strpos($kernelContent, $search) !== false) {
            $kernelContent = str_replace($search, $replace, $kernelContent);
            file_put_contents($kernelPath, $kernelContent);
            $this->info('Middleware berhasil ditambahkan ke Kernel.php!');
        } else {
            $this->error('Gagal menambahkan middleware. Pastikan struktur Kernel.php sesuai dengan standar Laravel.');
        }
    }
}
