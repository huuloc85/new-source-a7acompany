@extends('layouts.'.$layout)

<?php

if (isset($salaryOfficialsA7A)) {
    $salaryOfficialsVVP = $salaryOfficialsA7A;
}

/* Income */
$number_of_work_days_trial = number_format($salaryOfficialsVVP->number_of_work_days_trial, 2) ?? 0;
$day_shift_salary_trial = number_format($salaryOfficialsVVP->day_shift_salary_trial) ?? 0;
$day_shift_salary_trial_notice = $salaryOfficialsVVP->day_shift_salary_trial_notice ?? '';

$number_of_work_nights_trial = number_format($salaryOfficialsVVP->number_of_work_nights_trial, 2) ?? 0;
$night_shift_salary_trial = number_format($salaryOfficialsVVP->night_shift_salary_trial) ?? 0;
$night_shift_salary_trial_notice = $salaryOfficialsVVP->night_shift_salary_trial_notice ?? '';

$overtime_hours_trial = number_format($salaryOfficialsVVP->overtime_hours_trial, 2) ?? 0;
$overtime_salary_trial = number_format($salaryOfficialsVVP->overtime_salary_trial) ?? 0;
$overtime_salary_trial_notice = $salaryOfficialsVVP->overtime_salary_trial_notice ?? '';

$number_of_work = number_format($salaryOfficialsVVP->number_of_work, 2) ?? 0;
$allowance_apprentice_detail = number_format($salaryOfficialsVVP->allowance_apprentice_detail) ?? 0;
$allowance_apprentice_detail_notice = $salaryOfficialsVVP->allowance_apprentice_detail_notice ?? '';

$core_hours = number_format($salaryOfficialsVVP->core_hours, 2) ?? 0;
$official_salary = number_format($salaryOfficialsVVP->official_salary) ?? 0;
$official_salary_notice = $salaryOfficialsVVP->official_salary_notice ?? '';

$allowance_diligence_detail = number_format($salaryOfficialsVVP->allowance_diligence_detail) ?? 0;
$allowance_diligence_detail_notice = $salaryOfficialsVVP->allowance_diligence_detail_notice ?? '';

$allowance_responsibility_detail = number_format($salaryOfficialsVVP->allowance_responsibility_detail) ?? 0;
$allowance_responsibility_detail_notice = $salaryOfficialsVVP->allowance_responsibility_detail_notice ?? '';

$overtime_hours_detail = number_format($salaryOfficialsVVP->overtime_hours_detail, 2) ?? 0;
$overtime_salary = number_format($salaryOfficialsVVP->overtime_salary) ?? 0;
$overtime_salary_notice = $salaryOfficialsVVP->overtime_salary_notice ?? '';

$number_of_work_days = number_format($salaryOfficialsVVP->number_of_work_days) ?? 0;
$allowance_rice_detail = number_format($salaryOfficialsVVP->allowance_rice_detail) ?? 0;
$allowance_rice_detail_notice = $salaryOfficialsVVP->allowance_rice_detail_notice ?? '';

$number_of_work_nights = number_format($salaryOfficialsVVP->number_of_work_nights) ?? 0;
$allowance_shift_night = number_format($salaryOfficialsVVP->allowance_shift_night) ?? 0;
$allowance_shift_night_notice = $salaryOfficialsVVP->allowance_shift_night_notice ?? '';

$overtime_day_count_detail = number_format($salaryOfficialsVVP->overtime_day_count_detail) ?? 0;
$allowance_overtime_detail = number_format($salaryOfficialsVVP->allowance_overtime_detail) ?? 0;
$allowance_overtime_detail_notice = $salaryOfficialsVVP->allowance_overtime_detail_notice ?? '';

$holidays_count_detail = number_format($salaryOfficialsVVP->holidays_count_detail) ?? 0;
$holidays_money = number_format($salaryOfficialsVVP->holidays_money) ?? 0;
$holidays_money_notice = $salaryOfficialsVVP->holidays_money_notice ?? '';

$paid_holidays_count_detail = number_format($salaryOfficialsVVP->paid_holidays_count_detail) ?? 0;
$paid_holidays_money = number_format($salaryOfficialsVVP->paid_holidays_money) ?? 0;
$paid_holidays_money_notice = $salaryOfficialsVVP->paid_holidays_money_notice ?? '';

$business_travel_hours = number_format($salaryOfficialsVVP->business_travel_hours) ?? 0;
$gcn_business_travel_salary = number_format($salaryOfficialsVVP->gcn_business_travel_salary) ?? 0;
$gcn_business_travel_salary_notice = $salaryOfficialsVVP->gcn_business_travel_salary_notice ?? '';

