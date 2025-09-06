<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Discount;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::take(10)->get();

        foreach ($books as $book) {
            // Tạo discount theo phần trăm
            Discount::create([
                'book_id' => $book->id,
                'percent' => rand(10, 50),
                'amount' => null,
                'start_date' => Carbon::now()->subDays(rand(1, 30)),
                'end_date' => Carbon::now()->addDays(rand(30, 90)),
            ]);

            // Tạo discount theo số tiền (cho một số sách)
            if (rand(0, 1)) {
                Discount::create([
                    'book_id' => $book->id,
                    'percent' => null,
                    'amount' => rand(50000, 200000),
                    'start_date' => Carbon::now()->subDays(rand(1, 15)),
                    'end_date' => Carbon::now()->addDays(rand(15, 60)),
                ]);
            }
        }

        // Tạo một số discount đã hết hạn
        for ($i = 0; $i < 5; $i++) {
            Discount::create([
                'book_id' => $books->random()->id,
                'percent' => rand(20, 40),
                'amount' => null,
                'start_date' => Carbon::now()->subDays(rand(60, 90)),
                'end_date' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }

        // Tạo một số discount chưa bắt đầu
        for ($i = 0; $i < 3; $i++) {
            Discount::create([
                'book_id' => $books->random()->id,
                'percent' => rand(15, 35),
                'amount' => null,
                'start_date' => Carbon::now()->addDays(rand(1, 30)),
                'end_date' => Carbon::now()->addDays(rand(60, 120)),
            ]);
        }
    }
}
