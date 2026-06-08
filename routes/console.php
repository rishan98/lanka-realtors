<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('storage:publish-public {--force : Overwrite files already in public/storage}', function () {
    $source = storage_path('app/public');
    $target = public_path('storage');

    if (! File::isDirectory($source)) {
        $this->warn('No files at storage/app/public.');

        return 0;
    }

    File::ensureDirectoryExists($target);

    $copied = 0;
    $skipped = 0;

    foreach (File::allFiles($source) as $file) {
        $relative = $file->getRelativePathname();
        $destination = $target.DIRECTORY_SEPARATOR.$relative;

        if (File::exists($destination) && ! $this->option('force')) {
            $skipped++;
            continue;
        }

        File::ensureDirectoryExists(dirname($destination));
        File::copy($file->getPathname(), $destination);
        $copied++;
    }

    $this->info("Copied {$copied} file(s) to public/storage.".($skipped ? " Skipped {$skipped} existing." : ''));

    return 0;
})->purpose('Copy uploads to public/storage for shared hosting (no symlink)');

Artisan::command('storage:link {--force : Overwrite existing files}', function () {
    $this->warn('Symlinks are disabled on this server. Copying files to public/storage instead…');

    return $this->call('storage:publish-public', [
        '--force' => $this->option('force'),
    ]);
})->purpose('Publish public storage without symlink (Hostinger-safe)');
