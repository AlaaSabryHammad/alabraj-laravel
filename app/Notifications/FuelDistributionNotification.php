<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\FuelDistribution;

class FuelDistributionNotification extends Notification
{
    use Queueable;

    protected $fuelDistribution;

    /**
     * Create a new notification instance.
     */
    public function __construct(FuelDistribution $fuelDistribution)
    {
        $this->fuelDistribution = $fuelDistribution;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('إشعار توزيع محروقات - شركة الأبراج')
            ->greeting('السلام عليكم ورحمة الله وبركاته')
            ->line('تم تسجيل توزيع محروقات للمعدة الموكولة لك.')
            ->line('تفاصيل التوزيع:')
            ->line('المعدة المستهدفة: ' . $this->fuelDistribution->targetEquipment->name)
            ->line('نوع المحروقات: ' . $this->fuelDistribution->fuel_type_text)
            ->line('الكمية الموزعة: ' . $this->fuelDistribution->quantity . ' لتر')
            ->line('تاريخ التوزيع: ' . $this->fuelDistribution->distribution_date->format('Y-m-d'))
            ->line('موزع بواسطة: ' . $this->fuelDistribution->distributedBy->name)
            ->line('حالة الموافقة: ' . $this->fuelDistribution->approval_status_text)
            ->action('عرض التفاصيل', url('/fuel-management'))
            ->line('شكراً لكم لاستخدام نظام إدارة شركة الأبراج للمقاولات');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'إشعار توزيع محروقات',
            'message' => 'تم توزيع ' . $this->fuelDistribution->fuel_type_text .
                ' للمعدة ' . $this->fuelDistribution->targetEquipment->name .
                ' بكمية ' . $this->fuelDistribution->quantity . ' لتر',
            'fuel_distribution_id' => $this->fuelDistribution->id,
            'target_equipment_id' => $this->fuelDistribution->target_equipment_id,
            'target_equipment_name' => $this->fuelDistribution->targetEquipment->name,
            'fuel_type' => $this->fuelDistribution->fuel_type_text,
            'quantity' => $this->fuelDistribution->quantity,
            'distribution_date' => $this->fuelDistribution->distribution_date->format('Y-m-d'),
            'distributed_by' => $this->fuelDistribution->distributedBy->name,
            'approval_status' => $this->fuelDistribution->approval_status_text,
        ];
    }
}
