<?php

namespace App\Console\Commands;

use App\Jobs\ImportUser;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

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
        foreach ($this->loadUsersFromSCV() as $user)
        {
            $insertJob = (new ImportUser($user));
            dispatch($insertJob);
        }
    }

    private function loadUsersFromSCV()
    {
        $csv = array_map('str_getcsv', file($this->fileName));
        return array_slice($csv, 1);
    }

}
