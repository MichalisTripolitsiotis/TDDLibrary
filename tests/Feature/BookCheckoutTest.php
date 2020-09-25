<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;
    /**@test**/
    public function test_a_book_can_be_checked_out_by_signed_in_user()
    {
        // $this->withoutExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user)
            ->post('/checkout/' . $book->id);

        //if success then I want one reservation mainly
        $this->assertCount(1, Reservation::all());
        //return the reservation for the user
        $this->assertEquals($user->id, Reservation::first()->user_id);
        //return the reservation for the book
        $this->assertEquals($book->id, Reservation::first()->book_id);
        //check the time of reservation
        //  $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    /**@test**/
    public function test_only_signed_users_can_checkout_a_book()
    {
        //$this->withoutExceptionHandling();
        $book = Book::factory()->create();

        $this->post('/checkout/' . $book->id)
            ->assertRedirect('/login');

        $this->assertCount(0, Reservation::all());
    }

    /**@test**/
    public function test_only_real_books_can_be_checked_out()
    {

        $user = User::factory()->create();
        $this->actingAs($user)
            ->post('/checkout/123')
            ->assertStatus(404);
        $this->assertCount(0, Reservation::all());
    }

    /**@test**/
    public function test_a_book_can_be_checked_in_by_signed_in_user()
    {
        //$this->withoutExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();
        //first make a reservation in order to test the 'bring back' function
        $this->actingAs($user)
            ->post('/checkout/' . $book->id);

        $this->actingAs($user)
            ->post('/checkin/' . $book->id);

        $this->assertCount(1, Reservation::all());

        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        //issue with Carbon
        //$this->assertEquals(now(), Reservation::first()->checked_out_at);
        //$this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    /**@test**/
    public function test_only_signed_users_can_checkin_a_book()
    {
        //$this->withoutExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user)
            ->post('/checkout/' . $book->id);
        Auth::logout();
        $this->post('/checkin/' . $book->id)
            ->assertRedirect('/login');

        $this->assertCount(1, Reservation::all());
        $this->assertNull(Reservation::first()->checked_in_at);
    }

    /**@test**/
    public function test_a_404_is_thrown_if_book_not_checked_out_first()
    {
        //$this->withoutExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/checkin/' . $book->id)
            ->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }
}
