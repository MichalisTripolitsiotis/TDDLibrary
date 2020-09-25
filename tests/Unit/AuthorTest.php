<?php

namespace Tests\Unit;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
//use the bellow if error ..function connection()
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;
    /**@test**/
    public function test_only_a_name_required()
    {

        Author::firstOrCreate([
            'name' => 'John Doe',
        ]);

        $this->assertCount(1, Author::all());
    }
}
