<?php

namespace App\Console\Commands;

use Faker\Factory as Faker;
use Illuminate\Console\Command;
use App\Models\User;

class GenerateUsers extends Command
{

    private $nrOfUsers  = 100000;
    private $fileName   = 'userList.csv';

    protected $signature = 'command:generate_users';
    protected $description = 'Generates 100k users and exports them into a csv file';

    public function __construct()
    {
        parent::__construct();
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
        foreach (range(1, $this->nrOfUsers) as $nr) {
            fputcsv($fp, User::getRandomUserForCSV($faker));
        }
    }
}
