<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
  public function isAdmin(User $user): bool
  {
    return in_array($user->email, config('admin.emails', []));
  }
   
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Book $book): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Book $book): bool
    {
      return $this->isAdmin($user);
    }

    public function delete(User $user, Book $book): bool
    {
      return $this->isAdmin($user);
    }
    
}
