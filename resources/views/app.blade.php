@extends('layouts.app')
@section('title', 'Home Page')
@section('content')
    <div class="w-full bg-white flex justify-center py-8">
  <div class="w-11/12 max-w-6xl">
    <!-- Tiêu đề -->
    <div class="bg-red-600 text-white text-xl font-bold p-4 rounded-t-xl flex justify-between items-center">
      <span>🔴 TOP SÁCH BÁN CHẠY TRONG THÁNG</span>
      <div class="text-sm">
        Ưu đãi kết thúc: <span class="font-bold">00:00:00</span>
      </div>
    </div>

    <!-- Slider -->
    <div x-data="{ current: 0 }" class="relative overflow-hidden bg-red-600 p-4 rounded-b-xl">
      <div class="flex transition-transform duration-500" :style="`transform: translateX(-${current * 100}%)`">
        <!-- Slide 1 -->
        <div class="flex-none w-full grid grid-cols-5 gap-4">
          <!-- Book card -->
          <div class="bg-white rounded-lg shadow p-4">
            <div class="relative">
              <img src="https://via.placeholder.com/150" class="w-full rounded">
              <span class="absolute top-2 left-2 bg-red-600 text-white text-xs px-2 py-1 rounded">-20%</span>
            </div>
            <p class="text-red-500 text-sm mt-2">DÂN TRÍ</p>
            <h3 class="font-semibold text-gray-800">Sách Tự học siêu tốc - Digital SAT Total Prep</h3>
            <p class="text-lg font-bold text-black">559,200₫ <span class="line-through text-gray-500 text-sm">699,000₫</span></p>
          </div>
          <!-- Repeat more books -->
          <div class="bg-white rounded-lg shadow p-4">...</div>
          <div class="bg-white rounded-lg shadow p-4">...</div>
          <div class="bg-white rounded-lg shadow p-4">...</div>
          <div class="bg-white rounded-lg shadow p-4">...</div>
        </div>

        <!-- Slide 2 -->
        <div class="flex-none w-full grid grid-cols-5 gap-4">
          <div class="bg-white rounded-lg shadow p-4">Slide 2 Book 1</div>
          <div class="bg-white rounded-lg shadow p-4">Slide 2 Book 2</div>
          <div class="bg-white rounded-lg shadow p-4">Slide 2 Book 3</div>
          <div class="bg-white rounded-lg shadow p-4">Slide 2 Book 4</div>
          <div class="bg-white rounded-lg shadow p-4">Slide 2 Book 5</div>
        </div>
      </div>

      <!-- Buttons -->
      <button @click="current = Math.max(current - 1, 0)" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow">
        ◀
      </button>
      <button @click="current = Math.min(current + 1, 1)" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white p-2 rounded-full shadow">
        ▶
      </button>
    </div>
  </div>
</div>

@endsection
