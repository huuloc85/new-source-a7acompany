<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMailDailyQuantity extends Mailable
{
    use Queueable, SerializesModels;

    public $selectedDate;
    public $productivityLogsQuery;
    public $translatedCalendarDetails;
    public $employeesWithoutProductivity;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($selectedDate, $productivityLogsQuery, $translatedCalendarDetails, $employeesWithoutProductivity)
    {
        $this->selectedDate = $selectedDate;
        $this->productivityLogsQuery = $productivityLogsQuery;
        $this->translatedCalendarDetails = $translatedCalendarDetails;
        $this->employeesWithoutProductivity = $employeesWithoutProductivity;
    }

    public function build()
    {
        return $this->view('mail.send-mail-daily-quantity')
            ->subject('Thông báo: Báo Cáo Sản Lượng Hàng Ngày');
    }
}
