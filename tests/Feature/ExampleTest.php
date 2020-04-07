<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
 use RefreshDatabase;
 /**
  * A basic test example.
  *
  * @return void
  */
 public function testBasicTest()
 {
  $response = $this->get('/');

  $response->assertStatus(200);
 }

 public function testBasicExample()
 {
  $this->withoutExceptionHandling();

  $data = [
   'name' => 'testUser',
   'email' => 'dummy@email.com',
   'password' => 'test1234',
   'password_confirmation' => 'test1234'
  ];
  $response = $this->withHeaders([
   // 'X-Header' => 'Value',
  ])->json('POST', route('register'), $data);

  $response
   ->assertStatus(302);
  // ->assertJson([
  //  'created' => true,
  // ]);
 }
}
