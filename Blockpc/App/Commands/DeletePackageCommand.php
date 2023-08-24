<?php

declare(strict_types=1);

namespace Blockpc\App\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

final class DeletePackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blockpc:package-delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the basic structure for a new package';

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(protected Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Precaution: The package wil be deleted!');
        if ($this->confirm('Do you wish to continue?')) {
            $name = $this->ask('What is the package name will be deleted?');
            $name = Str::ucfirst($name);

            $package = base_path("Packages/{$name}");
            if ( $this->files->exists($package) ) {

                $this->deleteAll($package);

                @rmdir($package);

                $this->info("The package {$name} was deleted!");

                Artisan::call('optimize', ['--quiet' => true]);

            } else {
                $this->error("The package {$name} dont exists!");
            }
        } else {
            $this->info('Goodbay!');
        }
    }

    private function deleteAll($package)
    {
        $files = glob($package);

        foreach($files as $file) {

            if( is_file($file) ) {
                $this->info("File: {$file} deleted!");
                return unlink($file);
            }

            if ( is_dir($file) ) {
                $this->info("Directory: {$file}");

                // Get the list of the files in this directory
                $scan = glob(rtrim($file, '/').'/*');

                // Loop through the list of files
                foreach($scan as $index => $path) {

                    // Call recursive function
                    $this->deleteAll($path);
                }

                // Remove the directory itself
                return @rmdir($file);
            }
        }
    }
}
