<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::each(function ($user) {
            $bookIds = Book::inRandomOrder()->take(rand(3,5))->pluck('id');

            foreach ($bookIds as $bookId) {
                $status = collect(['requested', 'borrowed', 'returned'])->random();
                $requestedAt = now()->subDays(rand(5, 30));
                $borrowedAt  = in_array($status, ['borrowed', 'returned']) ? $requestedAt->copy()->addDays(rand(1, 5)) : null;
                $returnedAt  = $status === 'returned' ? $borrowedAt->copy()->addDays(rand(2, 10)) : null;
                $rating      = $status === 'returned' ? rand(3, 5) : null;

                $user->books()->attach($bookId, [
                    'status' => $status,
                    'requested_at' => $requestedAt,
                    'borrowed_at' => $borrowedAt,
                    'returned_at' => $returnedAt,
                    'rating' => $rating,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
