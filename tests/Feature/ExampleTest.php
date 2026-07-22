<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_tela_de_login_responde_ok()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
