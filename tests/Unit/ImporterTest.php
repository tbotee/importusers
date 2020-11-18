<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class ImporterTest extends TestCase
{

    function getDeletedUser()
    {
        return $data = array("Retta Schmidt", "shakira66@example.com", "zK<ip4(R","557-922-9228 x98890","");
    }

    function getUser()
    {
        return $data = array("Retta Schmidt", "shakira66@example.com", "zK<ip4(R","557-922-9228 x98890","1");
    }

    public function test_undeleted_user_should_set_on_deleted_at_to_null()
    {
        $user = User::factory()->make();
        $user->populateUserModel($this->getDeletedUser());
        $this->assertEquals(null, $user->deleted_at);
    }

    public function test_imported_user_without_deleted_flag_should_set_on_deleted_at_to_current_date()
    {
        $user = User::factory()->make();
        $user->populateUserModel($this->getUser());
        $this->assertTrue($user->deleted_at != null);
    }

    public function test_imported_password_gets_hashed()
    {
        $user = User::factory()->make();
        $user->populateUserModel($this->getUser());
        $this->assertTrue(substr( $user->password, 0, 7 ) === "$2y$04$");
    }

}
