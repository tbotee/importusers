<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ImportUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userRow;

    /**
     * Create a new job instance.
     *
     * @param $userRow
     */
    public function __construct($userRow)
    {
        $this->userRow = $userRow;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->createUserInDatabase();
    }

    public function createUserInDatabase() : int
    {
        $user = new User();
        $user->populateUserModel($this->userRow);
        $user->save();
        return $user->id;
    }

}
