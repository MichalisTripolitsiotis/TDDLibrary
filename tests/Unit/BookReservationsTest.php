<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationsTest extends TestCase
{
    use RefreshDatabase;
    /**@test**/
    public function test_a_book_can_be_checked_out()
    {
        //create two  objects
        $book = Book::factory()->create();
        $user = User::factory()->create();
        //make the checkout
        $book->checkout($user);

        //if success then I want one reservation mainly
        $this->assertCount(1, Reservation::all());
        //return the reservation for the user
        $this->assertEquals($user->id, Reservation::first()->user_id);
        //return the reservation for the book
        $this->assertEquals($book->id, Reservation::first()->book_id);
        //check the time of reservation
        //$this->assertFileEquals(now(), Reservation::first()->checked_out_at);
    }

    /**@test**/
    public function test_a_book_can_be_returned()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $book->checkout($user);
        $book->checkin($user);

        //if success then I want one reservation mainly
        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertNotNull(Reservation::first()->checked_in_at);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    /**@test**/
    public function test_if_not_checked_out_exception_thrown()
    {
        //cannot check in a book that never checked out
        $this->expectException(\Exception::class);
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $book->checkin($user);
    }

    /**@test**/
    public function test_a_book_can_be_checked_out_twice()
    {
        //$this->withoutExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $book->checkout($user);
        $book->checkin($user);
        $book->checkout($user);
        //if success then I want one reservation mainly
        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        //null because we haven't checked in
        $this->assertNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(), Reservation::find(2)->checked_out_at);

        $book->checkin($user);
        //we expect 2 reservations
        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, Reservation::find(2)->user_id);
        $this->assertEquals($book->id, Reservation::find(2)->book_id);
        $this->assertNotNull(Reservation::find(2)->checked_in_at);
        $this->assertEquals(now(), Reservation::find(2)->checked_in_at);
    }
}
