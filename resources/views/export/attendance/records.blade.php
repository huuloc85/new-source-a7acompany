<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<body>
    <table
        style="width: 100%; border-collapse: collapse; margin: 0 auto; background-color: #fff; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
        <thead>
            <tr>
                <th colspan="3"
                    style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                    Bảng Chấm Công Từ: {{ $startDate->format('d/m/Y') }} Đến: {{ $endDate->format('d/m/Y') }}
                </th>
            </tr>
            <tr>
                <th
                    style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                    STT
                </th>
                <th
                    style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                    Tên Nhân Viên
                </th>
                <th
                    style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                    Mã Nhân Viên
                </th>
                <th
                    style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                    Ngày
                </th>
                <th
                    style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                    Tổng Giờ Làm Việc(H)
                </th>
                <th
                    style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                    Giờ Hành Chính(H)
                </th>
                <th
                    style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                    Giờ Tăng Ca(H)
                </th>
            </tr>
        </thead>

        <tbody>

            @foreach ($records as $record)
                <tr>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">

                        {{ $loop->iteration }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $record->employee_code }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $record->employee ? $record->employee->name : 'Không xác định' }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $record->date }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                        {{ $record->total_hours ? round($record->total_hours, 2) : 'Chấm công không đủ' }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                        {{ number_format($record->administrative_hours, 2) }}
                    </td>
                    <td
                        style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                        {{ number_format($record->overtime_hours, 2) }}
                    </td>
            @endforeach

        </tbody>
    </table>

</body>

</html>
