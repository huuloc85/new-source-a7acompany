<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<body>
    <tr>
        <th rowspan="2" colspan="22"
            style="vertical-align: middle; text-align: right; border: 1px solid black; color: #000000; font-weight: bold; font-style: italic; font-family: Arial, sans-serif; font-size: 10px;">
            QP03 mẫu 2 (ngày ban hành: 6.2020, cải đính)</th>
    </tr>
    <br>
    <tr>
        <th rowspan="7" colspan="22"
            style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 26px;">
            KẾ HOẠCH SẢN XUẤT THÁNG {{ $month }}</th>
    </tr>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <table style="width:
        100%; border-collapse: collapse; table-layout: fixed; font-family: Arial, sans-serif;">
        <thead>
            <tr>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">STT</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">MÃ SẢN PHẨM</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">TÊN SẢN PHẨM</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">TÊN NGUYÊN VẬT LIỆU</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">KẾ HOẠCH SẢN XUẤT (PCS)</th>
                <th style="vertical-align: middle; text-align: center; background-color: yellow; font-weight: bold; font-size: 14px; font-family: Arial, height: 100px; border: 1px solid black; padding: 10px;"
                    rowspan="3">DỰ ĐỊNH VẬT LIỆU (KG)</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; height: 30px; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    colspan="6">BAO BÌ</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">TỶ TRỌNG SẢN PHẨM (G)</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">KẾ HOẠCH SẢN XUẤT/NGÀY</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">SỐ CAVITY</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">CHU KỲ</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">TẤN</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">MÁY</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">SỐ NGÀY CHẠY MÁY</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">SỐ NGÀY CÒN SX (NGÀY)</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">SỐ LƯỢNG CÒN SX (PCS)</th>
                <th style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;"
                    rowspan="3">SỐ LƯỢNG ĐÃ SX (PCS)</th>
                <th rowspan="1" colspan="{{ 2 * 31 }}"
                    style="vertical-align: middle; text-align: center; border: 1px solid black; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;">
                    Ngày Tháng</th>
            </tr>
            <tr>
                <th rowspan="2"
                    style="vertical-align: middle; text-align: center; border: 1px solid black; height: 70px; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;">
                    LOẠI BAO BÌ</th>
                <th rowspan="2"
                    style="vertical-align: middle; text-align: center; border: 1px solid black; height: 70px; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;">
                    SỐ BAO BÌ/ THÙNG</th>
                <th rowspan="2"
                    style="vertical-align: middle; text-align: center; border: 1px solid black; height: 70px; background-color: yellow; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;">
                    TỔNG BAO BÌ</th>
                <th rowspan="2"
                    style="vertical-align: middle; text-align: center; border: 1px solid black; height: 70px; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;">
                    LOẠI THÙNG</th>
                <th rowspan="2"
                    style="vertical-align: middle; text-align: center; border: 1px solid black; height: 70px; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;">
                    SẢN PHẨM/THÙNG</th>
                <th rowspan="2"
                    style="vertical-align: middle; text-align: center; border: 1px solid black; height: 70px; background-color: yellow; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;">
                    SỐ LƯỢNG THÙNG</th>
                @foreach ($formattedDaysArray as $dayInMonth)
                    <th rowspan="1" colspan="2"
                        style=" vertical-align: middle; text-align: center; border: 1px solid black; height: 70px; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;">
                        {{ \Carbon\Carbon::parse($dayInMonth)->format('d/m') }}
                    </th>
                @endforeach
            </tr>
            <tr>
                @foreach ($formattedDaysArray as $dayInMonth)
                    <th rowspan="1"
                        style="vertical-align: middle; text-align: center; border: 1px solid black; height: 70px; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;;">
                        Ca 1</th>
                    <th
                        style="vertical-align: middle; text-align: center; border: 1px solid black; height: 70px; color: #000000; font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;">
                        Ca 2</th>
                @endforeach
            </tr>

        </thead>
        <tbody>
            @foreach ($plans as $plan)
                <tr>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $loop->iteration }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px; font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->product->code }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->product->name }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->material_name }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ number_format($plan->production_plan) }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->planned_material }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->packaging_type }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->packaging_count_per_box }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ number_format($plan->total_packaging) }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->box_type }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ number_format($plan->products_per_box) }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->box_quantity }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->product_density }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ number_format($plan->daily_production_plan) }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->cavity_count }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->cycle }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;display: flex; justify-content: center; align-items: center; font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->ton }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->machine }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;display: flex; justify-content: center; align-items: center; font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->machine_run_days }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $plan->remaining_production_days }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ number_format($plan->remaining_production_quantity) }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                        {{ number_format($plan->produced_quantity) }}
                    </td>
                    @foreach ($formattedDaysArray as $dayInMonth)
                        @php
                            $totalQuanDateCa1 = session("totalQuanDateCa1_{$dayInMonth}_{$plan->id}") ?? 0;
                            $totalQuanDateCa2 = session("totalQuanDateCa2_{$dayInMonth}_{$plan->id}") ?? 0;
                        @endphp
                        <td
                            style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                            {{ number_format((float) $totalQuanDateCa1) }}
                        </td>
                        <td
                            style="border: 1px solid black; text-align: center; padding: 10px; height: 30px;font-family: Arial, sans-serif; font-size: 14px;">
                            {{ number_format((float) $totalQuanDateCa2) }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
