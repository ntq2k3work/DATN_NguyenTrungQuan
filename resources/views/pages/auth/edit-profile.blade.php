@extends('layouts.app')

@section('title')
Chỉnh sửa hồ sơ - BookStore NTQ
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
              Chỉnh sửa hồ sơ cá nhân
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
              Cập nhật thông tin tài khoản của bạn
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

    <!-- Edit Profile Form -->
    <div class="bg-white shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
          @csrf
          @method('PUT')

          <!-- Success Message -->
          @if(session('status'))
            <div class="rounded-md bg-green-50 p-4">
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

          <!-- First Name -->
          <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700">
              Tên <span class="text-red-500">*</span>
            </label>
            <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $firstName) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('first_name') border-red-300 @enderror"
                   placeholder="Nhập tên của bạn">
            @error('first_name')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Last Name -->
          <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700">
              Họ <span class="text-red-500">*</span>
            </label>
            <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $lastName) }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('last_name') border-red-300 @enderror"
                   placeholder="Nhập họ của bạn">
            @error('last_name')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Date of Birth -->
          <div>
            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">
              Ngày sinh
            </label>
            <input type="date" name="date_of_birth" id="date_of_birth"
                   value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('date_of_birth') border-red-300 @enderror">
            @error('date_of_birth')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Gender -->
          <div>
            <label for="gender" class="block text-sm font-medium text-gray-700">
              Giới tính
            </label>
            <select name="gender" id="gender"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('gender') border-red-300 @enderror">
              <option value="">Chọn giới tính</option>
              <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
              <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
              <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
            </select>
            @error('gender')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Address -->
          <div>
            <label for="address" class="block text-sm font-medium text-gray-700">
              Địa chỉ
            </label>
            <textarea name="address" id="address" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm @error('address') border-red-300 @enderror"
                      placeholder="Nhập địa chỉ của bạn">{{ old('address', $user->address) }}</textarea>
            @error('address')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Email (Read-only) -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Địa chỉ email
            </label>
            <input type="email" id="email" value="{{ $user->email }}" disabled
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 sm:text-sm"
                   placeholder="Email không thể thay đổi">
            <p class="mt-1 text-sm text-gray-500">Email không thể thay đổi sau khi đăng ký</p>
          </div>

          <!-- Form Actions -->
          <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
            <a href="{{ route('profile') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
              Hủy
            </a>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
              <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Cập nhật
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
