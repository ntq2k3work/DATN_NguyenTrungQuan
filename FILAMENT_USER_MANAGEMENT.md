# Hệ thống Quản lý User bằng Filament

## Tổng quan

Hệ thống quản lý user được xây dựng bằng Filament 4.0, cung cấp giao diện admin hiện đại và dễ sử dụng để quản lý người dùng trong ứng dụng Laravel.

## Tính năng chính

### 1. Quản lý User (UserResource)

#### Danh sách User
- **Hiển thị thông tin**: ID, Họ tên, Email, Vai trò, Ngày sinh, Giới tính, Trạng thái xác thực, Số đơn hàng
- **Tìm kiếm**: Theo tên, email, ID
- **Lọc**: Theo vai trò, giới tính, trạng thái xác thực, khoảng thời gian tạo
- **Sắp xếp**: Theo các cột khác nhau
- **Phân trang**: 10, 25, 50, 100 bản ghi mỗi trang

#### Tạo mới User
- **Thông tin cơ bản**:
  - Họ và tên (bắt buộc)
  - Email (bắt buộc, unique)
  - Mật khẩu (bắt buộc khi tạo mới, tối thiểu 8 ký tự)
  - Xác nhận mật khẩu
- **Thông tin chi tiết**:
  - Vai trò (User/Admin)
  - Ngày sinh
  - Giới tính (Nam/Nữ/Khác)
  - Địa chỉ
  - Trạng thái xác thực email

#### Chỉnh sửa User
- Cập nhật tất cả thông tin user
- Mật khẩu có thể để trống (không thay đổi)
- Validation đầy đủ cho email unique

#### Xem chi tiết User
- Hiển thị đầy đủ thông tin user
- Giao diện đẹp với badges và icons
- Thông tin hệ thống (ngày tạo, cập nhật)

#### Xóa User
- **Bảo vệ**: Không cho phép xóa chính mình
- **Kiểm tra ràng buộc**: Không xóa user có đơn hàng
- **Xác nhận**: Modal xác nhận trước khi xóa

#### Toggle trạng thái
- Thay đổi trạng thái xác thực email
- Không cho phép thay đổi trạng thái chính mình
- Thông báo kết quả

### 2. Bảo mật và Validation

#### Validation Rules
```php
'name' => 'required|string|max:255'
'email' => 'required|email|unique:users,email|max:255'
'password' => 'required|string|min:8|confirmed' // Khi tạo mới
'role' => 'required|in:user,admin'
'date_of_birth' => 'nullable|date|before:today'
'gender' => 'nullable|in:male,female,other'
'address' => 'nullable|string|max:500'
```

#### Bảo mật
- Mật khẩu được hash bằng bcrypt
- Kiểm tra quyền trước khi thực hiện các hành động
- Validation email unique
- Xác nhận trước khi xóa

### 3. Giao diện người dùng

#### Navigation
- **Icon**: Users icon từ Heroicon
- **Group**: "Quản lý người dùng"
- **Label**: "Người dùng"
- **Sort**: Ưu tiên hiển thị đầu tiên

#### Responsive Design
- Grid layout cho form
- Collapsible sections
- Mobile-friendly

#### Visual Feedback
- **Badges**: Màu sắc khác nhau cho vai trò, giới tính
- **Icons**: Check/x-circle cho trạng thái
- **Notifications**: Thông báo thành công/lỗi
- **Loading states**: Khi thực hiện actions

## Cấu trúc File

```
app/Filament/Resources/Users/
├── UserResource.php          # Resource chính
├── Pages/                    # Các trang
│   ├── ListUsers.php
│   ├── CreateUser.php
│   ├── EditUser.php
│   └── ViewUser.php
├── Schemas/                  # Schema cho form và infolist
│   ├── UserForm.php
│   └── UserInfolist.php
└── Tables/                   # Cấu hình table
    └── UsersTable.php
```

## Cách sử dụng

### 1. Truy cập Admin Panel
```
http://localhost:8000/admin
```

### 2. Đăng nhập
- Sử dụng tài khoản admin đã có
- Hoặc tạo tài khoản admin mới

### 3. Quản lý User
- Vào menu "Quản lý người dùng" > "Người dùng"
- Thực hiện các thao tác CRUD

### 4. Tạo User mới
- Click "Tạo mới"
- Điền thông tin bắt buộc
- Chọn vai trò phù hợp
- Lưu

### 5. Chỉnh sửa User
- Click "Sửa" trên user cần chỉnh sửa
- Cập nhật thông tin
- Lưu thay đổi

### 6. Xóa User
- Click "Xóa" trên user cần xóa
- Xác nhận trong modal
- Kiểm tra ràng buộc trước khi xóa

## Tùy chỉnh

### Thêm trường mới
1. Cập nhật migration nếu cần
2. Thêm vào UserForm.php
3. Thêm vào UsersTable.php
4. Thêm vào UserInfolist.php

### Thay đổi validation
1. Cập nhật trong UserForm.php
2. Thêm custom validation rules

### Thay đổi giao diện
1. Tùy chỉnh trong các file Schema
2. Thay đổi colors, icons, layout

## Lưu ý quan trọng

1. **Backup dữ liệu** trước khi thực hiện thay đổi lớn
2. **Test kỹ** các tính năng mới
3. **Kiểm tra ràng buộc** trước khi xóa user
4. **Bảo mật** thông tin nhạy cảm
5. **Log** các hành động quan trọng

## Troubleshooting

### Lỗi thường gặp
1. **Email duplicate**: Kiểm tra validation unique
2. **Password validation**: Đảm bảo đủ 8 ký tự
3. **Permission denied**: Kiểm tra quyền admin
4. **Database errors**: Kiểm tra migration và seeder

### Debug
1. Kiểm tra Laravel logs
2. Sử dụng `php artisan tinker`
3. Kiểm tra database trực tiếp
4. Xem Filament debug bar
