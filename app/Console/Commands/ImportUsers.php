<?php

namespace App\Console\Commands;

use App\Jobs\ImportUser;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ImportUsers extends Command
{
    use Queueable, SerializesModels;

    private $fileName;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import_users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports users from tfile and puts in to queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->fileName = Config::get('constants.fileOptions.name');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!Storage::disk('public')->exists($this->fileName))
        {
            echo "\nImport file {$this->fileName} does not exists, please run 'php artisan command:generate_users'\n";
            return;
        }

        $this->addUsersToQueue();
    }

    private function loadUsersFromSCV()
    {
        $csv = array_map('str_getcsv', explode("\n", $this->getFileContent()));
        return array_slice($csv, 1);
    }

    private function addUsersToQueue(): void
    {
        foreach ($this->loadUsersFromSCV() as $user) {
            $insertJob = (new ImportUser($user));
            dispatch($insertJob);
        }
    }

    /**
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getFileContent(): string
    {
        return Storage::disk('public')->get($this->fileName);
    }

}
