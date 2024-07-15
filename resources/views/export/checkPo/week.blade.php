<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<body>
    <?php $weekDate = array_keys($weekDates); ?>
    <div class="col-12">
        <tr>
            <th style="width: 120px"></th>
            <th style="width: 68px"></th>
            <th
                style="width: 200px; font-size: 9px; font-weight: bold; font-family: Times New Roman; color:#fe0000; text-align: left;">
                BẢNG KIỂM TRA PO THEO THÁNG </th>
        </tr>
        <table class="table align-items-center mb-0 table-striped">
            <thead>
                <tr>
                    <th
                        style="width: 40px; border: 1px solid black; font-family: Times New Roman; font-size: 9px; vertical-align: center; text-align: center; background-color: #99CCFF; font-weight: bold; border: 1px solid black;">
                        STT
                    </th>
                    <th
                        style="width: 170px; border: 1px solid black; font-family: Times New Roman; font-size: 9px; vertical-align: center; text-align: center; background-color: #99CCFF; font-weight: bold; border: 1px solid black;">
                        TÊN LINH KIỆN 部品名称
                    </th>
                    <th
                        style="width: 200px; border: 1px solid black; font-family: Times New Roman; font-size: 9px; vertical-align: center; text-align: center; background-color: #99CCFF; font-weight: bold; border: 1px solid black;">
                        TỔNG SỐ LƯỢNG TỒN HIỆN TẠI
                    </th>
                    <th
                        style="width: 150px; border: 1px solid black; font-family: Times New Roman; font-size: 9px; vertical-align: center; text-align: center; background-color: #99CCFF; font-weight: bold; border: 1px solid black;">
                        CÒN LẠI TRONG TUẦN
                    </th>
                    <th
                        style="width: 150px; border: 1px solid black; font-family: Times New Roman; font-size: 9px; vertical-align: center; text-align: center; background-color: #99CCFF; font-weight: bold; border: 1px solid black;">
                        ĐÃ XUẤT TRONG TUẦN
                    </th>
                    <th
                        style="width: 100px; border: 1px solid black; font-family: Times New Roman; font-size: 9px; vertical-align: center; text-align: center; background-color: #99CCFF; font-weight: bold; border: 1px solid black; ">
                        TỒN ĐẦU TUẦN
                    </th>


                    @foreach ($weekDate as $date)
                        <th
                            style="width: 100px; border: 1px solid black; font-family: Times New Roman; font-size: 9px; vertical-align: center; text-align: center; background-color: #FCE4D6; font-weight: bold; border: 1px solid black;">
                            {{ $date }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    @php
                        $total = session("$index.$product->id.total") ?? 0;
                        $totalReamingOfWeek = session("$index.$product->id.totalReamingOfWeek") ?? 0;
                        $quanExport = session("$index.$product->id.quanExport") ?? 0;
                        $beginningOfWeek = session("$index.$product->id.beginningOfWeek") ?? 0;
                    @endphp
                    <tr>
                        <td
                            style="font-family:Times New Roman;font-size: 9px; text-align: center; border: 1px solid black; font-weight: bold; border: 1px solid black;">
                            {{ $loop->iteration }}</td>
                        <td
                            style="font-family:Times New Roman;font-size: 9px; text-align: left; border: 1px solid black; font-weight: bold; border: 1px solid black;">
                            {{ $product->name }}</td>
                        <td
                            style="font-family:Times New Roman;font-size: 9px; text-align: center; border: 1px solid black;">
                            {{ number_format($total) }}</td>
                        <td
                            style="font-family:Times New Roman;font-size: 9px; text-align: center; border: 1px solid black;">
                            {{ number_format($totalReamingOfWeek) }}</td>
                        <td
                            style="font-family:Times New Roman;font-size: 9px; text-align: center; border: 1px solid black;">
                            {{ number_format($quanExport) }}</td>
                        <td
                            style="font-family:Times New Roman;font-size: 9px; text-align: center; border: 1px solid black;">
                            {{ number_format($beginningOfWeek) }}</td>
                        @foreach ($weekDate as $date)
                            @php
                                $quanExport = $weekDates[$date]['quanExport'][$product->id] ?? 0;
                            @endphp
                            <td
                                style="font-family:Times New Roman;font-size: 9px; text-align: center; border: 1px solid black;">
                                {{ number_format($quanExport) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
