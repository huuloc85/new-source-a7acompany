# FE Salary Web Flow (FE Tinh, BE Chi Map/Luu)

## Muc tieu
FE tu tinh bang luong, BE khong tinh lai cong thuc.
BE chi nhan payload FE da tinh san va map vao cac cot salary/timekeeping/payroll trong database.

## Endpoint

`POST /api/admin/salary-web/{salaryManagerId}/calculate`

## Co 2 mode

1. `be-calculate`
- FE chi gui:
  - `company`
  - `employee_ids` (optional)
- BE tu tinh (flow cu).

2. `fe-driven-map`
- FE gui `calculated_data` => BE chi map/lưu.

## Payload FE-driven (bulk)

```json
{
  "company": "a7a",
  "calculated_data": [
    {
      "employee_id": "1001",
      "employee_type": "worker",
      "timekeeping_summary": {
        "total_day_hours": 186,
        "total_night_hours": 12,
        "total_overtime_hours": 18
      },
      "timekeeping_daily": [
        {
          "date": "2026-04-01",
          "day_hours": 8,
          "night_hours": 0,
          "overtime_hours": 2
        }
      ],
      "calculation_detail": {
        "trial_section": {},
        "official_section": {},
        "allowance_section": {},
        "summary": {},
        "kpi_detail": {}
      },
      "payroll": {
        "salary_total": 12000000,
        "insurance_payroll": 800000,
        "advance_money_payroll": 1000000,
        "company_insurance_payroll": 1700000,
        "KPI_Subtraction_payroll": 0,
        "previous_period_debt_payroll": 0,
        "actually_received_payroll": 10200000
      }
    }
  ]
}
```

## Luu y map nhanh

- FE co the gui them:
  - `salary_fields` (flat field -> value)
  - `sheet_all_columns` (flat field -> value)
- BE se chi nhan cac key co trong `fillable` model salary.
- BE chan update `salaries_manager_id`, `employee_id`.

## API single employee

`POST /api/admin/salary-web/{salaryManagerId}/calculate/{employeeId}`

- Neu payload co cac block:
  - `timekeeping_summary`, `timekeeping_daily`, `calculation_detail`, `payroll`, `salary_fields`, `sheet_all_columns`
- Thi API se chay mode `fe-driven-map`.

