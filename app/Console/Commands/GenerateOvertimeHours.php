<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateOvertimeHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:generate-overtime 
                            {--percentage=30 : Percentage of records to add overtime to}
                            {--min-hours=0.5 : Minimum overtime hours}
                            {--max-hours=4 : Maximum overtime hours}
                            {--from-date=2025-01-01 : Start date}
                            {--to-date= : End date (default: today)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate random overtime hours for existing attendance records';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info("🚀 Starting overtime hours generation...");
        
        // Get all attendance records from 2025
        $attendances = Attendance::whereBetween('date', ['2025-01-01', '2025-08-06'])
            ->whereIn('status', ['present', 'late'])
            ->get();

        $totalRecords = $attendances->count();
        $this->info("📊 Found {$totalRecords} attendance records to process");

        if ($totalRecords === 0) {
            $this->error("No attendance records found!");
            return 1;
        }

        if (!$this->confirm('Do you want to continue and update attendance records with overtime hours?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $progressBar = $this->output->createProgressBar($totalRecords);
        $progressBar->start();

        $updateCount = 0;

        foreach ($attendances as $attendance) {
            // 35% chance of having overtime
            if (rand(1, 100) <= 35) {
                // Generate realistic working hours (8-12 hours)
                $baseHours = 8 + (rand(-30, 30) / 60); // 7.5 to 8.5 base hours
                $overtimeHours = 0.5 + (rand(0, 350) / 100); // 0.5 to 4 overtime hours
                $totalHours = $baseHours + $overtimeHours;
                
                // Generate realistic overtime notes
                $overtimeReasons = [
                    'عمل إضافي لإنهاء المشروع',
                    'اجتماعات مطولة', 
                    'ساعات إضافية مطلوبة',
                    'عمل إضافي اختياري',
                    'تسليم مهام عاجلة',
                    'دعم فني إضافي',
                    'إنهاء التقارير الشهرية',
                    'العمل في أوقات الذروة',
                    'دوام إضافي بطلب الإدارة',
                    'حل المشاكل الفنية',
                    'تدريب الموظفين الجدد'
                ];
                
                $overtimeNote = $overtimeReasons[array_rand($overtimeReasons)];
                $newNote = $attendance->notes ? $attendance->notes . ' - ' . $overtimeNote : $overtimeNote;
                
                // Update the record
                $attendance->update([
                    'working_hours' => round($totalHours, 2),
                    'notes' => $newNote
                ]);
                
                $updateCount++;
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("✅ Successfully updated {$updateCount} records with overtime hours!");
        $this->info("📊 Percentage updated: " . round(($updateCount / $totalRecords) * 100, 1) . "%");

        // Show final statistics
        $this->showFinalStatistics();

        return 0;
    }

    /**
     * Show final statistics after update
     */
    private function showFinalStatistics(): void
    {
        $this->newLine();
        $this->info("📈 Final Statistics:");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $overtimeRecords = Attendance::whereBetween('date', ['2025-01-01', '2025-08-06'])
            ->where('working_hours', '>', 8)
            ->count();

        $totalOvertimeHours = Attendance::whereBetween('date', ['2025-01-01', '2025-08-06'])
            ->where('working_hours', '>', 8)
            ->get()
            ->sum(function ($attendance) {
                return max(0, floatval($attendance->working_hours) - 8);
            });

        $averageOvertimeHours = $overtimeRecords > 0 ? $totalOvertimeHours / $overtimeRecords : 0;

        $this->line("⏰ Records with overtime (>8 hours): " . number_format($overtimeRecords));
        $this->line("🕒 Total overtime hours generated: " . round($totalOvertimeHours, 2));
        $this->line("📊 Average overtime per record: " . round($averageOvertimeHours, 2) . " hours");
        
        $this->newLine();
        $this->info("✨ Overtime generation completed successfully!");
    }

    /**
     * Generate random overtime hours with realistic distribution
     */
    private function generateRandomOvertimeHours(float $min, float $max): float
    {
        // Create realistic overtime distribution
        $scenarios = [
            ['weight' => 40, 'min' => $min, 'max' => 1.5],      // 40% - Short overtime (0.5-1.5 hours)
            ['weight' => 35, 'min' => 1.5, 'max' => 2.5],      // 35% - Medium overtime (1.5-2.5 hours)
            ['weight' => 20, 'min' => 2.5, 'max' => 3.5],      // 20% - Long overtime (2.5-3.5 hours)
            ['weight' => 5,  'min' => 3.5, 'max' => $max],     // 5% - Very long overtime (3.5-4 hours)
        ];

        $random = rand(1, 100);
        $cumulative = 0;

        foreach ($scenarios as $scenario) {
            $cumulative += $scenario['weight'];
            if ($random <= $cumulative) {
                $range = $scenario['max'] - $scenario['min'];
                return round($scenario['min'] + (mt_rand() / mt_getrandmax()) * $range, 2);
            }
        }

        // Fallback
        return round($min + (mt_rand() / mt_getrandmax()) * ($max - $min), 2);
    }

    /**
     * Generate appropriate overtime note
     */
    private function generateOvertimeNote(?string $existingNote, float $overtimeHours): ?string
    {
        $overtimeReasons = [
            'عمل إضافي لإنهاء المشروع',
            'اجتماعات مطولة',
            'ساعات إضافية مطلوبة',
            'عمل إضافي اختياري',
            'تسليم مهام عاجلة',
            'دعم فني إضافي',
            'تدريب الموظفين الجدد',
            'إنهاء التقارير الشهرية',
            'حل المشاكل الفنية',
            'دوام إضافي بطلب الإدارة',
            'مساعدة الزملاء في المشاريع',
            'العمل في أوقات الذروة'
        ];

        $overtimeNote = $overtimeReasons[array_rand($overtimeReasons)];
        
        if ($overtimeHours >= 3) {
            $overtimeNote = 'عمل إضافي مطول - ' . $overtimeNote;
        }

        if ($existingNote) {
            return $existingNote . ' - ' . $overtimeNote;
        }

        return $overtimeNote;
    }

    /**
     * Show summary statistics after update
     */
    private function showSummaryStatistics(string $fromDate, string $toDate): void
    {
        $this->newLine();
        $this->info("📊 Summary Statistics:");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $totalRecords = Attendance::whereBetween('date', [$fromDate, $toDate])->count();
        $overtimeRecords = Attendance::whereBetween('date', [$fromDate, $toDate])
            ->where('working_hours', '>', 8)->count();
        
        $totalOvertimeHours = Attendance::whereBetween('date', [$fromDate, $toDate])
            ->where('working_hours', '>', 8)
            ->get()
            ->sum(function ($attendance) {
                return max(0, $attendance->working_hours - 8);
            });

        $averageOvertimeHours = $overtimeRecords > 0 ? $totalOvertimeHours / $overtimeRecords : 0;

        $this->line("📅 Period: {$fromDate} to {$toDate}");
        $this->line("📊 Total attendance records: " . number_format($totalRecords));
        $this->line("⏰ Records with overtime: " . number_format($overtimeRecords));
        $this->line("📈 Overtime percentage: " . round(($overtimeRecords / $totalRecords) * 100, 1) . "%");
        $this->line("🕒 Total overtime hours: " . round($totalOvertimeHours, 2));
        $this->line("📊 Average overtime per record: " . round($averageOvertimeHours, 2) . " hours");
        
        $this->newLine();
        $this->info("✨ Overtime generation completed successfully!");
    }
}
