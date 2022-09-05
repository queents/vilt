<?php

namespace Queents\Vilt\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InstallVilt extends Command
{

    public string $publish;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'vilt:install';

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
        /*
         * Step 1 Copy And Publish Assets
         */
        $this->info('Install Jetstream');
        $this->runArtisan('jetstream:install', [
            "stack"=>"inertia"
        ]);
        $this->info('Migrate Jetstream Tables');
        $this->runArtisan('migrate');
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
        $this->info('Clear cache');
        $this->runArtisan('optimize:clear');
        $this->info('Please run npm i & npm run build');
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

    public function runArtisan(string $command, array $args=[]): string
    {
        Artisan::call($command, $args);
        return  Artisan::output();
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
}
