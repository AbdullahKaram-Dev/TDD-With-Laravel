<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Book;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_a_book_can_be_added_to_the_library()
    {
        /* prevent pretty laravel exception handling */
        $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'The Great Gatsby',
            'author' => 'F. Scott Fitzgerald',
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());
        $response->assertRedirect('/books/'.$book->id);
    }

    /** @test */
    public function test_title_must_be_required()
    {
        $response = $this->post('/books', [
            'title' => '',
            'author' => 'F. Scott Fitzgerald',
        ]);
        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function test_author_must_be_required()
    {
        $response = $this->post('/books', [
            'title' => 'title',
            'author' => '',
        ]);
        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function test_a_book_can_be_updated()
    {
        $this->post('/books', [
            'title' => 'Cool Title',
            'author' => 'Victor',
        ]);

        $book = Book::first();

        $response = $this->patch('/books/'.$book->id,[
            'title' => 'New Title',
            'author' => 'New Author',
        ]);

        $this->assertEquals('New Title', Book::first()->title);
        $this->assertEquals('New Author', Book::first()->author);
        $response->assertRedirect('/books/'.$book->id);
    }

    /** @test */
    public function test_book_can_be_deleted()
    {
        $this->post('/books', [
            'title' => 'Cool Title',
            'author' => 'Victor',
        ]);
        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete('/books/'.$book->id);
        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }
}
