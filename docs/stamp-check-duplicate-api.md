# API Check Duplicate Stamps - Documentation

## Tổng quan

API endpoint mới để kiểm tra tem trùng lặp trước khi in. Được sử dụng khi người dùng bấm nút **IN** trên trang lịch sử in tem.

---

## Endpoint

### POST `/api/stamps/check-duplicate`

**Authentication:** Required (Bearer Token)

**Middleware:** `api.check.qa.qc`

---

## Request

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
```

### Body Parameters

| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `product_id` | string | ✅ | ID của sản phẩm | `"16032400"` |
| `date` | string | ✅ | Ngày in (format: YYYY-MM-DD) | `"2026-01-06"` |
| `shift` | string | ✅ | Ca làm việc | `"1"` hoặc `"2"` |
| `binStart` | string | ✅ | Tem bắt đầu (số đơn hoặc nhiều số cách nhau bằng dấu phẩy) | `"1"` hoặc `"1,2,3,5"` |
| `binCount` | number | ✅ | Số lượng tem | `3` |
| `type` | string | ✅ | Loại tem | `"box"` hoặc `"bag"` |

### Request Example

```json
{
  "product_id": "16032400",
  "date": "2026-01-06",
  "shift": "1",
  "binStart": "3",
  "binCount": 3,
  "type": "box"
}
```

---

## Response

### Success Response (200 OK)

#### Trường hợp KHÔNG có tem trùng

```json
{
  "isDuplicate": false,
  "duplicates": [],
  "message": "Không có tem trùng lặp"
}
```

#### Trường hợp CÓ tem trùng

```json
{
  "isDuplicate": true,
  "duplicates": [
    {
      "id": 123,
      "binStart": "1",
      "binCount": 5,
      "overlappingStamps": [3, 4, 5]
    },
    {
      "id": 456,
      "binStart": "10,11,15",
      "binCount": 3,
      "overlappingStamps": [10]
    }
  ],
  "message": "Phát hiện tem trùng lặp"
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `isDuplicate` | boolean | `true` nếu phát hiện tem trùng, `false` nếu không |
| `duplicates` | array | Danh sách các bản ghi tem bị trùng (rỗng nếu không có) |
| `duplicates[].id` | number | ID của bản ghi tem đã in trước đó |
| `duplicates[].binStart` | string | binStart của bản ghi đó |
| `duplicates[].binCount` | number | binCount của bản ghi đó |
| `duplicates[].overlappingStamps` | array | Mảng các số tem bị trùng lặp |
| `message` | string | Thông báo mô tả kết quả |

---

## Error Response

### 400 Bad Request - Validation Error

```json
{
  "error": "Validation Error",
  "message": "Missing required fields",
  "details": {
    "product_id": ["The product id field is required."],
    "date": ["The date field is required."]
  }
}
```

### 500 Internal Server Error

```json
{
  "error": "Server Error",
  "message": "Error checking duplicate stamps"
}
```

---

## Logic Xử Lý

### 1. Query Điều Kiện

API chỉ kiểm tra với các bản ghi trong database có:
- ✅ `product_id` = product_id request
- ✅ `date` = date request
- ✅ `shift` = shift request
- ✅ `type` = type request
- ✅ `status` = `"approve"` (đã được duyệt)
- ✅ `purpose` = `"new"` (in mới)

### 2. Tính Range Tem

#### Case 1: binStart là số đơn
```
Input:  binStart = "5", binCount = 3
Output: [5, 6, 7]
```

#### Case 2: binStart có dấu phẩy (tem không liên tục)
```
Input:  binStart = "1,3,5,7", binCount = 4
Output: [1, 3, 5, 7]
```

### 3. So Sánh Overlap

API so sánh range tem của request với từng range tem đã in:
- Nếu có bất kỳ số tem nào trùng nhau → Thêm vào `duplicates`
- Trả về tất cả các bản ghi và các số tem bị trùng

---

## Ví Dụ Chi Tiết

### Scenario 1: Tem Liên Tục - CÓ TRÙNG

**Database có sẵn:**
```
Record ID: 100
- product_id: "16032400"
- date: "2026-01-06"
- shift: "1"
- binStart: "1"
- binCount: 5
- status: "approve"
- purpose: "new"
→ Tem đã in: [1, 2, 3, 4, 5]
```

**Request:**
```json
{
  "product_id": "16032400",
  "date": "2026-01-06",
  "shift": "1",
  "binStart": "3",
  "binCount": 3,
  "type": "box"
}
```
→ Tem muốn in: [3, 4, 5]

**Response:**
```json
{
  "isDuplicate": true,
  "duplicates": [
    {
      "id": 100,
      "binStart": "1",
      "binCount": 5,
      "overlappingStamps": [3, 4, 5]
    }
  ],
  "message": "Phát hiện tem trùng lặp"
}
```

---

### Scenario 2: Tem Không Liên Tục - CÓ TRÙNG 1 PHẦN

**Database có sẵn:**
```
Record ID: 200
- binStart: "1,3,5,7,9"
- binCount: 5
→ Tem đã in: [1, 3, 5, 7, 9]
```

**Request:**
```json
{
  "binStart": "2,3,4",
  "binCount": 3
}
```
→ Tem muốn in: [2, 3, 4]

**Response:**
```json
{
  "isDuplicate": true,
  "duplicates": [
    {
      "id": 200,
      "binStart": "1,3,5,7,9",
      "binCount": 5,
      "overlappingStamps": [3]
    }
  ],
  "message": "Phát hiện tem trùng lặp"
}
```

---

### Scenario 3: KHÔNG CÓ TRÙNG

**Database có sẵn:**
```
Record ID: 300
- binStart: "1"
- binCount: 3
→ Tem đã in: [1, 2, 3]
```

**Request:**
```json
{
  "binStart": "5",
  "binCount": 2
}
```
→ Tem muốn in: [5, 6]

**Response:**
```json
{
  "isDuplicate": false,
  "duplicates": [],
  "message": "Không có tem trùng lặp"
}
```

---

### Scenario 4: NHIỀU Bản Ghi Trùng

**Database có sẵn:**
```
Record 1: binStart="1", binCount=5 → [1,2,3,4,5]
Record 2: binStart="8,9,10", binCount=3 → [8,9,10]
```

**Request:**
```json
{
  "binStart": "3,4,9",
  "binCount": 3
}
```
→ Tem muốn in: [3, 4, 9]

**Response:**
```json
{
  "isDuplicate": true,
  "duplicates": [
    {
      "id": 1,
      "binStart": "1",
      "binCount": 5,
      "overlappingStamps": [3, 4]
    },
    {
      "id": 2,
      "binStart": "8,9,10",
      "binCount": 3,
      "overlappingStamps": [9]
    }
  ],
  "message": "Phát hiện tem trùng lặp"
}
```

---

## Cách Sử Dụng Từ Frontend

### Flow Recommend

```javascript
// 1. Khi user click nút "IN"
const checkDuplicate = async (stampData) => {
  try {
    const response = await axios.post('/api/stamps/check-duplicate', {
      product_id: stampData.product_id,
      date: stampData.date,
      shift: stampData.shift,
      binStart: stampData.binStart,
      binCount: stampData.binCount,
      type: stampData.type
    });

    if (response.data.isDuplicate) {
      // 2. Hiển thị warning cho user
      const overlappingStamps = response.data.duplicates
        .flatMap(d => d.overlappingStamps)
        .join(', ');
      
      const confirmed = await showConfirmDialog({
        title: 'Cảnh báo tem trùng lặp',
        message: `Các tem sau bị trùng: ${overlappingStamps}\nBạn có chắc muốn tiếp tục?`,
        details: response.data.duplicates
      });

      if (!confirmed) {
        return; // User cancel
      }
    }

    // 3. Nếu không trùng HOẶC user confirm → Tiếp tục in
    await printStamps(stampData);
    
  } catch (error) {
    console.error('Error checking duplicate:', error);
    // Handle error
  }
};
```

---

## Notes

### Performance
- API đã được tối ưu với query điều kiện cụ thể
- Recommend: Thêm index cho columns: `product_id`, `date`, `shift`, `status`, `purpose`

### Validation Rules
- `binStart`: Chỉ chấp nhận số và dấu phẩy (regex: `/^[0-9]+(,[0-9]+)*$/`)
- `shift`: Chỉ chấp nhận "1" hoặc "2"
- `type`: Chỉ chấp nhận "box" hoặc "bag"
- `date`: Format YYYY-MM-DD
- `binCount`: Số nguyên dương >= 1

### Edge Cases Đã Xử Lý
- ✅ binStart có spaces (sẽ tự động trim)
- ✅ binCount lớn hơn số lượng tem trong binStart (chỉ lấy đủ số lượng)
- ✅ Không có bản ghi nào trong DB (trả về không trùng)
- ✅ Nhiều bản ghi trùng cùng lúc (trả về tất cả)

---

## Changelog

### Version 1.0.0 (2026-01-06)
- ✅ Initial release
- ✅ Support continuous stamps (`binStart` = single number)
- ✅ Support non-continuous stamps (`binStart` with commas)
- ✅ Check against `status='approve'` and `purpose='new'` records only
- ✅ Full error handling and validation
- ✅ Detailed overlap detection

---

## Testing

### Test Cases Đề Xuất

```javascript
// Test 1: Không có duplicate
POST /api/stamps/check-duplicate
{
  "product_id": "TEST001",
  "date": "2026-01-06",
  "shift": "1",
  "binStart": "100",
  "binCount": 5,
  "type": "box"
}
// Expected: isDuplicate = false

// Test 2: Có duplicate - tem liên tục
// (Cần setup: Đã có record với binStart="1", binCount=10)
POST /api/stamps/check-duplicate
{
  "product_id": "TEST001",
  "date": "2026-01-06",
  "shift": "1",
  "binStart": "5",
  "binCount": 3,
  "type": "box"
}
// Expected: isDuplicate = true, overlappingStamps = [5,6,7]

// Test 3: Có duplicate - tem không liên tục
POST /api/stamps/check-duplicate
{
  "binStart": "1,3,5",
  "binCount": 3
}
// Expected: Check overlap với records có binStart chứa dấu phẩy

// Test 4: Validation error
POST /api/stamps/check-duplicate
{
  "product_id": "TEST001"
  // Missing required fields
}
// Expected: 400 Bad Request
```

---

## Contact

Nếu có vấn đề hoặc câu hỏi, vui lòng liên hệ Backend team.

**API Version:** 1.0.0  
**Last Updated:** 2026-01-06