$number_of_business_trips = number_format($salaryOfficialsVVP->number_of_business_trips) ?? 0;
$allowance_gcn_business_fuel = number_format($salaryOfficialsVVP->allowance_gcn_business_fuel) ?? 0;
$allowance_gcn_business_fuel_notice = $salaryOfficialsVVP->allowance_gcn_business_fuel_notice ?? '';

$money_referral_people = number_format($salaryOfficialsVVP->money_referral_people) ?? 0;
$money_referral_people_notice = $salaryOfficialsVVP->money_referral_people_notice ?? '';

$allowance_different = number_format($salaryOfficialsVVP->allowance_diffrent) ?? 0;
$allowance_different_notice = $salaryOfficialsVVP->allowance_diffrent_notice ?? '';

$bonuses_for_attendance = number_format($salaryOfficialsVVP->bonuses_for_attendance) ?? 0;
$bonuses_for_attendance_notice = $salaryOfficialsVVP->bonuses_for_attendance_notice ?? '';

$birthday_money = number_format($salaryOfficialsVVP->birthday_money) ?? 0;
$birthday_money_notice = $salaryOfficialsVVP->birthday_money_notice ?? '';

$previous_period_debt = number_format($salaryOfficialsVVP->previous_period_debt) ?? 0;
$previous_period_debt_notice = $salaryOfficialsVVP->previous_period_debt_notice ?? '';

// Total income
$salary_total = number_format($salaryOfficialsVVP->salary_total, 0) ?? 0;

/* Deductions */
$insurance_detail = number_format($salaryOfficialsVVP->insurance_detail) ?? 0;
$insurance_detail_notice = $salaryOfficialsVVP->insurance_detail_notice ?? '';

$advance_money = number_format($salaryOfficialsVVP->advance_money) ?? 0;
$advance_money_notice = $salaryOfficialsVVP->advance_money_notice ?? '';

$number_of_violations = number_format($salaryOfficialsVVP->number_of_violations) ?? 0;
$subtract_of_violations = number_format($salaryOfficialsVVP->subtract_of_violations) ?? 0;
$subtract_of_violations_notice = $salaryOfficialsVVP->subtract_of_violations_notice ?? '';

$days_leave_allowed = number_format($salaryOfficialsVVP->daysleave_allowed) ?? 0;
$subtract_days_leave_allowed = number_format($salaryOfficialsVVP->subtract_daysleave_allowed) ?? 0;
$subtract_days_leave_allowed_notice = $salaryOfficialsVVP->subtract_daysleave_allowed_notice ?? '';

$days_leave_not_allowed = number_format($salaryOfficialsVVP->daysleave_notallowed) ?? 0;
$subtract_days_leave_not_allowed = number_format($salaryOfficialsVVP->subtract_daysleave_notallowed) ?? 0;
$subtract_days_leave_not_allowed_notice = $salaryOfficialsVVP->subtract_daysleave_notallowed_notice ?? '';

$error_serious = number_format($salaryOfficialsVVP->error_serious) ?? 0;
$subtract_error_serious = number_format($salaryOfficialsVVP->subtract_error_serious) ?? 0;
$subtract_error_serious_notice = $salaryOfficialsVVP->subtract_error_serious_notice ?? '';

$error_minor = number_format($salaryOfficialsVVP->error_minor) ?? 0;
$subtract_error_minor = number_format($salaryOfficialsVVP->subtract_error_minor) ?? 0;
$subtract_error_minor_notice = $salaryOfficialsVVP->subtract_error_minor_notice ?? '';

$kpi_subtraction = number_format($salaryOfficialsVVP->kpi_subtraction) ?? 0;
$kpi_subtraction_notice = $salaryOfficialsVVP->kpi_subtraction_notice ?? '';

// Total deductions
$total = $salaryOfficialsVVP->insurance_detail + $salaryOfficialsVVP->advance_money + $salaryOfficialsVVP->subtract_of_violations + $salaryOfficialsVVP->subtract_daysleave_allowed + $salaryOfficialsVVP->subtract_daysleave_notallowed + $salaryOfficialsVVP->subtract_error_serious + $salaryOfficialsVVP->subtract_error_minor + $salaryOfficialsVVP->kpi_subtraction;

/* More information */
$start_date = $salaryManager->formatTimeDMY($salaryManager->start_date) ?? 'dd/mm/yyyy';
$end_date = $salaryManager->formatTimeDMY($salaryManager->end_date) ?? 'dd/mm/yyyy';

