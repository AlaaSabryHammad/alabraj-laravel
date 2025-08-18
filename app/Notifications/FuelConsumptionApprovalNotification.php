<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\EquipmentFuelConsumption;

class FuelConsumptionApprovalNotification extends Notification
{
    use Queueable;

    protected $fuelConsumption;

    /**
     * Create a new notification instance.
     */
    public function __construct(EquipmentFuelConsumption $fuelConsumption)
    {
        $this->fuelConsumption = $fuelConsumption;
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
            ->subject('طلب موافقة على استهلاك محروقات - شركة الأبراج')
            ->greeting('السلام عليكم ورحمة الله وبركاته')
            ->line('يوجد طلب جديد يتطلب موافقتكم على استهلاك محروقات للمعدة التالية:')
            ->line('المعدة: ' . $this->fuelConsumption->equipment->name)
            ->line('نوع المحروقات: ' . $this->fuelConsumption->fuel_type_text)
            ->line('الكمية: ' . $this->fuelConsumption->quantity . ' لتر')
            ->line('التاريخ: ' . $this->fuelConsumption->consumption_date->format('Y-m-d'))
            ->line('المُدخل بواسطة: ' . $this->fuelConsumption->user->name)
            ->action('مراجعة الطلب', url('/equipment/' . $this->fuelConsumption->equipment_id))
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
            'title' => 'طلب موافقة على استهلاك محروقات',
            'message' => 'طلب موافقة على استهلاك ' . $this->fuelConsumption->fuel_type_text .
                ' للمعدة ' . $this->fuelConsumption->equipment->name,
            'fuel_consumption_id' => $this->fuelConsumption->id,
            'equipment_id' => $this->fuelConsumption->equipment_id,
            'equipment_name' => $this->fuelConsumption->equipment->name,
            'fuel_type' => $this->fuelConsumption->fuel_type_text,
            'quantity' => $this->fuelConsumption->quantity,
            'consumption_date' => $this->fuelConsumption->consumption_date->format('Y-m-d'),
            'requested_by' => $this->fuelConsumption->user->name,
        ];
    }
}
