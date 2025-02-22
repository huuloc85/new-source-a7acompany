# API Documentation - Xác thực và Quản lý người dùng

## Base URL

```
[domain]/api
```

## Endpoints

### 1. Đăng nhập

**POST** `/login`

**Request Body:**

```json
{
    "phone": "string",
    "password": "string",
    "expiresInMins": "number (optional, default: 60)"
}
```

**Response Success: (200)**

```json
{
    "role_id": "number",
    "token": "string",
    "is_birthday": "boolean",
    "birthday_employees": ["string"],
    "cleaning_duties": [
        {
            "date": "YYYY-MM-DD",
            "type": "string"
        }
    ],
    "expires_at": "YYYY-MM-DD HH:mm:ss"
}
```

**Response Error: (401)**

```json
{
    "message": "Số điện thoại hoặc mật khẩu không đúng!"
}
```

**Response Error: (403)**

```json
{
    "message": "Bạn đã nghỉ việc!"
}
```

### 2. Đăng xuất

**POST** `/logout`

**Headers:**

```
Authorization: Bearer {token}
```

**Response Success: (200)**

```json
{
    "message": "Đăng xuất thành công"
}
```

### 3. Lấy thông tin người dùng đang đăng nhập

**GET** `/me` hoặc `/profile`

**Headers:**

```
Authorization: Bearer {token}
```

**Response Success: (200)**

```json
{
    "user": {
        "id": "number",
        "name": "string",
        "phone": "string",
        "email": "string",
        "role_id": "number"
        // và các thông tin khác của user
    }
}
```

**Response Error: (401)**

```json
{
    "message": "Người dùng chưa đăng nhập!"
}
```

### 4. Cập nhật thông tin cá nhân (Dành cho nhân viên)

**PATCH** `/change-info`

**Headers:**

```
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "name": "string",
    "phone": "string"
}
```

**Response Success: (200)**

```json
{
    "message": "Cập nhật thông tin cá nhân thành công!",
    "user": {
        "id": "number",
        "name": "string",
        "phone": "string"
        // và các thông tin khác của user
    }
}
```

### 5. Cập nhật thông tin nhân viên (Dành cho Admin)

**PATCH** `/change-profile/{id}`

**Headers:**

```
Authorization: Bearer {token}
```

**Request Body:**

```json
{
    "name": "string",
    "phone": "string",
    "email": "string",
    "role": "number"
}
```

**Response Success: (200)**

```json
{
    "message": "Cập nhật thông tin thành công!",
    "user": {
        "id": "number",
        "name": "string",
        "phone": "string",
        "email": "string",
        "role": "number"
        // và các thông tin khác của user
    }
}
```

**Response Error: (403)**

```json
{
    "message": "Bạn không có quyền thực hiện thao tác này!"
}
```

**Response Error: (404)**

```json
{
    "message": "Người dùng không tồn tại!"
}
```

## Lưu ý

1. Tất cả các endpoints (trừ `/login`) đều yêu cầu token xác thực trong header
2. Token có thời hạn mặc định là 60 phút, có thể điều chỉnh thông qua tham số `expiresInMins` khi đăng nhập
3. Admin được xác định bằng `role_id = 15`
4. Khi đăng nhập thành công, hệ thống sẽ trả về:
    - Thông tin sinh nhật của nhân viên trong ngày
    - Lịch trực sắp tới (trong 3 ngày) của nhân viên đăng nhập
