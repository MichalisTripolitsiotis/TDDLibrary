<?php

namespace Tests\Feature;

use App\Http\Controllers\BooksController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;

class BookManagementTest extends TestCase
{
    //refresh db meaning not really store data in order to test the cases
    use RefreshDatabase;

    /**@test**/
    public function test_a_book_can_be_added()
    {

        //for better error handling
        $this->withoutExceptionHandling();
        //if I hit an end point 'books' with title, author
        $response = $this->post('/books', $this->data());
        //get the latest book
        $book = Book::first();
        //we are optimistics and I want as a result one book to added and an ok response
        //$response->assertOk(); can be used ONLY without redirection
        $this->assertCount(1, Book::all());
        $response->assertRedirect($book->path());
    }

    /**@test**/
    public function test_empty_title()
    {
        // use RefreshDatabase;
        //for better error handling
        // $this->withoutExceptionHandling();
        //if I hit an end point 'books' with title, author
        $response = $this->post('/books', [
            'title' => "",
            'author' => "bauthor",
        ]);
        //we are optimistics and I want as a result one book to added and an ok response
        $response->assertSessionHasErrors('title');
    }
    /**@test**/
    public function test_empty_author()
    {
        // use RefreshDatabase;
        //for better error handling
        // $this->withoutExceptionHandling();
        //if I hit an end point 'books' with title, author
        $response = $this->post('/books', array_merge($this->data(), ['author_id' => '']));
        //we are optimistics and I want as a result one book to added and an ok response
        $response->assertSessionHasErrors('author_id');
    }

    /**@test**/
    public function test_update_book()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', $this->data());
        $book = Book::first();
        $response = $this->patch($book->path(), [
            'title' => 'New Title',
            'author_id' => 'New Author',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals(2, Book::first()->author_id);
        $response->assertRedirect($book->fresh()->path());
    }

    /**@test**/
    public function test_delete_book()
    {
        $this->withoutExceptionHandling();
        $this->post('/books', $this->data());
        $book = Book::first();
        $response = $this->delete($book->path());
        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

    /** @test */
    public function test_a_new_author_is_automatically_added()
    {
        //$this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Cool Title',
            'author_id' => 'Victor',
        ]);

        $book = Book::first();
        $author = Author::first();
        //dd($book->autor_id);
        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());
    }
    private function data()
    {
        return [
            'title' => 'Cool Book Title',
            'author_id' => 'Victor',
        ];
    }
}
