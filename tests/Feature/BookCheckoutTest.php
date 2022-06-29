<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation;
use App\Models\Book;
use App\Models\User;
use Tests\TestCase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_a_book_can_be_checked_out_by_signed_in_user()
    {
        $this->withoutExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user)->post('/checkout/'.$book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    /** @test */
    public function test_only_signed_in_users_can_check_out_a_book()
    {
        $book = Book::factory()->create();
        $this->post('/checkout/'.$book->id)->assertRedirect('/login');
        $this->assertCount(0, Reservation::all());
    }

    /** @test */
    public function test_only_exists_books_can_be_checkout()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/checkout/'. 2)
             ->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

    /** @test */
    public function test_a_book_can_be_checked_in_by_signed_in_user()
    {
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/checkout/'.$book->id);
        $this->actingAs($user)->post('/checkin/'.$book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);

        $this->assertEquals(now(),Reservation::first()->checked_out_at);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    /** @test */
    public function test_only_signed_in_users_can_check_in_a_book()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user)->post('/checkout/'.$book->id);

        /* logout user and destroy session */
        Auth::logout();

        $this->post('/checkin/'.$book->id)->assertRedirect('/login');
        $this->assertCount(1, Reservation::all());
        $this->assertNull(Reservation::first()->checked_in_at);
    }

    /** @test  */
    public function test_only_exists_books_can_be_checked_in()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post('/checkout/'. 2)
             ->assertStatus(404);
        $this->assertCount(0, Reservation::all());

        $book = Book::factory()->create();
        $this->actingAs($user)->post('/checkout/'.$book->id)->assertStatus(200);
        $this->assertCount(1, Reservation::all());
    }

    /** @test  */
    public function test_404_thrown_if_book_not_checked_out_first()
    {
        $this->withoutExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/checkin/'.$book->id)->assertStatus(404);

        $this->assertCount(0, Reservation::all());
    }

}
