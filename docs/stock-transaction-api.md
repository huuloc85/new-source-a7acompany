# API Stock Transaction - Hướng dẫn sử dụng

## Tổng quan

API Stock Transaction cung cấp các endpoint để quản lý giao dịch nhập/xuất kho thông qua hệ thống barcode. System hỗ trợ scan barcode để tự động nhập/xuất kho với bin system.

## Format Barcode

Barcode format: `{product_id}{separator}{ddmmyyyy}{shift}{bin_number}`

**Ví dụ:** `39a101020251006`

- `39`: Product ID
- `a`: Separator (ký tự phân cách)
- `10102025`: Ngày tháng (10/10/2025)
- `1`: Ca làm việc (1-9)
- `006`: Bin number (số thứ tự thùng - 3 chữ số)

## Lot & Bin System

- **Lot Code:** `A-10102025-1` (format: `{SEPARATOR}-{DDMMYYYY}-{SHIFT}`)
- **Bin:** `6` (chỉ lưu số thứ tự, không lưu full bin code)
- **Bin Code:** `A-10102025-1-006` (chỉ dùng để hiển thị)

---

## Error Responses

Tất cả APIs đều trả về response với format chuẩn:

### Success Response Format

```json
{
    "success": true,
    "message": "Thông báo thành công",
    "data": {
        /* Dữ liệu response */
    }
}
```

### Error Response Format

```json
{
    "success": false,
    "message": "Thông báo lỗi",
    "error": "Chi tiết lỗi (optional)"
}
```

### Common Error Cases

**1. Validation Error (422)**

```json
{
    "success": false,
    "message": "Format barcode không đúng. Định dạng: [product_id][separator][ddmmyyyy][shift][bin_number]",
    "example": "39a101020251001"
}
```

**2. Product Not Found (404)**

```json
{
    "success": false,
    "message": "Không tìm thấy sản phẩm với ID: 39"
}
```

**3. Access Denied (403)**

```json
{
    "success": false,
    "message": "Bạn không có quyền truy cập!"
}
```

**4. Bin Already Exists (422)**

```json
{
    "success": false,
    "message": "Thùng này đã được nhập kho trước đó"
}
```

**5. Insufficient Stock (422)**

```json
{
    "success": false,
    "message": "Số lượng xuất kho vượt quá số lượng hiện có",
    "data": {
        "available_quantity": 10,
        "requested_quantity": 28
    }
}
```

**6. Server Error (500)**

```json
{
    "success": false,
    "message": "Lỗi khi nhập kho",
    "error": "Database connection failed"
}
```

---

## Frontend Implementation Guide

### Handling API Response

```typescript
// ✅ ĐÚNG - Check success field trước
async function callAPI(barcode: string) {
    try {
        const response = await fetch("/api/stock-transactions/scan-in", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ barcode }),
        })

        const data = await response.json()

        if (data.success) {
            // Handle success
            console.log("Success:", data.message)
            const transaction = data.data // Transaction object here
            return transaction
        } else {
            // Handle error
            console.error("Error:", data.message)
            throw new Error(data.message)
        }
    } catch (error) {
        console.error("Network error:", error)
        throw error
    }
}
```

```typescript
// ❌ SAI - Không check success field
async function callAPIWrong(barcode: string) {
    const response = await fetch("/api/stock-transactions/scan-in", {
        method: "POST",
        body: JSON.stringify({ barcode }),
    })

    const data = await response.json()
    return data // Có thể là error object thay vì transaction!
}
```

---

## Endpoints

### 1. Scan Barcode để Nhập Kho (Tự động)

**Endpoint:** `POST /api/stock-transactions/scan-in`

**Mô tả:** Scan barcode và tự động nhập kho với số lượng = `quanEntityBin` của sản phẩm. Employee ID được lấy từ user đang login.

**Request Body:**

```json
{
    "barcode": "39a101020251006"
}
```

**Response Success:**

```json
{
    "success": true,
    "message": "Nhập kho thành công",
    "data": {
        "id": 1,
        "storage_product_id": 10,
        "type": "in",
        "quantity": 28,
        "employee_id": 15,
        "created_at": "2025-10-17T08:41:45.000000Z",
        "updated_at": "2025-10-17T08:41:45.000000Z",
        "storage_product": {
            "id": 10,
            "product_id": 39,
            "lot": "A-17102025-1",
            "bin": 6,
            "quantity": 28,
            "barcode": "39a171020251006",
            "product": {
                "id": 39,
                "code": "P001",
                "name": "Sản phẩm test"
            }
        },
        "employee": {
            "id": 15,
            "name": "Nguyễn Huỳnh Phúc Hữu Lộc"
        }
    }
}
```

