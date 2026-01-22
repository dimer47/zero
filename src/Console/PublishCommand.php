<?php

namespace Dimer47\Zero\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'zero:publish')]
class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zero:publish
                {--force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Zero Docker runtimes to your project for customization';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->publishRuntimes();

        $this->output->writeln('');
        $this->components->info('Zero Docker runtimes published successfully.');
        $this->output->writeln('');
        $this->components->info('You can now customize the Dockerfiles in the docker/ directory.');
        $this->components->warn('Remember to update your docker-compose.yml to point to ./docker/{PHP_VERSION} instead of ./vendor/dimer47/zero/runtimes/{PHP_VERSION}');
        $this->output->writeln('');

        return 0;
    }

    /**
     * Publish the runtime files.
     *
     * @return void
     */
    protected function publishRuntimes()
    {
        $source = __DIR__ . '/../../runtimes';
        $destination = $this->laravel->basePath('docker');

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $versions = ['8.2', '8.3', '8.4'];

        foreach ($versions as $version) {
            $sourceDir = $source . '/' . $version;
            $destDir = $destination . '/' . $version;

            if (!is_dir($sourceDir)) {
                continue;
            }

            if (is_dir($destDir) && !$this->option('force')) {
                if (!$this->components->confirm("The directory docker/{$version} already exists. Overwrite?", false)) {
                    $this->components->info("Skipped PHP {$version}");
                    continue;
                }
            }

            $this->copyDirectory($sourceDir, $destDir);
            $this->components->info("Published PHP {$version} runtime to docker/{$version}");
        }
    }

    /**
     * Copy a directory recursively.
     *
     * @param string $source
     * @param string $destination
     * @return void
     */
    protected function copyDirectory(string $source, string $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $files = scandir($source);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $sourcePath = $source . '/' . $file;
            $destPath = $destination . '/' . $file;

            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
        }
    }
}
