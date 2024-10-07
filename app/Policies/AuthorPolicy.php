<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\User;

class AuthorPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, Author $author): bool
    {
        $author->load('article');
        return $user->getPermission('article.update') && $user->id === $author->article->postulant_id;
    }
}
