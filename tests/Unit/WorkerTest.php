<?php

namespace Tests\Unit;

use App\Jobs\ImportUser;
use App\Models\User;
use Tests\TestCase;
use Faker\Factory as Faker;

class WorkerTest extends TestCase
{

    function getUser()
    {
        $faker = Faker::create();
        return $data = array("Retta Schmidt", $faker->email, "zK<ip4(R","557-922-9228 x98890","1");
    }

    public function test_importer_should_create_new_user_in_the_database()
    {
        $importUser = new ImportUser($this->getUser());
        $id = $importUser->createUserInDatabase();
        // because I didnt used a test database, lets delete it, use forcedelete, because softdelete is activated
        User::where('id', $id)->forcedelete();
        $this->assertTrue($id > 0);
    }
}
