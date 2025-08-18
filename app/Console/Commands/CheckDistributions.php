<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FuelDistribution;

class CheckDistributions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:distributions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'فحص بيانات توزيعات المحروقات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== فحص التوزيعات ===');

        $distributions = FuelDistribution::with(['targetEquipment', 'distributedBy', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        $this->info('عدد التوزيعات الكلي: ' . $distributions->count());

        if ($distributions->count() > 0) {
            foreach ($distributions->take(5) as $distribution) {
                $this->info('ID: ' . $distribution->id);
                $this->info('التانكر: ' . $distribution->fuel_truck_id);
                $this->info('المعدة المستهدفة: ' . $distribution->targetEquipment->name);
                $this->info('الكمية: ' . $distribution->quantity . ' لتر');
                $this->info('حالة الموافقة: ' . $distribution->approval_status);
                $this->info('الموزع: ' . $distribution->distributedBy->name);
                $this->info('تاريخ التوزيع: ' . $distribution->distribution_date);
                $this->info('---');
            }
        } else {
            $this->error('لا توجد توزيعات في قاعدة البيانات!');
        }
    }
}