---

### 2. Scan Barcode để Xuất Kho

**Endpoint:** `POST /api/stock-transactions/scan-out`

**Mô tả:** Scan barcode và xuất toàn bộ số lượng của thùng đó (1 thùng = toàn bộ quantity của bin). Employee ID được lấy từ user đang login. **Lưu ý quan trọng:** Khi xuất hết hàng, bin sẽ có quantity = 0 nhưng vẫn được giữ lại trong database để theo dõi history.

**Request Body:**

```json
{
    "barcode": "39a101020251006"
}
```

**Response Success:**

```json
{
    "success": true,
    "message": "Xuất kho thành công",
    "data": {
        "id": 2,
        "storage_product_id": 10,
        "type": "out",
        "quantity": 28,
        "employee_id": 15,
        "created_at": "2025-10-17T08:45:30.000000Z",
        "updated_at": "2025-10-17T08:45:30.000000Z",
        "storage_product": {
            "id": 10,
            "product_id": 39,
            "lot": "A-17102025-1",
            "bin": 6,
            "quantity": 0,
            "barcode": "39a171020251006",
            "product": {
                "id": 39,
                "code": "P001",
                "name": "Sản phẩm test"
            }
        },
        "employee": {
            "id": 15,
            "name": "Nguyễn Huỳnh Phúc Hữu Lộc"
        }
    }
}
```

**Lưu ý:** Xuất 1 thùng = xuất toàn bộ 28 sản phẩm (hoặc `quanEntityBin` của product đó).

---

### 3. Scan Barcode (Chỉ kiểm tra)

**Endpoint:** `POST /api/stock-transactions/scan`

**Mô tả:** Scan barcode để kiểm tra thông tin, không thực hiện giao dịch.

**Request Body:**

```json
{
    "barcode": "39a101020251006"
}
```

**Response Success:**

```json
{
    "success": true,
    "message": "Scan barcode thành công",
    "data": {
        "storage_product": {
            "id": 10,
            "product_id": 39,
            "lot": "A-17102025-1",
            "bin": 6,
            "quantity": 28,
            "barcode": "39a171020251006",
            "product": {
                "id": 39,
                "code": "P001",
                "name": "Sản phẩm test"
            }
        },
        "barcode_info": {
            "product_id": 39,
            "date": "17102025",
            "shift": 1,
            "bin_number": 6,
            "lot_code": "A-17102025-1"
        },
        "quantity_per_bin": 28,
        "total_bins": 5
    }
}
```

---

### 4. Danh sách Giao dịch

**Endpoint:** `GET /api/stock-transactions`

**Query Parameters:**

- `type`: `in` hoặc `out`
- `storage_product_id`: ID của storage product
- `employee_id`: ID của employee
- `from_date`: Từ ngày (YYYY-MM-DD)
- `to_date`: Đến ngày (YYYY-MM-DD)

- `per_page`: Số record mỗi trang (default: 15)

**Example:**

```
GET /api/stock-transactions?type=in&from_date=2025-10-01&per_page=20
```

---

### 7. Thống kê Giao dịch và Tồn kho

**Endpoint:** `GET /api/stock-transactions/statistics`

**Mô tả:** Lấy thống kê giao dịch và tình trạng tồn kho hiện tại. API này trả về data phù hợp cho dashboard statistics.

**Query Parameters:**

- `from_date`: Từ ngày (YYYY-MM-DD) - optional
- `to_date`: Đến ngày (YYYY-MM-DD) - optional
- `limit`: Số lượng top products tối đa (default: 50)

**Response:**

```json
{
    "success": true,
    "message": "Thống kê giao dịch kho",
    "data": {
        "summary": {
            "total_in": 1500,
            "total_out": 800,
            "net_change": 700,
            "total_transactions": 45
        },
        "top_products": [
            {
                "storage_product_id": 1,
                "total_quantity": 280,
                "transaction_count": 10,
                "storage_product": {
                    "id": 1,
                    "product_id": 39,
                    "lot": "A-17102025-1",
                    "bin": 1,
                    "quantity": 28,
                    "barcode": "39a171020251001",
                    "product": {
                        "id": 39,
                        "code": "P001",
                        "name": "Sản phẩm test"
                    }
                }
            }
        ]
    }
}
```

---

### 8. Chi tiết Giao dịch

**Endpoint:** `GET /api/stock-transactions/{id}`

---

### 9. Thống kê Giao dịch

