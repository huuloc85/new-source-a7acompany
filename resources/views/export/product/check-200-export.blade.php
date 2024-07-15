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
        <th style="width: 74px"></th>
        <th style="width: 68px"></th>
        <th style="width: 120px; font-size: 9px; font-weight: bold; font-family: Times New Roman; color:#fe0000; text-align: left;">NHẬP HÀNG 200%</th>
    </tr>
    <table>
        <thead>
            <tr>
                <th style="width: 120px; height: 48px; font-size: 9px; font-weight: bold; font-family: Times New Roman; vertical-align: center;  text-align: left; border: 1px solid black;">Tên Linh Kiện</th>
                <th style="width: 74px; font-size: 9px; font-weight: bold; text-align: center; font-family: Times New Roman; vertical-align: center; border: 1px solid black;">Tồn đầu kỳ hàng 200%</th>
                <th style="width: 68px; font-size: 9px; font-weight: bold; text-align: center; font-family: Times New Roman; vertical-align: center;border: 1px solid black;">Phát sinh kiểm hàng 200%</th>
                @foreach($daysInMonth as $dayInMonth)
                <th style="width: 68px; text-align: right;font-size: 9px; font-family: Times New Roman; vertical-align: center; border: 1px solid black;">{{$dayInMonth}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td style="font-family:Times New Roman;font-size: 9px; text-align: left; border: 1px solid black;">{{ $item['name'] }}</td>
                <td style="font-family:MS UI Gothic;font-size: 9px; text-align: center; border: 1px solid black;">{{ number_format($item['totalQuantityInventory']) }}</td>
                <td style="font-family:Times New Roman;font-size: 9px; text-align: center; border: 1px solid black;">{{ number_format($item['totalQuantityMonth']) }}</td>
                @foreach($item['totalQuantityPerDay'] as $day => $quantity)
                <td style="font-family:Times New Roman;font-size: 9px; text-align: center; border: 1px solid black;">{{ number_format((float)$quantity) }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>