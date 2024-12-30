<?php

namespace App\Notifications;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use App\Events\DocumentUploaded;

class DocumentUploadedNotification extends Notification
{
    use Queueable;

    public $document;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new document has been uploaded.')
                    ->action('View Document', url('/admin/documents/' . $this->document->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        $data = [
            'document_id' => $this->document->id,
            'title' => $this->document->title,
            'message' => 'A new document has been uploaded.',
        ];
        $data = json_encode($data);
        // Log dữ liệu thông báo

        return $data;
    }
    public function toBroadcast($notifiable)
    {
        return new DocumentUploaded($this->document);
    }
}

