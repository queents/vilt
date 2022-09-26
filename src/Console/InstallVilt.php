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
        $getMigrationFiles = File::files(database_path('migrations'));
        foreach ($getMigrationFiles as $file){
            if(strpos($file->getFilename(), 'sessions')){
                File::delete($file->getRealPath());
            }
        }
        $this->info('Copy User.php');
        $this->handelFile('/app/Models/User.php', app_path('/Models/User.php'));
        $this->info('Copy modules_statuses.json');
        $this->handelFile('/modules_statuses.json', base_path('/modules_statuses.json'));
        $this->callSilent('migrate');
        $this->callSilent('roles:install');
        $theme = $this->ask("Please select theme from this list \n [1] Admin UI \n [2] FilamentUI \n [3] Without UI \n");
        if($theme === "1"){
            $this->requireComposerPackages(['queents/ui-module']);
        }
        else if($theme === "2"){
            $this->requireComposerPackages(['3x1io/filamentui-module']);
        }
        $plugins = $this->ask("Please select plugins from this list EX: 1,2,3,4 \n
[1] Settings - VILT framework Settings module GUI to save key and value on database and cache it \n
[2] Translations - Database Base Translations Keys with Google Translations API Integration \n
[3] Notifications - VILT Notifications Module with multi channels and vendors like FCM / Pusher \n
[4] Payment - Payment Services Integrations & Management Module for VILT Framework \n
[5] Log - Log Viewer for VILT Stack using Laravel Log Reader \n
[6] Backup - Backup module for VILT Stack build with spatie laravel-backup \n
[7] Locations - Database seeds for Locations Module for VILT stack \n
[8] Browser - VILT browser modules to browser the file inside your app \n
[9] Artisan - VILT artisan modules to run artisan commands using GUI \n
        ");

        $plugins = explode(",", $plugins);
        foreach($plugins as $plugin){
            switch ($plugin){
                case "1":
                    $this->requireComposerPackages(['queents/settings-module']);
                    $this->activeModule('Settings');
                    $this->runArtisanCommand(['migrate']);
                    break;
                case "2":
                    $this->requireComposerPackages(['queents/translations-module']);
                    $this->activeModule('Translations');
                    $this->runArtisanCommand(['migrate']);
                    $this->runArtisanCommand(['translations:install']);
                    break;
                case "3":
                    $this->requireComposerPackages(['queents/notifications-module']);
                    $this->activeModule('Notifications');
                    $this->runArtisanCommand(['migrate']);
                    $this->runArtisanCommand(['notifications:install']);
                    break;
                case "4":
                    $this->requireComposerPackages(['queents/payment-module']);
                    $this->activeModule('Payment');
                    $this->runArtisanCommand(['migrate']);
                    $this->runArtisanCommand(['payment:install']);
                    break;
                case "5":
                    $this->requireComposerPackages(['3x1io/log-module']);
                    $this->activeModule('Log');
                    $this->generatePermission('logs');
                    break;
                case "6":
                    $this->requireComposerPackages(['3x1io/backup-module']);
                    $this->activeModule('Backup');
                    $this->generatePermission('backups');
                    break;
                case "7":
                    $this->requireComposerPackages(['queents/locations-module']);
                    $this->activeModule('Locations');
                    $this->runArtisanCommand(['migrate']);
                    $this->runArtisanCommand(['location:install']);
                    break;
                case "8":
                    $this->requireComposerPackages(['queents/browser-module']);
                    $this->activeModule('Browser');
                    $this->generatePermission('browser');
                    break;
                case "9":
                    $this->requireComposerPackages(['queents/artisan-module']);
                    $this->activeModule('Artisan');
                    $this->generatePermission('artisan');
                    break;
            }
        }

        /*
         * Step 1 Copy And Publish Assets
         */
        $this->info('Install JetStream');
        $this->callSilent('jetstream:install', [
            "stack"=>"inertia"
        ]);
        $this->info('Migrate JetStream Tables');
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
        $this->callSilent('optimize:clear');
        if($theme === "1"){
            $this->info('Add THEME_MODULE=UI to .env');
            $this->info('After Add To .env Please run yarn i & yarn build');
        }
        else if($theme === "2"){
            $this->info('Add THEME_MODULE=FilamentUI to .env');
            $this->info('After Add To .env Please run yarn & yarn add tippy.js & yarn build');
        }
    }

    public function runDirectCommandPHP(array $commands): void
    {
        (new Process(array_merge([$this->phpBinary()],$commands), base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }

    public function generatePermission(string $path): void
    {
        $this->runDirectCommandPHP(['roles:generate', $path]);
    }

    public function runArtisanCommand(array $command):void
    {
        $this->runDirectCommandPHP(array_merge(['artisan'], $command));
    }

    public function activeModule(string $module): void
    {
        $check = File::exists(base_path('/modules_statuses.json'));
        if($check){
            $fileJson = json_decode(File::get(base_path('/modules_statuses.json')));
            $fileJson[$module] = true;
            File::put(base_path('/modules_statuses.json'), json_encode($fileJson));
        }
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
