<?php

namespace App\Console\Commands;

use Faker\Factory as Faker;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Config;

class GenerateUsers extends Command
{

    private $nrOfUsers  = 10;
    private $fileName;
    private $columns = array('name', 'email', 'password', 'phone', 'deleted');

    protected $signature = 'command:generate_users';
    protected $description = 'Generates 100k users and exports them into a csv file';

    public function __construct()
    {
        parent::__construct();
        $this->fileName = Config::get('constants.fileOptions.name');
    }

    public function handle()
    {
        $this->generateCSV();
    }

    private function generateCSV()
    {
        $fp = fopen($this->fileName, 'w');
        $this->writeUsersToCSV($fp);
        fclose($fp);
    }

    private function writeUsersToCSV($fp): void
    {
        $faker = Faker::create();
        fputcsv($fp, $this->columns);
        foreach (range(1, $this->nrOfUsers) as $nr) {
            fputcsv($fp, User::getRandomUserForCSV($faker));
        }
    }
}
