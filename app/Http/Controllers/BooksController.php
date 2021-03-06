<?php

namespace App\Http\Controllers;

use App\Models\Book;

class BooksController extends Controller
{
    public function store()
    {
        $book = Book::create($this->validateRequest());
        return redirect('/books/'.$book->id);
    }

    public function update(Book $book)
    {
        $book->update($this->validateRequest());
        return redirect('/books/'.$book->id);
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect('/books');
    }

    public function show(Book $book)
    {

    }

    protected function validateRequest():array
    {
        return request()->validate([
            'title' => 'required',
            'author_id' => 'required'
        ]);
    }
}
