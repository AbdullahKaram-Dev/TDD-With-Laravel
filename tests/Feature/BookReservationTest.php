<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookReservationTest extends TestCase
{

  /** test */
  public function test_a_book_can_be_added_to_the_library()
  {

    $this->withoutExceptionHandling();

    $response = $this->post('/books', [
      'title' => 'The Great Gatsby',
      'author' => 'F. Scott Fitzgerald',
    ]);

    $response->assertOk();
    $this->assertCount(1, Book::all());
  }
}
