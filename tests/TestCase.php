<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Faker\Factory;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{

    use CreatesApplication, DatabaseMigrations;

    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    public function actingAs(Authenticatable $user, $driver = null)
    {

        $token = JWTAuth::fromUser($user);
        $this->withHeader('Authorization', 'Bearer ' . $token);

        return $this;
    }
}
