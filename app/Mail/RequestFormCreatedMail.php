<?php

namespace App\Mail;

use App\Models\RequestForm;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequestFormCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $requestForm;

    public $supervisor;

    public $approvalUrl;

    public $requestFormTypeName;

    /**
     * Create a new message instance.
     */
    public function __construct(RequestForm $requestForm, $supervisor, string $approvalUrl)
    {
        $this->requestForm = $requestForm;
        $this->supervisor = $supervisor;
        $this->approvalUrl = $approvalUrl;

        // Lấy tên loại đơn
        $types = RequestForm::getTypes();
        $this->requestFormTypeName = $types[$requestForm->type] ?? $requestForm->type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bạn có đơn mới cần phê duyệt '
        );
    }

    /**     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.request-form-created',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
