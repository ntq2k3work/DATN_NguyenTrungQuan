@extends('layouts.app')

@section('script')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('title')
Đăng ký - BookStore NTQ
@endsection

@section('content')
<div class="min-h-screen flex items-baseline justify-center p-4">
  <div class="w-full max-w-2xl p-8 md:p-10 rounded-lg shadow-lg">
    <!-- Tabs -->
    <div class="flex justify-center mb-6 text-lg">
      <a href="{{ route('login') }}" class="px-4 font-semibold text-gray-400">Đăng nhập</a>
      <span class="border-l mx-4"></span>
      <a href="#" class="px-4 font-bold text-black">Đăng ký</a>
    </div>

    <!-- Form -->
    <form action="{{ route('handleRegister') }}" method="POST" class="space-y-5 text-base">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <input type="text" placeholder="Họ" name="last_name" value="{{ old('last_name') }}" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200 @error('last_name') border border-red-400 @enderror">
          @error('last_name')
          <p class="mt-1 text-sm text-red-600 field-error" data-field="last_name">{{ $message }}</p>
          @enderror
        </div>
        <div>
          <input type="text" placeholder="Tên" name="first_name" value="{{ old('first_name') }}" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200 @error('first_name') border border-red-400 @enderror">
          @error('first_name')
          <p class="mt-1 text-sm text-red-600 field-error" data-field="first_name">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <!-- Giới tính -->
      <div class="flex items-center space-x-6 text-gray-700">
        <label class="flex items-center space-x-2">
          <input type="radio" name="gender" value="female" class="h-4 w-4" {{ old('gender') === 'female' ? 'checked' : '' }}>
          <span>Nữ</span>
        </label>
        <label class="flex items-center space-x-2">
          <input type="radio" name="gender" value="male" class="h-4 w-4" {{ old('gender') === 'male' ? 'checked' : '' }}>
          <span>Nam</span>
        </label>
      </div>
      @error('gender')
      <p class="mt-1 text-sm text-red-600 field-error" data-field="gender">{{ $message }}</p>
      @enderror

      <!-- Ngày sinh / Email / Mật khẩu -->
      <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200 @error('date_of_birth') border border-red-400 @enderror">
      @error('date_of_birth')
      <p class="mt-1 text-sm text-red-600 field-error" data-field="date_of_birth">{{ $message }}</p>
      @enderror
      <input type="text" name="address" placeholder="Nhập địa chỉ" value="{{ old('address') }}" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200 @error('address') border border-red-400 @enderror">
      @error('address')
      <p class="mt-1 text-sm text-red-600 field-error" data-field="address">{{ $message }}</p>
      @enderror
      <input type="email" placeholder="Email" name="email" value="{{ old('email') }}" class="w-full p-3 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200 @error('email') border border-red-400 @enderror">
      @error('email')
      <p class="mt-1 text-sm text-red-600 field-error" data-field="email">{{ $message }}</p>
      @enderror

      <!-- Password Field with Eye Icon -->
      <div class="relative">
        <input type="password" placeholder="Mật khẩu" name="password" id="password" class="w-full p-3 pr-12 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200 @error('password') border border-red-400 @enderror">
        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePasswordVisibility('password')" aria-label="Hiển thị/ẩn mật khẩu">
          <svg id="eye-icon-password" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
        </button>
      </div>
      @error('password')
      <p class="mt-1 text-sm text-red-600 field-error" data-field="password">{{ $message }}</p>
      @enderror

      <!-- Confirm Password Field with Eye Icon -->
      <div class="relative">
        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Nhập lại mật khẩu" class="w-full p-3 pr-12 bg-gray-100 rounded outline-none focus:ring-2 focus:ring-red-200 @error('password_confirmation') border border-red-400 @enderror">
        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" onclick="togglePasswordVisibility('password_confirmation')" aria-label="Hiển thị/ẩn mật khẩu">
          <svg id="eye-icon-password_confirmation" class="h-5 w-5 text-gray-400 hover:text-gray-600 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
        </button>
      </div>
      @error('password_confirmation')
      <p class="mt-1 text-sm text-red-600 field-error" data-field="password_confirmation">{{ $message }}</p>
      @enderror

      <p class="text-xs text-gray-500">
        <div class="g-recaptcha" data-sitekey="{{ env('reCAPTCHA_SITE') }}" data-callback="onReCaptchaSuccess"></div>
      </p>
      @error('g-recaptcha-response')
      <p class="mt-1 text-sm text-red-600 field-error" data-field="g-recaptcha-response">{{ $message }}</p>
      @enderror

      <div class="flex items-center gap-6">
        <button type="submit" class="bg-red-600 text-white font-bold py-3 px-6 rounded hover:bg-red-700">
          ĐĂNG KÝ
        </button>
        <div class="text-sm text-gray-700">
          Bạn đã có tài khoản?
          <a href="#" class="text-blue-600 ml-2">Đăng nhập ngay</a>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Interaction Scripts: Toggle Password & Hide Validation Errors on Input -->
<script>
function togglePasswordVisibility(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const eyeIcon = document.getElementById(`eye-icon-${fieldId}`);

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
        `;
        eyeIcon.setAttribute('aria-label', 'Ẩn mật khẩu');
    } else {
        passwordField.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
        `;
        eyeIcon.setAttribute('aria-label', 'Hiển thị mật khẩu');
    }
}

// Ẩn lỗi khi người dùng nhập/chọn
function hideFieldError(fieldName) {
    const errorEl = document.querySelector(`.field-error[data-field="${fieldName}"]`);
    if (errorEl) errorEl.style.display = 'none';
    // Bỏ viền đỏ trên input tương ứng
    const inputEls = document.querySelectorAll(`[name="${fieldName}"]`);
    inputEls.forEach(el => {
        el.classList.remove('border', 'border-red-400');
    });
}

// Lắng nghe nhập liệu cho các input có trong form
document.addEventListener('DOMContentLoaded', function () {
    const fields = ['last_name','first_name','date_of_birth','address','email','password','password_confirmation'];
    fields.forEach(name => {
        const elements = document.querySelectorAll(`[name="${name}"]`);
        elements.forEach(el => {
            const eventType = (el.type === 'radio' || el.type === 'checkbox' || el.tagName === 'SELECT') ? 'change' : 'input';
            el.addEventListener(eventType, () => hideFieldError(name));
        });
    });

    // Radio group gender
    const genderRadios = document.querySelectorAll('input[name="gender"]');
    genderRadios.forEach(r => r.addEventListener('change', () => hideFieldError('gender')));
});

// reCAPTCHA callback: khi xác minh xong thì ẩn lỗi
function onReCaptchaSuccess() {
    hideFieldError('g-recaptcha-response');
}
</script>
@endsection
