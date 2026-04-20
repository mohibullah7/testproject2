<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

            public function test_basic_math()
        {
            $result = 2 + 2;

            $this->assertEquals(4, $result);
        }

        public function test_alpha(){

         $post = Post::where('id',3)->get(); 

         $a = 30;
         $b = 50 ;
         $number = $a == $b ;

         $this->assertTrue($number);
        }
}
