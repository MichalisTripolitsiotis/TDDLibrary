<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class AuthorsController extends Controller
{
    public function create()
    {
        return view('livewire.authors');
    }
    public function store()
    {

        Author::create($this->validateRequest());
    }

    protected function validateRequest()
    {
        return request()->validate([
            'name' => 'required',
            'dob' => 'required',
        ]);
    }
}
