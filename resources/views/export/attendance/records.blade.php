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
                    <th rowspan="2"
                        style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                        STT
                    </th>
                    <th rowspan="2"
                        style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                        Tên Nhân Viên
                    </th>
                    <th rowspan="2"
                        style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                        Mã Nhân Viên
                    </th>
                    @foreach ($listDate as $date)
                        <th colspan="3"
                            style="border: 1px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #007bff; font-family: Arial, sans-serif; font-size: 16px;">
                            Ngày {{ date('d-m-Y', strtotime($date)) }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($listDate as $date)
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
                    @endforeach
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
                            {{ $record->name }}
                        </td>
                        <td
                            style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                            {{ $record->code }}
                        </td>
                        @foreach ($listDate as $date)
                            <?php $checkTrue = false; ?>
                            @foreach ($record->getDataAttribute() as $attendace)
                                @if ($attendace->date == $date)
                                    <?php $checkTrue = true; ?>
                                    <td style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                                        {{ $attendace->total_hours ? $attendace->total_hours : 0 }}
                                    </td>
                                    <td style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                                        {{ $attendace->administrative_hours ? $attendace->administrative_hours : 0 }}
                                    </td>
                                    <td style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                                        {{ $attendace->overtime_hours ? $attendace->overtime_hours : 0 }}
                                    </td>
                                @endif
                            @endforeach
                            @if ($checkTrue == false)
                                <td style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                                    0
                                </td>
                                <td style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                                    0
                                </td>
                                <td style="border: 1px solid black; text-align: center; padding: 10px; font-family: Arial, sans-serif; font-size: 14px;">
                                    0
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