$name = Auth()->user()->name ?? 'your name';
$code = Auth()->user()->code ?? 'your code';
$role = Auth()->user()->role->role_name ?? 'your role';
$date_show = $salaryManager->formatTimeDMY($salaryManager->date_show) ?? 'dd/mm/yyyy';

$actually_received = number_format($salaryOfficialsVVP->actually_received) ?? 0;
$forms_of_payment = $salaryOfficialsVVP->forms_of_payment ?? '';
$salaryInWords = $salaryInWords ?? '';

$company_insurance_detail = number_format($salaryOfficialsVVP->company_insurance_detail) ?? 0;
$number_of_violations = number_format($salaryOfficialsVVP->number_of_violations) ?? '';
$actually_received = number_format($salaryOfficialsVVP->actually_received) ?? 0;

$otherNote = '........';

?>

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-1 position-relative mt-n1 mx-1">
                    <div class="border-radius-lg ps-2 pt-4 pb-3">
                        <h4 class="card-title mb-0">Chi Tiết Bảng Lương</h4>
                    </div>
                </div>
                <div class="card-body">
                    <a
                        class="btn btn-link mb-3"
                        href="{{ route('admin.employee-show.salary') }}"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                    <p class="mb-3">
                        <strong class="text-danger fw-bold">Lưu ý</strong>
                        : Tải bảng lương để xem rõ hơn
                    </p>
                    <button
                        class="btn btn-success mb-3"
                        onclick="downloadCanvas()"
                    >
                        Tải bảng lương
                    </button>
                    <div class="text-center overflow-x-auto">
                        <canvas
                            class="bg-white border"
                            id="salaryCanvas"
                        ></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const incomeData = [
            ['Diễn giải', 'Số giờ / Ngày', 'Thành tiền', 'Ghi chú'],
            [
                'Lương ca ngày (thử việc)',
                '{{ $number_of_work_days_trial }}',
                '{{ $day_shift_salary_trial }}',
                '{{ $day_shift_salary_trial_notice }}',
            ],
            [
                'Lương ca đêm (thử việc):',
                '{{ $number_of_work_nights_trial }}',
                '{{ $night_shift_salary_trial }}',
                '{{ $night_shift_salary_trial_notice }}',
            ],
            [
                'Lương tăng ca (thử việc):',
                '{{ $overtime_hours_trial }}',
                '{{ $overtime_salary_trial }}',
                '{{ $overtime_salary_trial_notice }}',
            ],
            [
                'Phụ cấp học việc',
                '{{ $number_of_work }}',
                '{{ $allowance_apprentice_detail }}',
                '{{ $allowance_apprentice_detail_notice }}',
            ],
            [
                'Số giờ chính',
                '{{ $core_hours }}',
                '{{ $official_salary }}',
                '{{ $official_salary_notice }}',
            ],
            [
                'Chuyên cần',
                '',
                '{{ $allowance_diligence_detail }}',
                '{{ $allowance_diligence_detail_notice }}',
            ],
            [
                'Trách Nhiệm',
                '',
                '{{ $allowance_responsibility_detail }}',
                '{{ $allowance_responsibility_detail_notice }}',
            ],
            [
                'Số giờ tăng ca',
                '{{ $overtime_hours_detail }}',
                '{{ $overtime_salary }}',
                '{{ $overtime_salary_notice }}',
            ],
            [
                'Phụ cấp cơm ca ngày',
                '{{ $number_of_work_days }}',
                '{{ $allowance_rice_detail }}',
                '{{ $allowance_rice_detail_notice }}',
            ],
            [
                'Phụ cấp cơm ca đêm:',
                '{{ $number_of_work_nights }}',
                '{{ $allowance_shift_night }}',
                '{{ $allowance_shift_night_notice }}',
            ],
            [
                'Phụ cấp tăng ca',
                '{{ $overtime_day_count_detail }}',
                '{{ $allowance_overtime_detail }}',
                '{{ $allowance_overtime_detail_notice }}',
            ],
            [
                'Tiền lễ tết',
                '{{ $holidays_count_detail }}',
                '{{ $holidays_money }}',
                '{{ $holidays_money_notice }}',
            ],
            [
                'Tiền phép năm',
                '{{ $paid_holidays_count_detail }}',
                '{{ $paid_holidays_money }}',
                '{{ $paid_holidays_money_notice }}',
            ],
            [
                'Lương đi công tác GCN',
                '{{ $business_travel_hours }}',
                '{{ $gcn_business_travel_salary }}',
                '{{ $gcn_business_travel_salary_notice }}',
            ],
            [
                'Phụ cấp xăng đi GCN',
                '{{ $number_of_business_trips }}',
                '{{ $allowance_gcn_business_fuel }}',
                '{{ $allowance_gcn_business_fuel_notice }}',
            ],
            [
                'Tiền giới thiệu người',
                '',
                '{{ $money_referral_people }}',
                '{{ $money_referral_people_notice }}',
            ],
            [
                'Phụ cấp khác',
                '',
                '{{ $allowance_different }}',
                '{{ $allowance_different_notice }}',
            ],
            [
                'Tiền thưởng đạt chuyên cần',
                '',
                '{{ $bonuses_for_attendance }}',
                '{{ $bonuses_for_attendance_notice }}',
            ],
            [
                'Tiền sinh nhật',
                '',
                '{{ $birthday_money }}',
                '{{ $birthday_money_notice }}',
            ],
            [
                'Tiền lương tháng trước bị thiếu',
                '',
                '{{ $previous_period_debt }}',
                '{{ $previous_period_debt_notice }}',
            ],
            ['Tổng thu nhập', '', '{{ $salary_total }}', ''],
        ];

        const deductionData = [
            ['Diễn giải', 'Số giờ / Ngày', 'Thành tiền', 'Ghi chú'],
            [
                'Khấu trừ BHXH (10.5%)',
                '',
                '{{ $insurance_detail }}',
                '{{ $insurance_detail_notice }}',
            ],
            [
                'Tạm ứng',
                '',
                '{{ $advance_money }}',
                '{{ $advance_money_notice }}',
            ],
            [
                'Phí công đoàn 1%',
                '{{ $number_of_violations }}',
                '{{ $subtract_of_violations }}',
                '{{ $subtract_of_violations_notice }}',
            ],
            [
                'Nghỉ có phép',
                '{{ $days_leave_allowed }}',
                '{{ $subtract_days_leave_allowed }}',
                '{{ $subtract_days_leave_allowed_notice }}',
            ],
            [
                'Nghỉ không phép',
                '{{ $days_leave_not_allowed }}',
                '{{ $subtract_days_leave_not_allowed }}',
                '{{ $subtract_days_leave_not_allowed_notice }}',
            ],
            [
                'Lỗi nặng',
                '{{ $error_serious }}',
                '{{ $subtract_error_serious }}',
                '{{ $subtract_error_serious_notice }}',
            ],
            [
                'Lỗi nhẹ',
                '{{ $error_minor }}',
                '{{ $subtract_error_minor }}',
                '{{ $subtract_error_minor_notice }}',
            ],
            [
                'Trừ KPI',
                '',
                '{{ $kpi_subtraction }}',
                '{{ $kpi_subtraction_notice }}',
            ],
            ['Tổng trừ', '', '{{ number_format($total) }}', ''],
        ];

        const rowHeight = 30;
        const extraPadding = 200; // Space for title & employee info
        const tableSpacing = 50; // Space between tables
        const bottomPadding = 350; // Space at the bottom of the canvas
        const canvasHeight =
            (incomeData.length + deductionData.length) * rowHeight +
            extraPadding +
            tableSpacing +
            bottomPadding;

        const canvas = document.getElementById('salaryCanvas');
        const ctx = canvas.getContext('2d');

        // Set canvas dimensions dynamically
        canvas.width = 800;
        canvas.height = canvasHeight;

        function drawTableTitle(title, startY) {
            ctx.font = 'bold 16px Arial';
            ctx.fillStyle = '#000';
            ctx.textAlign = 'left';
            ctx.fillText(title, 50, startY);
        }

        function drawTable(data, startY, boldRowIndex) {
            const colWidths = [250, 150, 150, 150];
            const startX = 50;

            ctx.strokeStyle = '#000';
            ctx.lineWidth = 1;

            for (let i = 0; i < data.length; i++) {
                let x = startX;

                // Bold header row
                if (i === 0) {
                    ctx.font = 'bold 14px Arial';
                    ctx.fillStyle = '#f5f5f5';
                    ctx.fillRect(
                        startX,
                        startY,
                        colWidths.reduce((a, b) => a + b, 0),
                        rowHeight,
                    );
                } else if (i === boldRowIndex) {
                    ctx.font = 'bold 14px Arial'; // Bold last row
                } else {
                    ctx.font = '14px Arial';
                }

                ctx.fillStyle = '#000';

                for (let j = 0; j < data[i].length; j++) {
                    ctx.strokeRect(x, startY, colWidths[j], rowHeight);
                    ctx.fillText(data[i][j], x + 10, startY + 20);
                    x += colWidths[j];
                }
                startY += rowHeight;
            }
        }

        function drawTables() {
            let startY = 0;

            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            startY += 40;
            ctx.font = 'bold 20px Arial';
            ctx.fillStyle = '#000';
            ctx.textAlign = 'center';
            ctx.fillText('THÔNG TIN BẢNG LƯƠNG', canvas.width / 2, startY);

            startY += 25;
            ctx.font = '15px Arial';
            ctx.fillText(
                'Từ {{ $start_date }} đến {{ $end_date }}',
                canvas.width / 2,
                startY,
            );

            ctx.textAlign = 'left';

            startY += 30;
            ctx.font = 'bold 16px Arial';
            ctx.fillText('Tên nhân viên:', 50, startY);
            ctx.font = '16px Arial';
            ctx.fillText('{{ $name }}', 200, startY);

            startY += 20;
            ctx.font = 'bold 16px Arial';
            ctx.fillText('Mã nhân viên:', 50, startY);
            ctx.font = '16px Arial';
            ctx.fillText('{{ $code }}', 200, startY);

            startY += 20;
            ctx.font = 'bold 16px Arial';
            ctx.fillText('Bộ phận:', 50, startY);
            ctx.font = '16px Arial';
            ctx.fillText('{{ $role }}', 200, startY);

            startY += 20;
            ctx.font = 'bold 16px Arial';
            ctx.fillText('Ngày nhận lượng:', 50, startY);
            ctx.font = '16px Arial';
            // TODO: Get the actual date
            ctx.fillText('{{ $date_show }}', 200, startY);

            startY += 40;
            drawTableTitle('Các khoảng lương', startY);
            startY += 10;
            drawTable(incomeData, startY, incomeData.length - 1);

            startY += incomeData.length * rowHeight + tableSpacing - 20;
            drawTableTitle('Các khoảng trừ ', startY);
            startY += 10;
            drawTable(deductionData, startY, deductionData.length - 1);

            // Add extra space before drawing the new description
            startY += (deductionData.length + 1) * rowHeight + 20;

            ctx.font = 'bold 16px Arial';
            ctx.fillText('Thực nhận tiền lương:', 50, startY);
            ctx.font = '16px Arial';
            ctx.fillText('{{ $actually_received }}', 250, startY);

            startY += 25;
            ctx.font = 'bold 16px Arial';
            ctx.fillText('Bằng chữ:', 50, startY);
            ctx.font = '16px Arial';
            ctx.fillText('{{ $salaryInWords }}', 150, startY);

            startY += 25;
            ctx.font = 'bold 16px Arial';
            ctx.fillText('Phương thức:', 50, startY);
            ctx.font = '16px Arial';
            ctx.fillText('{{ $forms_of_payment }}', 200, startY);

            startY += 50;
            ctx.font = 'bold 16px Arial';
            ctx.fillText(
                'Công ty phải đóng BHXH 21,5% cho người lao động: ',
                50,
                startY,
            );
            ctx.font = '16px Arial';
            ctx.fillText('{{ $company_insurance_detail }}', 600, startY);

            startY += 25;
            ctx.font = 'bold 16px Arial';
            ctx.fillText(
                'Công ty phải đóng Kinh phí công đoàn 2% cho người lao động:',
                50,
                startY,
            );
            ctx.font = '16px Arial';
            ctx.fillText('{{ $number_of_violations }}', 600, startY);

            startY += 25;
            ctx.fillStyle = 'red';
            ctx.font = 'bold 16px Arial';
            ctx.fillText(
                'Công ty phải tổng trả chi phí lương cho 01 người lao động / tháng:',
                50,
                startY,
            );
            ctx.fillStyle = '#000';
            ctx.font = '16px Arial';
            ctx.fillText('{{ $actually_received }}', 600, startY);

            startY += 50;
            ctx.font = 'bold 16px Arial';
            ctx.fillText('Chi chú (nếu có):', 50, startY);
            ctx.font = '16px Arial';
            ctx.fillText('{{ $otherNote }}', 200, startY);
        }

        function downloadCanvas() {
            // Create a new canvas with a white background
            const tempCanvas = document.createElement('canvas');
            const tempCtx = tempCanvas.getContext('2d');

            // Set the same size as the original canvas
            tempCanvas.width = canvas.width;
            tempCanvas.height = canvas.height;

            // Fill with white background
            tempCtx.fillStyle = '#ffffff';
            tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

            // Draw the existing canvas onto the new one
            tempCtx.drawImage(canvas, 0, 0);

            // Create download link
            const link = document.createElement('a');
            link.download = 'salary_breakdown.png';
            link.href = tempCanvas.toDataURL('image/png');
            link.click();
        }

        drawTables();
    </script>
@endsection
