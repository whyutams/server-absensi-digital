<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\File;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $logPath = storage_path('logs');
    $files = File::glob($logPath . '/laravel-*.log');
    $threshold = now()->subDays(7)->startOfDay()->timestamp;

    foreach ($files as $file) {
        if (File::lastModified($file) < $threshold) {
            File::delete($file);
        }
    }
})->daily()->name('clean-old-logs');
