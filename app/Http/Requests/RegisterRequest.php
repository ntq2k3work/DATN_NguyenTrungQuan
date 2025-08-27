<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string|null $date_of_birth
 * @property string|null $address
 * @property string|null $gender
 * @property string $password
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'date_of_birth' => 'nullable|date|after_or_equal:1900-01-01|before_or_equal:today',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'g-recaptcha-response' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Tên là bắt buộc',
            'last_name.required' => 'Họ là bắt buộc',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Vui long nhập địa chỉ email hợp lệ.',
            'date_of_birth.before_or_equal' => 'Ngày sinh phải trước ngày hiện tại',
            'email.unique' => 'Tài khoản với email này đã tồn tại.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'address.required' => 'Địa chỉ là bắt buộc.',
            'g-recaptcha-response.required' => 'Vui lòng xác nhận bạn không phải là người máy.',
        ];
    }
}
