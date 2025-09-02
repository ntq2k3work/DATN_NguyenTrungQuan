<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            [
                'name' => 'Nguyễn Nhật Ánh',
                'bio' => 'Nhà văn nổi tiếng Việt Nam với nhiều tác phẩm văn học thiếu nhi và thanh niên.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tô Hoài',
                'bio' => 'Nhà văn lớn của văn học Việt Nam hiện đại, tác giả của "Dế Mèn phiêu lưu ký".',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nam Cao',
                'bio' => 'Nhà văn hiện thực phê phán xuất sắc, tác giả của "Chí Phèo", "Lão Hạc".',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vũ Trọng Phụng',
                'bio' => 'Nhà văn hiện thực phê phán, được mệnh danh là "ông vua phóng sự đất Bắc".',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thạch Lam',
                'bio' => 'Nhà văn lãng mạn, tác giả của "Hà Nội băm sáu phố phường".',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nguyễn Tuân',
                'bio' => 'Nhà văn tài hoa, tác giả của "Vang bóng một thời", "Sông Đà".',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Xuân Quỳnh',
                'bio' => 'Nhà thơ nữ xuất sắc, tác giả của "Sóng", "Thuyền và biển".',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hồ Chí Minh',
                'bio' => 'Chủ tịch Hồ Chí Minh - nhà thơ, nhà văn hóa lớn của dân tộc Việt Nam.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tố Hữu',
                'bio' => 'Nhà thơ cách mạng, tác giả của "Việt Bắc", "Từ ấy".',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chế Lan Viên',
                'bio' => 'Nhà thơ lớn, tác giả của "Điêu tàn", "Ánh sáng và phù sa".',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('authors')->insert($authors);
    }
}
