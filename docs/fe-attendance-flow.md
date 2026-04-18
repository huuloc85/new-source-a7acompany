# FE Attendance Flow (Read-Only tu attendance_records)

## 1) Muc tieu
Database da co san du lieu trong `attendance_records`, vi vay FE chi can goi API de load du lieu cham cong xuong hien thi.

Khong dung:
- `POST /api/admin/salary-web/{salaryManagerId}/sync-attendance`
- Job `SyncAttendanceJob`
- Queue/polling cho flow dong bo

## 2) Endpoint FE nen dung

### 2.1 API chinh de lay du lieu cham cong
`GET /api/attendances/calculate`

API nay doc du lieu tu:
- `schedule_details`
- `attendance_records`
- `employees`

Va tra ve ket qua da tinh san:
- `shift`
- `time_in`
- `time_out`
- `total_hours`
- `overtime_hours`
- `administrative_hours`

## 3) Query params can dung
- `filter[date_between]`: `YYYY-MM-DD,YYYY-MM-DD`
- `filter[employees.company]`: `a7a|vvp`
- `filter[employee_id]` (optional)
- `filter[employees.name]` (optional)
- `filter[employees.calendar_category_id]` (optional)
- `page` (default `1`)
- `limit` (default `15`, dat `0` de lay tat ca)

Vi du:
```http
GET /api/attendances/calculate?filter[date_between]=2026-04-01,2026-04-30&filter[employees.company]=vvp&limit=0
```

## 4) Cau truc response FE can doc
Response la paginator Laravel:
- `current_page`
- `per_page`
- `total`
- `last_page`
- `data[]`

Moi item trong `data[]`:
- `employee_id`
- `name`
- `company`
- `date`
- `shift` (`1` ca ngay, `2` ca dem)
- `hnhc`
- `day_type`
- `is_schedule_change`
- `time_in`
- `time_out`
- `total_hours`
- `overtime_hours`
- `administrative_hours`

## 5) FE flow de xai
1. User chon `company`, `start_date`, `end_date`.
2. FE goi `GET /api/attendances/calculate` voi `filter[date_between]` + `filter[employees.company]`.
3. Render `data[]` len bang cham cong.
4. Neu user doi filter (ngay, ten, nhan vien) thi goi lai API va render lai.

Flow nay la read-only truc tiep tu du lieu cham cong da ton tai, khong co buoc dong bo nen.

## 6) Error handling
- `401`/`419`: het phien dang nhap
- `403`: thieu quyen `view_attendance`
- `422`: query filter khong hop le
- `500`: loi he thong

## 7) Mau code FE (TypeScript)
```ts
type Company = "a7a" | "vvp";

export async function fetchAttendanceCalculated(params: {
  company: Company;
  startDate: string; // YYYY-MM-DD
  endDate: string;   // YYYY-MM-DD
  page?: number;
  limit?: number;
}, token: string) {
  const q = new URLSearchParams();
  q.set("filter[date_between]", `${params.startDate},${params.endDate}`);
  q.set("filter[employees.company]", params.company);
  q.set("page", String(params.page ?? 1));
  q.set("limit", String(params.limit ?? 0));

  const res = await fetch(`/api/attendances/calculate?${q.toString()}`, {
    headers: { Authorization: `Bearer ${token}` },
  });

  if (!res.ok) throw await res.json();
  return res.json();
}
```

## 8) Ghi chu
- API co co che mo rong range (+/-2 ngay) de detect ca dem/chuyen ca, sau do filter lai dung khoang ngay FE request.
- Neu FE chi can xem cham cong, chi can endpoint `GET /api/attendances/calculate` la du.
- Neu FE can tu tinh bang luong roi luu vao BE, xem them: `docs/fe-salary-web-fe-driven-map.md`.
