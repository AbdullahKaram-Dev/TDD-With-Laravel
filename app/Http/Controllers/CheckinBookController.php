<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CheckinBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function store(Request $request,Book $book)
    {
        try {
            $book->checkin($request->user());
        }catch (\Exception $e) {
            return response([''],404);
        }
    }
}
