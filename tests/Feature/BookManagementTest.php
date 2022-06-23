<?php

namespace Tests\Feature;

use App\Models\Author;
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

        $response = $this->post('/books',$this->data());

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
        $response = $this->post('/books',array_merge($this->data(), ['author_id' => '']));
        $response->assertSessionHasErrors('author_id');
    }

    /** @test */
    public function test_a_book_can_be_updated()
    {
        $this->post('/books',$this->data());

        $book = Book::first();

        $response = $this->patch('/books/'.$book->id,$this->data());

        $this->assertEquals('Cool Title', Book::first()->title);
        $this->assertEquals(1, Book::first()->author_id);
        $response->assertRedirect('/books/'.$book->id);
    }

    /** @test */
    public function test_book_can_be_deleted()
    {
        $this->post('/books',$this->data());

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete('/books/'.$book->id);

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }

    /** @test */
    public function test_new_author_is_automatically_added()
    {
        $this->withoutExceptionHandling();

        $this->post('/books',$this->data());

        $book   = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id, $book->author_id);
        $this->assertCount(1, Author::all());

    }

    protected function data():array
    {
        return [
            'title' => 'Cool Title',
            'author_id' => 1,
        ];
    }
}
