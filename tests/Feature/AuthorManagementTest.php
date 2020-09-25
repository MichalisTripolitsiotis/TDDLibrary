<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Author;
use Carbon\Carbon;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;
    /**@test**/
    public function test_author_can_be_created()
    {
        //$this->withoutExceptionHandling();
        $this->post('/authors', $this->data());
        $author = Author::all();
        $this->assertCount(1, $author);
        $this->assertInstanceOf(Carbon::class, $author->first()->dob);
        $this->assertEquals('14/05/1988', $author->first()->dob->format('d/m/Y'));
    }

    /**@test**/
    public function test_a_name_is_required()
    {
        $response = $this->post('/authors', array_merge($this->data(), ['name' => '']));
        $response->assertSessionHasErrors('name');
    }

    /**@test**/
    public function test_a_dob_is_required()
    {
        $response = $this->post('/authors', array_merge($this->data(), ['dob' => '']));
        $response->assertSessionHasErrors('dob');
    }

    private function data()
    {
        return [
            'name' => "Author Name",
            'dob' => "1988/05/14",
        ];
    }
}
