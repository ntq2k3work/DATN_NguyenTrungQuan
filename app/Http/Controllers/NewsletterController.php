<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\NewsletterWelcomeMail;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'email.max' => 'Địa chỉ email không được vượt quá 255 ký tự.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;

        // Check if email already exists
        $existingSubscription = NewsletterSubscription::where('email', $email)->first();

        if ($existingSubscription) {
            if ($existingSubscription->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email này đã được đăng ký nhận thông tin sách mới.'
                ], 400);
            } else {
                // Reactivate subscription
                $existingSubscription->resubscribe();

                // Send welcome email only if user wants email notifications
                $user = User::where('email', $email)->first();
                if (!$user || $user->wantsEmailNotifications()) {
                    try {
                        Mail::to($email)->send(new NewsletterWelcomeMail($email));
                        Log::info('Newsletter welcome email sent', ['email' => $email]);
                    } catch (\Exception $e) {
                        // Log error but don't fail the subscription
                        Log::error('Failed to send welcome email: ' . $e->getMessage());
                    }
                } else {
                    Log::info('Newsletter welcome email skipped - user disabled email notifications', [
                        'email' => $email,
                        'user_id' => $user->id
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Đăng ký nhận thông tin sách mới thành công!'
                ]);
            }
        }

        // Create new subscription
        $subscription = NewsletterSubscription::create([
            'email' => $email,
            'is_active' => true,
            'subscribed_at' => now(),
        ]);

        // Send welcome email only if user wants email notifications
        $user = User::where('email', $email)->first();
        if (!$user || $user->wantsEmailNotifications()) {
            try {
                Mail::to($email)->send(new NewsletterWelcomeMail($email));
                Log::info('Newsletter welcome email sent', ['email' => $email]);
            } catch (\Exception $e) {
                // Log error but don't fail the subscription
                Log::error('Failed to send welcome email: ' . $e->getMessage());
            }
        } else {
            Log::info('Newsletter welcome email skipped - user disabled email notifications', [
                'email' => $email,
                'user_id' => $user->id
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký nhận thông tin sách mới thành công!'
        ]);
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request, $token)
    {
        $subscription = NewsletterSubscription::where('unsubscribe_token', $token)->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Token hủy đăng ký không hợp lệ.'
            ], 404);
        }

        $subscription->unsubscribe();

        return response()->json([
            'success' => true,
            'message' => 'Bạn đã hủy đăng ký nhận thông tin sách mới thành công.'
        ]);
    }

    /**
     * Get unsubscribe page
     */
    public function unsubscribePage($token)
    {
        $subscription = NewsletterSubscription::where('unsubscribe_token', $token)->first();

        if (!$subscription) {
            return view('newsletter.unsubscribe-error');
        }

        return view('newsletter.unsubscribe', compact('subscription'));
    }
}
