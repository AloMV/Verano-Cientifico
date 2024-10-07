<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function destroy($id)
    {
        $author = Author::with('article')->findOrFail($id);
        $this->authorize('delete', $author);
        $author->delete();
        return response('success', 200);
    }
}
