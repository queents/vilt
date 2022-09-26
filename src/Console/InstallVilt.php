<?php

namespace Queents\Vilt\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class InstallVilt extends Command
{

    public string $publish;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vilt:install {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command help you to generate the full file and view for VILT framework';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->publish = base_path('vendor/queents/vilt/src/Publish');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $theme = $this->ask("Please select theme from this list \n [1] Admin UI \n [2] FilamentUI \n [3] Without UI \n");
        if($theme === "1"){
            $this->requireComposerPackages(['queents/ui-module']);
        }
        else if($theme === "2"){
            $this->requireComposerPackages(['3x1io/filamentui-module']);
        }

        /*
         * Step 1 Copy And Publish Assets
         */
        $getMigrationFiles = File::files(database_path('migrations'));
        foreach ($getMigrationFiles as $file){
            if(strpos($file->getFilename(), 'sessions')){
                File::delete($file->getRealPath());
            }
        }

        $this->info('Install JetStream');
        $this->callSilent('jetstream:install', [
            "stack"=>"inertia"
        ]);
        $this->info('Migrate JetStream Tables');
        $this->callSilent('migrate');
        $this->info('Copy tailwind.config.js');
        $this->handelFile('/tailwind.config.js', base_path('/tailwind.config.js'));
        $this->info('Copy vite.config.js');
        $this->handelFile('/vite.config.js', base_path('/vite.config.js'));
        $this->info('Copy postcss.config.js');
        $this->handelFile('/postcss.config.js', base_path('/postcss.config.js'));
        $this->info('Copy package.json');
        $this->handelFile('/package.json', base_path('/package.json'));
        $this->info('Copy HandleInertiaRequests.php');
        $this->handelFile('/app/Http/Middleware/HandleInertiaRequests.php', app_path('/Http/Middleware/HandleInertiaRequests.php'));
        $this->info('Copy User.php');
        $this->handelFile('/app/Models/User.php', app_path('/Models/User.php'));
        $this->info('Copy RouteServiceProvider.php');
        $this->handelFile('/app/Providers/RouteServiceProvider.php', app_path('/Providers/RouteServiceProvider.php'));
        $this->info('Copy modules.php');
        $this->handelFile('/config/modules.php', config_path('/modules.php'));
        $this->info('Copy app.php');
        $this->handelFile('/config/app.php', config_path('/app.php'));
        $this->info('Copy placeholder.webp');
        $this->handelFile('/public/placeholder.webp', public_path('/placeholder.webp'));
        $this->info('Copy css');
        $this->handelFile('/resources/css', resource_path('/css'), 'folder');
        $this->info('Copy js');
        $this->handelFile('/resources/js', resource_path('/js'), 'folder');
        $this->info('Copy views');
        $this->handelFile('/resources/views', resource_path('/views'), 'folder');
        $this->info('Copy stubs');
        $this->handelFile('/stubs/nwidart-stubs', base_path('/stubs/nwidart-stubs'), 'folder');
        if(!$this->checkFile(base_path('Modules'))){
            File::makeDirectory(base_path('Modules'));
        }
        $this->info('Copy modules_statuses.json');
        $this->handelFile('/modules_statuses.json', base_path('/modules_statuses.json'));
        $this->info('Migrate Roles');
        $this->callSilent('migrate');
        $this->info('Clear cache');
        $this->callSilent('optimize:clear');
        $this->info('Generate Admin & Roles');
        $this->callSilent('roles:install');
        $this->info('The Permission Has Been Generated');
        $this->info('Admin Username: admin@admin.com');
        $this->info('Admin Password: QTS@2022');
        $this->info('Please run yarn i & yarn build');
    }

    public function handelFile(string $from, string $to, string $type = 'file'): void
    {
        $checkIfFileEx = $this->checkFile($to);
        if($checkIfFileEx){
            $this->deleteFile($to);
            $this->copyFile($this->publish .$from, $to, $type);
        }
        else {
            $this->copyFile($this->publish .$from, $to, $type);
        }
    }

    public function checkFile(string $path): bool
    {
        return File::exists($path);
    }

    public function copyFile(string $from,string $to, string $type ='file'): bool
    {
        if($type === 'folder'){
            $copy = File::copyDirectory($from , $to);
        }
        else {
            $copy = File::copy($from , $to);
        }

        return $copy;
    }
    public function deleteFile(string $path, string $type ='file'): bool
    {
        if($type === 'folder'){
            $delete = File::deleteDirectory($path);
        }
        else {
            $delete = File::delete($path);
        }

        return $delete;
    }

    /**
     * Run the given commands.
     *
     * @param  array  $commands
     * @return void
     */
    protected function runCommands(array $commands): void
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> '.$e->getMessage().PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    '.$line);
        });
    }

    /**
     * Get the path to the appropriate PHP binary.
     *
     * @return string
     */
    protected function phpBinary(): string
    {
        return (new PhpExecutableFinder())->find(false) ?: 'php';
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param  mixed  $packages
     * @return void
     */
    protected function requireComposerPackages(mixed $packages): void
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = [$this->phpBinary(), $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    /**
     * Install the given Composer Packages as "dev" dependencies.
     *
     * @param  mixed  $packages
     * @return void
     */
    protected function requireComposerDevPackages(mixed $packages): void
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = [$this->phpBinary(), $composer, 'require', '--dev'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require', '--dev'],
            is_array($packages) ? $packages : func_get_args()
        );

        (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }
}
