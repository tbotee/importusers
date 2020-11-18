<?php

namespace Tests\Feature;

use App\Jobs\ImportUser;
use Tests\TestCase;
use App\Models\User;

class WorkerFuntionalTest extends TestCase
{

    function getDeletedUser($email)
    {
        return $data = array("Retta Schmidt", $email , "zK<ip4(R","557-922-9228 x98890","");
    }

    public function test_worker_should_add_user_to_database()
    {
        $mail = "most_random_email_ever1234@ghdt356a.com";
        ImportUser::dispatch($this->getDeletedUser($mail));

        $this->assertDatabaseHas("users", array(
            "email" => $mail,
        ));

        // because I didnt used a test database, lets delete it, use forcedelete, because softdelete is activated
        User::where('email', $mail)->forcedelete();

    }
}
