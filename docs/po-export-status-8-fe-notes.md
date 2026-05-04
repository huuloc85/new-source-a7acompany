# PO export status 8 migration notes for FE

## Backend change summary

- Export quantity for PO no longer uses `total_month_quantities.status = 3`.
- Monthly export quantity now comes from `total_daily_quantities_po.status = 8`.
- `update-stock-quantity:cron` now calculates beginning inventory from PO export status 8.
- `GET /api/quantities/monthly?status=8` now returns monthly export totals aggregated from `total_daily_quantities_po`.
- Product detail now returns PO export records as `status8`; old `status3` is no longer returned by the detail endpoint.

## Status mapping

| Status | Meaning                  | Source table                                                           |
| ------ | ------------------------ | ---------------------------------------------------------------------- |
| `1`    | Production 100%          | `daily_quantities`, `total_daily_quantities`, `total_month_quantities` |
| `2`    | Checked/import 200%      | `daily_quantities`, `total_daily_quantities`, `total_month_quantities` |
| `3`    | Old export quantity      | Deprecated for PO export UI                                            |
| `4`    | Beginning inventory      | `total_month_quantities`                                               |
| `5`    | Beginning inventory 200% | `total_month_quantities`                                               |
| `6`    | Error quantity 200%      | `daily_quantities`, `total_daily_quantities`, `total_month_quantities` |
| `7`    | Monthly MOQ              | `total_month_quantities`                                               |
| `8`    | PO export quantity       | `daily_quantity_po`, `total_daily_quantities_po`                       |

## API usage for FE

### Get monthly PO export total

Use status 8:

```http
GET /api/quantities/monthly?status=8&month=05-2026&include=product&limit=0
```

Response shape follows the existing paginator shape. Each item has:

```json
{
    "product_id": 1,
    "status": 8,
    "month": "05-2026",
    "totalQuan": "12345",
    "product": {}
}
```

Notes:

- `id` is not guaranteed for status 8 rows because they are aggregated from daily PO totals.
- Do not call monthly update APIs to edit status 8 totals directly.
- To create PO export data, use the Check PO export API.

### Create PO export quantity

Use:

```http
POST /api/check-po/export
```

Payload can be single-date:

```json
{
    "date": "2026-05-04",
    "fileName": "po-file.xlsx",
    "note": "optional",
    "products": [{ "productId": 1, "quantity": 100 }]
}
```

Or batch import:

```json
{
    "batches": [
        {
            "date": "2026-05-04",
            "fileName": "po-file.xlsx",
            "note": "optional",
            "products": [{ "productId": 1, "quantity": 100 }]
        }
    ]
}
```

### Product detail drawer

Use the existing product detail endpoint:

```http
GET /api/products/detail/{productId}?month=05-2026
```

For PO export rows, read:

```json
{
    "status8": [
        {
            "id": 10,
            "product_id": 1,
            "employee_id": 2,
            "quantity": 100,
            "status": 8,
            "date": "2026-05-04",
            "batch_id": "...",
            "file_name": "po-file.xlsx",
            "note": "optional",
            "employee": { "id": 2, "name": "..." }
        }
    ]
}
```

Do not read `status3` for PO export detail.

### Update/delete PO export detail from drawer

The old product detail action endpoint now supports `status = 8` and will update/delete `daily_quantities_po` plus re-sync `total_daily_quantities_po`.

Update:

```http
PUT /api/products/detail
```

```json
{
    "dailyId": 10,
    "product_id": 1,
    "status": 8,
    "quantity": 120
}
```

Delete:

```http
DELETE /api/products/detail/10?status=8
```

If `status=8` is not provided on delete, backend keeps the old non-PO behavior and deletes from `daily_quantities`.

## FE changes needed

- Replace any PO export UI/filter logic using `status = 3` with `status = 8`.
- For monthly PO export totals, read from `GET /api/quantities/monthly?status=8...`.
- Do not render status 8 monthly rows as editable `total_month_quantities` records, because they are computed from PO daily totals.
- If the UI has an old "Xuất Hàng" tab backed by `status3`, migrate it to `status8`.
- Any inventory formulas on FE should subtract PO export status 8 totals, not status 3 totals.

## Files changed in backend

- `app/Console/Commands/UpdateStockQuantity.php`
    - Replaced previous-month export lookup from `total_month_quantities.status = 3` with sum of `total_daily_quantities_po.status = 8`.
- `app/Http/Controllers/Api/Admin/TotalQuantityController.php`
    - Added status 8 branch in `getMonthly`.
    - Status 8 monthly totals are now aggregated from PO daily totals.
- `app/Http/Controllers/Api/Admin/ProductController.php`
    - Product detail returns `status8` from `daily_quantities_po`.
    - Product detail update/delete supports `status = 8` against PO records.
    - Product list status filter now accepts `status = 8`.
