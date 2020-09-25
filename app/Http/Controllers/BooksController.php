<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BooksController extends Controller
{
    public function store()
    {

        $data = $this->validateRequest();
        $book = Book::create($data);
        //path method can be fount in Book Model
        return redirect($book->path());
    }
    public function update(Book $book)
    {
        $data = $this->validateRequest();
        $book->update($data);
        return redirect($book->path());
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect('/books');
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'author_id' => 'required',
        ]);
    }
}
