<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<body>
    <table>
        <thead>
            <tr>
                <th
                    style="width: 145px; height:116px;font-family: Calibri;font-weight: bold; font-size: 9px;text-align: center; border: 1px solid black; background-color:#ffff00; color:#002060">
                    MÃ LINH KIỆN</th>
                <th
                    style="width: 120px; border: 1px solid black; font-family:VNI-Times; font-size: 9px; text-align: center; background-color:#ffff00">
                    TÊN LINH KIỆN 部品名称</th>

                <th
                    style="width: 81px; border: 1px solid black; font-family:VNI-Times; font-size: 9px;vertical-align: center; text-align: center; background-color:#ffff00; color:#002060">
                    SẢN LƯỢNG (MOQ)</th>
                <th
                    style="width: 96px; border: 1px solid black; font-family:VNI-Times; font-size: 9px;vertical-align: center; text-align: center; background-color:#ffff00; color:#002060">
                    THUNG CATON/THANG (MOQ)</th>
                <th
                    style="width: 60px; border: 1px solid black; font-family:VNI-Times; font-size: 9px;vertical-align: center;  text-align: center;background-color:#00b050; color:#002060">
                    Kích thước khuôn
                    金型サイズ</th>
                <th
                    style="width: 76px; border: 1px solid black; font-family:VNI-Times; font-size: 9px;vertical-align: center;  text-align: center;background-color:#00b050; color:#002060">
                    Số CAV(cái/ shot)
                    取り数（個/ショット）</th>
                <th
                    style="width: 78px; border: 1px solid black; font-family:VNI-Times; font-size: 9px; vertical-align: center;  text-align: center;background-color:#ffff00; color:#002060">
                    Chu kì(s/shot)
                    サイクル時間（秒/ショット）</th>
                <th
                    style="width: 141px; border: 1px solid black; font-family:VNI-Times; font-size: 9px; vertical-align: center; text-align: center;background-color:#ffff00; color:#002060">
                    Dự định
                    Thời gian hoạt đông thiết bị(ngày/tháng)
                    計画
                    設備負荷（日/月）</th>
                <th
                    style="width: 81px; border: 1px solid black; font-family:VNI-Times; font-size: 9px; vertical-align: center;  text-align: center;background-color:#ffff00; color:#002060">
                    Thực tế
                    Thời gian hoạt đông thiết bị(ngày/tháng)
                    実績
                    設備負荷（日/月）</th>
                <th
                    style="width: 92px;border: 1px solid black;font-family:VNI-Times;  font-size: 9px;vertical-align: center;  text-align: center;background-color:#ffff00; color:#002060">
                    FAPV出荷（対象品 〇表示）</th>
                <th
                    style="width: 79px; border: 1px solid black; font-family:VNI-Times; font-size: 9px; vertical-align: center;  text-align: center;background-color:#ffff00; color:#002060">
                    FASV出荷（対象品 〇表示）</th>
                <th
                    style="width: 103px;border: 1px solid black;font-family:VNI-Times; font-size: 9px; vertical-align: center; text-align: center;background-color:#ffff00; color:#002060">
                    FAVV出荷（対象品 〇表示）</th>
                @foreach ($models as $model)
                    <th
                        style="width: 54px;font-weight: bold; font-family:VNI-Times; font-size: 9px; border: 1px solid black; text-align: center; vertical-align: center;background-color:#00b050;  display: inline-block;color:#002060">
                        {{ $model }}</th>
                @endforeach

                <th
                    style="width: 63px;font-weight: bold; font-size: 9px;font-family: Times New Roman; border: 1px solid black; text-align: center; background-color:#d9e1f2; color:#002060">
                    TỔNG SỐ LƯỢNG TỒN ĐẦU KỲ</th>
                <th
                    style="width: 80px;font-weight: bold; font-size: 9px;font-family: Times New Roman;  border: 1px solid black; text-align: center; background-color:#d9e1f2; color:#002060">
                    TỔNG THỰC TẾ SẢN XUẤT(cái/tháng)総計製造実績（個/月）</th>
                <th
                    style="width: 62px;font-weight: bold; font-size: 9px;font-family: Times New Roman; border: 1px solid black; text-align: center; background-color:#d9e1f2; color:#002060">
                    TỔNG SỐ LƯỢNG ĐÃ XUẤT</th>
                <th
                    style="width: 72px;font-weight: bold; font-size: 9px;font-family: Times New Roman; border: 1px solid black; text-align: center; background-color:#d9e1f2; color:#002060">
                    SỐ LƯỢNG HÀNG ĐÃ KIỂM 200%</th>
                <th
                    style="width: 56px;font-weight: bold; font-size: 9px;font-family: Times New Roman; border: 1px solid black; text-align: center; background-color:#d9e1f2; color:#002060">
                    SỐ LƯỢNG HÀNG CHƯA KIỂM 200%</th>
                <th
                    style="width: 62px;font-weight: bold; font-size: 9px;font-family: Times New Roman; border: 1px solid black; text-align: center; background-color:#d9e1f2; color:#002060">
                    TỔNG SỐ LƯỢNG TỒN CUỐI KỲ</th>
                <th
                    style="width: 56px;font-weight: bold; font-size: 9px;font-family: Times New Roman; border: 1px solid black; text-align: center; background-color:#d9e1f2; color:#002060">
                    SỐ NGÀY TỒN KHO</th>
                @foreach ($listMonthExport as $monthExport)
                    <th
                        style="width: 62px;font-weight: bold;font-size: 9px; font-family: Times New Roman; border: 1px solid black; text-align: center; background-color:#fff2cc; color:#002060">
                        SỐ <br> LƯỢNG <br>
                        <br> ĐÃ XUẤT <br> {{ $monthExport }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $product)
                <tr>
                    <td
                        style="font-family: Arial;font-size: 9px;border: 1px solid black; text-align: left; color:#002060">
                        {!! $product['code'] !!}</td>
                    <td
                        style="font-family: Arial;font-size: 9px;border: 1px solid black; text-align: left; color:#002060">
                        {!! $product['name'] !!}</td>
                    <td
                        style="font-family: Arial;font-size: 9px;border: 1px solid black; text-align: right; color:#002060">
                        {!! number_format($product['stockMOQ']) !!}</td>
                    <td
                        style="font-family: Calibri;font-size: 9px;border: 1px solid black; text-align: right; color:#002060">
                        {!! number_format($product['quantityCaTon']) !!}</td>
                    <td
                        style="font-family: Calibri;font-size: 9px; background-color:#fff2cc; border: 1px solid black; text-align: left; color:#002060">
                        {!! $product['moldSize'] !!}</td>
                    <td
                        style="font-family: Calibri;font-size: 9px; background-color:#fff2cc; border: 1px solid black;text-align: right; color:#002060">
                        {!! $product['CAV'] !!}</td>
                    <td
                        style="font-family: Calibri;font-size: 9px; background-color:#fff2cc; border: 1px solid black; text-align: right; color:#002060">
                        {!! $product['cycle'] !!}</td>
                    <td
                        style="font-family: Calibri; font-size: 9px;background-color:#ccffff; border: 1px solid black; text-align: right; color:#002060">
                        {!! number_format($product['planTime'], 1) !!}</td>
                    <td
                        style="font-family: Calibri;font-size: 9px; background-color:#ccffff; border: 1px solid black; text-align: right; color:#002060">
                        {{ number_format($product['realTime'], 1) }}</td>
                    <td
                        style="font-family: Calibri;font-size: 9px; border: 1px solid black; text-align: center; color:#002060">
                        {{ $product['FAPV'] == 1 ? '〇' : '' }}</td>
                    <td
                        style="font-family: Calibri;font-size: 9px;border: 1px solid black; text-align: center; color:#002060">
                        {{ $product['FASV'] == 1 ? '〇' : '' }}</td>
                    <td
                        style="font-family: Calibri; font-size: 9px;border: 1px solid black; text-align: center; color:#002060">
                        {!! $product['FAVV'] == 1 ? '〇' : '' !!}</td>
                    @foreach ($models as $key => $model)
                        <td
                            style="font-family: Times New Roman; font-size: 9px;border: 1px solid black; text-align: right; color:#002060">
                            {{ $product['binCode'] == $model ? $product['quanEntityBin'] : '' }}
                        </td>
                    @endforeach
                    <td
                        style="font-family: Times New Roman; font-size: 9px;border: 1px solid black; text-align: center; color:#002060">
                        {{ number_format($product['stockQuantity']) }}</td>
                    <td
                        style="font-family: Times New Roman; font-size: 9px;border: 1px solid black; text-align: center; color:#002060">
                        {{ number_format($product['realityQuantity']) }}</td>
                    <td
                        style="font-family: Times New Roman; font-size: 9px;border: 1px solid black; text-align: center; color:#002060">
                        {{ number_format($product['exportedQuantity']) }}</td>
                    <td
                        style="font-family: Times New Roman; font-size: 9px;border: 1px solid black; text-align: center; color:#002060">
                        {{ number_format($product['checked200']) }}</td>
                    <td
                        style="font-family: Times New Roman;font-size: 9px; border: 1px solid black; text-align: center; color:#002060">
                        {{ number_format($product['unchecked200']) }}</td>
                    <td
                        style="font-family: Times New Roman;font-size: 9px;border: 1px solid black; text-align: center; color:#002060">
                        {{ number_format($product['stockEndQuantity']) }}</td>
                    <td
                        style="font-family: Times New Roman; font-size: 9px;border: 1px solid black; text-align: center; color:#002060">
                        {{ number_format($product['daysInventory'], 1) }}</td>
                    @foreach ($product['totalQuantityExportPerMonth'] as $month => $quantity)
                        <td
                            style="font-family: Times New Roman;font-size: 9px;border: 1px solid black; text-align: center; color:#002060">
                            {{ number_format($quantity) }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
