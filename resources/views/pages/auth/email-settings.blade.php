@extends('layouts.app')

@section('title')
Cài đặt thông báo email - BookStore NTQ
@endsection

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
  <div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg mb-6">
      <div class="px-4 py-5 sm:p-6">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Cài đặt thông báo email
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Quản lý các thông báo email bạn muốn nhận từ BookStore NTQ
            </p>
          </div>
          <div class="flex space-x-3">
            <a href="{{ route('profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
              <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
              </svg>
              Quay lại
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Success Message -->
    @if(session('status'))
      <div class="mb-6 rounded-md bg-green-50 p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-green-800">
              {{ session('status') }}
            </p>
          </div>
        </div>
      </div>
    @endif

    <!-- Email Settings Form -->
    <div class="bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <form method="POST" action="{{ route('email-settings.update') }}" class="space-y-6" id="emailSettingsForm">
          @csrf
          @method('PUT')

          <!-- Email Notifications Toggle -->
          <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
              </div>
              <div class="ml-4 flex-1">
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">
                      Thông báo email
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                      Nhận thông báo qua email về đơn hàng, sách mới và các ưu đãi đặc biệt
                    </p>
                  </div>
                  <div class="ml-4">
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox" name="email_notifications_enabled" value="1"
                             {{ old('email_notifications_enabled', $user->email_notifications_enabled) ? 'checked' : '' }}
                             class="sr-only peer">
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                  </div>
                </div>

                <!-- Notification Types -->
                <div class="mt-4 space-y-3">
                  <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Thông báo đơn hàng (xác nhận, vận chuyển, giao hàng)
                  </div>
                  <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Thông tin sách mới và khuyến mãi
                  </div>
                  <div class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Newsletter và tin tức về sách
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('profile') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
              Hủy
            </a>
            <button type="submit" id="submitBtn"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
              <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Lưu cài đặt
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Toast Notification Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('emailSettingsForm');
    const submitBtn = document.getElementById('submitBtn');

    // Handle form submission
    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Đang lưu...
        `;

        // Show info toast
        showInfoToast('Đang lưu cài đặt thông báo...');
    });

    // Handle form validation errors
    @if($errors->any())
        @foreach($errors->all() as $error)
            showErrorToast("{{ $error }}");
        @endforeach
    @endif
});
</script>
@endsection
