<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<body>
    <tr>
        <th style="width: 120px"></th>
        <th style="width: 68px"></th>
        <th
            style="width: 120px; font-size: 9px; font-weight: bold; font-family: Times New Roman; color:#fe0000; text-align: left;">
            NHẬP SẢN LƯỢNG HÀNG NGÀY</th>
    </tr>
    <table>
        <thead>
            <tr>
                <th
                    style="width: 100px; font-size: 9px; height: 48px;  font-weight: bold; font-family: Times New Roman; vertical-align: center;  text-align: center; border: 1px solid black;">
                    TÊN LINH KIỆN</th>
                <th
                    style="width: 100px; font-size: 9px; font-weight: bold; vertical-align: center;font-family: Times New Roman; text-align: center; border: 1px solid black;">
                    TỔNG CỘNG</th>
                @foreach ($daysInMonth as $dayInMonth)
                    <th colspan="2"
                        style="width: 140px; font-size: 9px;font-family:Times New Roman;font-weight: bold; vertical-align: center; text-align: center; border: 1px solid black;">
                        {{ $dayInMonth }}</th>
                @endforeach
            </tr>
            <tr>
                <th
                    style="width: 100px; font-size: 9px; font-family: Times New Roman; vertical-align: center; text-align: center; border: 1px solid black;">
                </th>
                <th
                    style="width: 100px; font-size: 9px; font-family: Times New Roman; vertical-align: center; text-align: center; border: 1px solid black;">
                </th>
                @foreach ($daysInMonth as $dayInMonth)
                    <th
                        style="width: 70px; font-size: 9px; font-family: Times New Roman;font-weight: bold;  vertical-align: center; text-align: center; border: 1px solid black;">
                        Ca 1</th>
                    <th
                        style="width: 70px; font-size: 9px; font-family: Times New Roman;font-weight: bold;  vertical-align: center; text-align: center; border: 1px solid black;">
                        Ca 2</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td
                        style="width: 100px; font-size: 9px; font-family: Times New Roman; vertical-align: center;font-weight: bold;  text-align: left; border: 1px solid black;">
                        {{ $item['name'] }}</td>
                    <td
                        style="width: 100px; font-size: 9px; font-family: Times New Roman; vertical-align: center;font-weight: bold;  text-align: center; border: 1px solid black;">
                        {{ number_format($item['totalQuantityMonth']) }}</td>
                    @foreach ($daysInMonth as $dayInMonth)
                        @php
                            $totalQuanDateCa1 = session("totalQuanDateCa1_{$dayInMonth}_{$item['id']}") ?? 0;
                            $totalQuanDateCa2 = session("totalQuanDateCa2_{$dayInMonth}_{$item['id']}") ?? 0;
                        @endphp
                        <td
                            style="width: 70px; font-size: 9px; font-family: Times New Roman; vertical-align: center; text-align: center; border: 1px solid black;">
                            {{ number_format((float) $totalQuanDateCa1) }}</td>
                        <td
                            style="width: 70px; font-size: 9px; font-family: Times New Roman; vertical-align: center; text-align: center; border: 1px solid black;">
                            {{ number_format((float) $totalQuanDateCa2) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
