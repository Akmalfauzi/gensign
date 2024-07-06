<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class ExportBladeToHtml extends Command
{
    protected $signature = 'export:blade {--localhost : Whether to use localhost URL}';
    protected $description = 'Export Blade templates to static HTML';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->warn('Exporting Blade templates to static HTML...');

        $viewsDir = resource_path('views');
        $outputDir = public_path('design');

        if (!File::exists($outputDir)) {
            $this->info('Creating output directory...');
            File::makeDirectory($outputDir, 0755, true);
        } else {
            $this->info('Cleaning output directory...');
            File::cleanDirectory($outputDir);
        }

        $this->info('--------------------------------------');

        $this->warn('Compiling assets...');

        exec('npm run build 2>&1', $output, $exitCode);

        foreach ($output as $line) {
            $this->info($line);
        }

        if ($exitCode !== 0) {
            $this->error('Ada masalah dalam menjalankan npm run format.');
            return 1;
        }

        $this->info('--------------------------------------');

        $this->warn('Exporting Blade templates to static HTML...');

        $files = $this->getBladeFiles($viewsDir);

        $htmlIndex = null;

        foreach ($files as $file) {
            $viewName = $this->getViewName($file, $viewsDir);

            $html = View::make($viewName)->render();

            $relativePath = str_replace('.', '/', $viewName);
            $outputPath = $outputDir . '/' . $relativePath . '.html';

            $outputFileDir = dirname($outputPath);
            if (!File::exists($outputFileDir)) {
                File::makeDirectory($outputFileDir, 0755, true);
            }

            $minifiedHtml = preg_replace([
                '/href=("|\')http:\/\/\[::1\]:5173\/resources\/css\/app\.css("|\')/',
                '/src=("|\')http:\/\/\[::1\]:5173\/resources\/js\/app\.js("|\')/'
            ], [
                'href="./css/app.css"',
                'src="./js/app2.js"'
            ], $html);

            File::put($outputPath, $minifiedHtml);
            $this->info("Page '{$viewName}' has been exported to {$outputPath}");

            if ($viewName === 'index') {
                if ($this->option('localhost')) {
                    $htmlIndex = url('/design') . '/' . $relativePath . '.html';
                } else {
                    $htmlIndex = public_path('design') . '/' . $relativePath . '.html';
                }
            }

            File::copyDirectory(resource_path('assets'), public_path('design/assets'));
        }

        $this->warn('All Blade templates have been exported to static HTML.');

        $this->info('--------------------------------------');

        $this->warn('Minifying HTML files...');

        exec('npm run format 2>&1', $output, $exitCode);

        foreach ($output as $line) {
            $this->info($line);
        }

        if ($exitCode !== 0) {
            $this->error('Ada masalah dalam menjalankan npm run format.');
            return 1;
        }

        $this->info('--------------------------------------');
        $this->warn('All HTML files have been minified.');

        $this->info('--------------------------------------');
        $this->info('Exporting Blade templates to static HTML completed.');

        $this->info('--------------------------------------');

        if ($htmlIndex) {
            if ($this->option('localhost')) {
                $this->info("HTML index page available at: {$htmlIndex}");
            } else {
                $this->info("HTML index page available at: file://{$htmlIndex}");
            }
        } else {
            $this->error("Index page not found.");
        }
    }

    private function getBladeFiles($directory)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $files = new \RegexIterator($iterator, '/^.+\.blade\.php$/i', \RegexIterator::MATCH);

        $filteredFiles = [];
        foreach ($files as $file) {
            $filePath = $file->getPathname();

            if (strpos($filePath, 'layout') === false) {
                $filteredFiles[] = $file;
            }
        }

        return $filteredFiles;
    }


    private function getViewName($file, $baseDir)
    {
        $filePath = realpath($file->getPathname());
        $baseDir = realpath($baseDir);
        $viewName = str_replace([$baseDir, '.blade.php', DIRECTORY_SEPARATOR], ['', '', '.'], $filePath);

        return ltrim($viewName, '.');
    }
}