**Endpoint:** `GET /api/stock-transactions/statistics`

**Query Parameters:**

- `from_date`: Từ ngày
- `to_date`: Đến ngày
- `limit`: Số lượng top products (default: 50)

**Response:**

```json
{
    "success": true,
    "data": {
        "summary": {
            "total_in": 1500,
            "total_out": 800,
            "net_change": 700,
            "total_transactions": 45
        },
        "top_products": [...]
    }
}
```

---

## Error Responses

### Barcode Format Error

```json
{
    "success": false,
    "message": "Format barcode không đúng. Định dạng: [product_id][separator][ddmmyyyy][shift][bin_number]",
    "example": "39a101020251001"
}
```

### Product Not Found

```json
{
    "success": false,
    "message": "Không tìm thấy sản phẩm với ID: 39"
}
```

### Bin Already Exists

```json
{
    "success": false,
    "message": "Thùng này đã được nhập kho trước đó",
    "data": {
        "existing_storage": {...},
        "barcode_info": {...}
    }
}
```

### Insufficient Quantity

```json
{
    "success": false,
    "message": "Số lượng xuất kho vượt quá số lượng hiện có",
    "data": {
        "available_quantity": 10,
        "requested_quantity": 20
    }
}
```

---

## Workflow Sử dụng

### 1. Nhập Kho

1. User đăng nhập vào hệ thống
2. Scan barcode sản phẩm: `39a101020251006`
3. Call API `POST /api/stock-transactions/scan-in`
4. System tự động:
    - Parse barcode → product_id=39, bin=6, lot=A-10102025-1
    - Tìm/tạo product
    - Tạo storage product với bin=6 (integer)
    - Nhập số lượng cố định = `quanEntityBin` (ví dụ: 28 sản phẩm)
    - Tạo transaction record với employee_id từ auth()->user()->id
    - **Lưu ý:** Không thể tùy chỉnh quantity, luôn nhập đúng 1 thùng, không cần note

### 2. Xuất Kho

1. Scan barcode của thùng cần xuất: `39a101020251006`
2. Call API `POST /api/stock-transactions/scan-out`
3. System tự động xuất toàn bộ số lượng của thùng (28 sản phẩm)
4. Remaining quantity = 0 (thùng rỗng)
5. **Lưu ý:** Luôn xuất cả thùng, không thể xuất từng phần, không cần note

### 3. Kiểm tra Tồn kho

1. Scan barcode: `39a101020251006`
2. Call API `POST /api/stock-transactions/scan`
3. Xem thông tin tồn kho hiện tại của bin đó

---

## Lưu ý quan trọng

1. **Bin Number:** Chỉ lưu số thứ tự (6) chứ không lưu full bin code (A-10102025-1-006)
2. **Lot System:** Mỗi lot đại diện cho một ca sản xuất trong ngày
3. **Unique Constraint:** Mỗi combination (product_id, lot, bin) là duy nhất
4. **Fixed Quantity:**
    - **Nhập kho:** Luôn nhập đúng 1 thùng = `quanEntityBin` của product
    - **Xuất kho:** Luôn xuất cả thùng, không thể xuất từng phần
    - **Database History:** Khi xuất hết (quantity = 0), bin vẫn được giữ lại để theo dõi history
5. **Transaction Log:** Tất cả giao dịch đều được ghi log đầy đủ
6. **Employee ID:** Tự động lấy từ `auth()->user()->id`, không cần truyền trong request
7. **Business Logic:** 1 barcode scan = 1 thùng hoàn chỉnh (in/out)

---

## Authentication

Tất cả các API đều yêu cầu user đăng nhập. Employee ID sẽ được tự động lấy từ user đang login thông qua `auth()->user()->id`.

---

## Testing

### Test với cURL:

```bash
# Nhập kho (cần authenticate token)
curl -X POST http://localhost:8000/api/stock-transactions/scan-in \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"barcode": "39a101020251006"}'

# Xuất kho cả thùng
curl -X POST http://localhost:8000/api/stock-transactions/scan-out \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"barcode": "39a101020251006"}'

# Kiểm tra thông tin bin
curl -X POST http://localhost:8000/api/stock-transactions/scan \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"barcode": "39a101020251006"}'
```

### Test với PowerShell:

```powershell
$headers = @{
    "Authorization" = "Bearer YOUR_TOKEN"
    "Content-Type" = "application/json"
}

$body = @{
    barcode = "39a101020251006"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/stock-transactions/scan-in" `
  -Method POST -Body $body -Headers $headers
```
