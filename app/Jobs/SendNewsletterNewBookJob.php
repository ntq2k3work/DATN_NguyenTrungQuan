<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\NewsletterSubscription;
use App\Mail\NewsletterNewBookMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewsletterNewBookJob implements ShouldQueue
{
    use Queueable;

    public $timeout = 300; // 5 minutes
    public $tries = 3;

    protected $bookId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $bookId)
    {
        $this->bookId = $bookId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $book = Book::with(['author', 'category', 'publisher'])->find($this->bookId);
        
        if (!$book) {
            Log::warning("Book not found for newsletter", ['book_id' => $this->bookId]);
            return;
        }

        // Get all active newsletter subscriptions
        $subscriptions = NewsletterSubscription::active()->get();

        if ($subscriptions->isEmpty()) {
            Log::info("No active newsletter subscriptions found");
            return;
        }

        $successCount = 0;
        $failureCount = 0;

        foreach ($subscriptions as $subscription) {
            try {
                Mail::to($subscription->email)->send(
                    new NewsletterNewBookMail($book, $subscription->unsubscribe_token)
                );
                
                $successCount++;
                
                Log::info("Newsletter email sent successfully", [
                    'email' => $subscription->email,
                    'book_id' => $book->id,
                    'book_title' => $book->title
                ]);

            } catch (\Exception $e) {
                $failureCount++;
                
                Log::error("Failed to send newsletter email", [
                    'email' => $subscription->email,
                    'book_id' => $book->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info("Newsletter job completed", [
            'book_id' => $book->id,
            'book_title' => $book->title,
            'total_subscriptions' => $subscriptions->count(),
            'success_count' => $successCount,
            'failure_count' => $failureCount
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Newsletter job failed", [
            'book_id' => $this->bookId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
