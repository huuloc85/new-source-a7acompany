<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<body>
    <table style="width: 100%; margin: 0 auto;">
        <thead>
            <tr>
                <th colspan="{{ count($listDate) * 3 + 7 }}"
                    style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: left; padding: 10px; background-color: #002060; font-family: Times New Roman; font-size: 30px; height: 150px; vertical-align: middle;">
                    Bảng Chấm Công Từ: {{ $startDate->format('d/m/Y') }} Đến: {{ $endDate->format('d/m/Y') }}
                </th>
            </tr>
            <tr>
                <th rowspan="2"
                    style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; width: 150px; vertical-align: middle;">
                    STT
                </th>
                <th rowspan="2"
                    style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; width: 300px; vertical-align: middle;">
                    Tên Nhân Viên
                </th>
                <th rowspan="2"
                    style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; width: 300px; vertical-align: middle;">
                    Mã Nhân Viên
                </th>
                <th colspan="4"
                    style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; width: 400px; height: 50px; vertical-align: middle;">
                    Số Công Chính
                </th>
                @foreach ($listDate as $date)
                    <th colspan="3"
                        style="border: 2px solid black; color: #ffffff; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; vertical-align: middle; ">
                        {{ date('d-m-Y', strtotime($date)) }}
                    </th>
                @endforeach
            </tr>
            <tr>
                <th
                    style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; width: 100px; height: 130px; vertical-align: middle;">
                    Tổng<br>Giờ<br>Trong<br>Tháng
                </th>
                <th
                    style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; width: 100px; height: 130px; vertical-align: middle;">
                    Tổng<br>Giờ<br>Ngày
                </th>
                <th
                    style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; width: 100px; height: 130px; vertical-align: middle;">
                    Tổng<br>Giờ<br>Đêm
                </th>
                <th
                    style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; width: 100px; height: 130px; vertical-align: middle;">
                    Tổng<br>Giờ<br>Tăng<br>Ca
                </th>

                @foreach ($listDate as $date)
                    {{-- <th
                        style="border: 2px solid black; color: #000000; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; vertical-align: middle; height: 130px; ">
                        Tổng Giờ Làm Việc (H)
                    </th> --}}
                    <th
                        style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; vertical-align: middle; height: 130px; ">
                        Ngày
                    </th>
                    <th
                        style="border: 2px solid black; color: #000000; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; vertical-align: middle; height: 130px; ">
                        Đêm
                    </th>
                    <th
                        style="border: 2px solid black; color: #0000FF; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; vertical-align: middle; height: 130px; ">
                        TC
                    </th>
                    {{-- <th
                        style="border: 2px solid black; color: #ffffff; font-weight: bold; text-align: center; padding: 10px; background-color: #E26B0A; font-family: Times New Roman; font-size: 16px; vertical-align: middle; height: 130px; ">
                        Ca Làm Việc
                    </th> --}}
                @endforeach
            </tr>
        </thead>


        <tbody>
            @foreach ($records as $record)
                <tr>
                    <td
                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; color: #002060; font-weight: bold; vertical-align: center;">
                        {{ $loop->iteration }}
                    </td>
                    <td
                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; color: #002060; font-weight: bold; vertical-align: center;">
                        {{ $record->name }}
                    </td>
                    <td
                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; color: #002060; font-weight: bold; vertical-align: center;">
                        {{ $record->code }}
                    </td>

                    <td
                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; color: #002060; font-weight: bold; vertical-align: center; background-color: #8DB4E2; height: 50px; vertical-align: center;">
                        {{ $record->getEmployeeTotalHoursAttribute()['totalHourMonth'] ?? 0 }}
                    </td>
                    <td
                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; color: #002060; font-weight: bold; vertical-align: center; background-color: #8DB4E2; height: 50px; vertical-align: center;">
                        {{ $record->getEmployeeTotalHoursAttribute()['totalHourDay'] ?? 0 }}
                    </td>
                    <td
                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; color: #002060; font-weight: bold; vertical-align: center; background-color: #8DB4E2; height: 50px; vertical-align: center;">
                        {{ $record->getEmployeeTotalHoursAttribute()['totalHourNight'] ?? 0 }}
                    </td>
                    <td
                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; color: #002060; font-weight: bold; vertical-align: center; background-color: #8DB4E2; height: 50px; vertical-align: center;">
                        {{ $record->getEmployeeTotalHoursAttribute()['totalHourTC'] ?? 0 }}
                    </td>
                    <?php $rowIndex = 0; ?>
                    @foreach ($listDate as $date)
                        <?php $checkTrue = false; ?>
                        @foreach ($record->getDataAttribute() as $attendance)
                            @if ($attendance->date == $date)
                                <?php $checkTrue = true; ?>
                                {{-- <td
                                    style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; font-weight: bold; height: 50px; vertical-align: center; ">
                                    {{ $attendance->total_hours ? $attendance->total_hours : 0 }}
                                </td> --}}

                                @if ($attendance->shift == 'Ca 1')
                                    <td
                                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #ffffff; font-weight: bold; height: 50px; vertical-align: center; ">
                                        {{ $attendance->administrative_hours ? $attendance->administrative_hours : 0 }}
                                    </td>
                                    <td
                                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #ffffff; font-weight: bold; height: 50px; vertical-align: center; ">
                                        0
                                    </td>
                                @elseif ($attendance->shift == 'Ca 2')
                                    <td
                                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #ffffff; font-weight: bold; height: 50px; vertical-align: center; ">
                                        0
                                    </td>
                                    <td
                                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #ffffff; font-weight: bold; height: 50px; vertical-align: center; ">
                                        {{ $attendance->administrative_hours ? $attendance->administrative_hours : 0 }}
                                    </td>
                                @elseif ($attendance->shift == 'Đổi lịch đi làm')
                                    <td
                                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #ffffff; font-weight: bold; height: 50px; vertical-align: center; ">
                                        {{ $attendance->administrative_hours ? $attendance->administrative_hours : 0 }}
                                        <p style="color: red;">Lịch Thay Đổi Không Biết Ca Làm Việc</p>
                                    </td>
                                    <td
                                        style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #ffffff; font-weight: bold; height: 50px; vertical-align: center; ">
                                        0
                                    </td>
                                @endif

                                <td
                                    style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #0000FF; font-weight: bold; height: 50px; vertical-align: center; ">
                                    {{ $attendance->overtime_hours ? $attendance->overtime_hours : 0 }}
                                </td>
                                {{-- <td
                                    style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #0000FF; font-weight: bold; height: 50px; vertical-align: center; ">
                                    {{ $attendance->shift }}
                                </td> --}}
                            @endif
                        @endforeach

                        @if ($checkTrue == false)
                            {{-- <td
                                style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; font-weight: bold; height: 50px; vertical-align: center; ">
                                0
                            </td> --}}
                            <td
                                style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #ffffff; font-weight: bold; height: 50px; vertical-align: center; ">
                                0
                            </td>
                            <td
                                style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #ffffff; font-weight: bold; height: 50px; vertical-align: center; ">
                                0
                            </td>
                            <td
                                style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #0000FF; font-weight: bold; height: 50px; vertical-align: center; ">
                                0
                            </td>
                            {{-- <td
                                style="border: 2px solid black; text-align: center; padding: 10px; font-family: Times New Roman; font-size: 14px; background-color: {{ $rowIndex % 2 == 0 ? '#76933C' : '#FF66FF' }}; color: #0000FF; font-weight: bold; height: 50px; vertical-align: center; ">
                                Chưa Có Ca Làm Việc
                            </td> --}}
                        @endif

                        <?php $rowIndex++; ?>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
