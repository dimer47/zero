<?php

namespace Dimer47\Zero\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'zero:install')]
class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zero:install
                {--php=8.3 : The PHP version that should be used (8.2, 8.3, or 8.4)}
                {--devcontainer : Create a .devcontainer configuration directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Zero Docker configuration for Laravel Zero';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        $phpVersion = $this->option('php');

        if (!in_array($phpVersion, ['8.2', '8.3', '8.4'])) {
            $this->components->error("Invalid PHP version [{$phpVersion}]. Supported versions: 8.2, 8.3, 8.4");
            return 1;
        }

        $this->installDockerCompose($phpVersion);

        if ($this->option('devcontainer')) {
            $this->installDevContainer();
        }

        $this->output->writeln('');
        $this->components->info('Zero scaffolding installed successfully.');
        $this->output->writeln('');
        $this->components->info('Build the Docker image:');
        $this->output->writeln('<fg=gray>➜</> <options=bold>./vendor/bin/zero build</>');
        $this->output->writeln('');
        $this->components->info('Then run your commands:');
        $this->output->writeln('<fg=gray>➜</> <options=bold>./vendor/bin/zero list</>');
        $this->output->writeln('');

        return 0;
    }

    /**
     * Install the docker-compose.yml file.
     *
     * @param string $phpVersion
     * @return void
     */
    protected function installDockerCompose(string $phpVersion)
    {
        $composePath = $this->getComposePath();
        $relativePath = basename($composePath);

        if (file_exists($composePath)) {
            if (!$this->components->confirm("The file {$relativePath} already exists. Do you want to replace it?", false)) {
                return;
            }
        }

        $content = file_get_contents(__DIR__ . '/../../stubs/compose.stub');

        file_put_contents($composePath, $content);

        // Update .env with the selected PHP version
        $this->updateEnvFile($phpVersion);

        $this->components->info("Created {$relativePath}");
    }

    /**
     * Update or create .env file with PHP version.
     *
     * @param string $phpVersion
     * @return void
     */
    protected function updateEnvFile(string $phpVersion)
    {
        $envPath = $this->laravel->basePath('.env');

        if (file_exists($envPath)) {
            $env = file_get_contents($envPath);

            if (preg_match('/^PHP_VERSION=.*/m', $env)) {
                $env = preg_replace('/^PHP_VERSION=.*/m', "PHP_VERSION={$phpVersion}", $env);
            } else {
                $env .= "\nPHP_VERSION={$phpVersion}\n";
            }

            file_put_contents($envPath, $env);
        } else {
            file_put_contents($envPath, "PHP_VERSION={$phpVersion}\n");
        }

        $this->components->info("Set PHP_VERSION={$phpVersion} in .env");
    }

    /**
     * Install the devcontainer.json configuration file.
     *
     * @return void
     */
    protected function installDevContainer()
    {
        $devcontainerPath = $this->laravel->basePath('.devcontainer');

        if (!is_dir($devcontainerPath)) {
            mkdir($devcontainerPath, 0755, true);
        }

        file_put_contents(
            $devcontainerPath . '/devcontainer.json',
            file_get_contents(__DIR__ . '/../../stubs/devcontainer.stub')
        );

        $this->components->info('Created .devcontainer/devcontainer.json');
    }

    /**
     * Get the path to the docker-compose file.
     *
     * @return string
     */
    protected function getComposePath(): string
    {
        $possiblePaths = [
            'compose.yaml',
            'compose.yml',
            'docker-compose.yaml',
            'docker-compose.yml',
        ];

        foreach ($possiblePaths as $path) {
            $fullPath = $this->laravel->basePath($path);
            if (file_exists($fullPath)) {
                return $fullPath;
            }
        }

        return $this->laravel->basePath('docker-compose.yml');
    }
}
