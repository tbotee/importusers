<?php

namespace App\Console\Commands;

use Faker\Factory as Faker;
use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

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
        $this->writeColumnHeaders();
        $this->writeUserRows(Faker::create());
    }

    private function writeUserRows($faker)
    {
        $userRows = '';
        foreach (range(1, $this->nrOfUsers) as $nr) {
            $userRows .= ($nr === 1 ? "" : "\n") . $this->getCSVString(User::getRandomUserForCSV($faker));
        }
        Storage::disk('public')->append($this->fileName, $userRows);
    }

    private function writeColumnHeaders()
    {
        Storage::disk('public')->put($this->fileName, $this->getCSVString($this->columns));
    }

    private function getCSVString($list)
    {
        return '"'.implode('","', $list) . '"';
    }
}
