<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestFormSubmission extends Command
{
    protected $signature = 'test:form-submission {warehouse_id=41}';
    protected $description = 'Test form submission via HTTP POST';

    public function handle()
    {
        $warehouseId = $this->argument('warehouse_id');

        $this->info("=== اختبار إرسال النموذج عبر HTTP ===");
        $this->line("");

        // بيانات النموذج التجريبية
        $formData = [
            '_token' => 'test-token', // سيكون هناك token حقيقي في الواقع
            'code' => 'HTTP-TEST-' . time(),
            'name' => 'قطعة اختبار HTTP - ' . date('Y-m-d H:i:s'),
            'description' => 'اختبار عبر HTTP POST',
            'category' => 'اختبار',
            'brand' => 'HTTP-TEST',
            'model' => 'MODEL-HTTP',
            'unit_price' => '150.00',
            'unit_type' => 'قطعة',
            'minimum_stock' => '3',
            'supplier' => 'مورد HTTP',
            'location_shelf' => 'الرف B1',
            'initial_quantity' => '5',
            'reference_number' => 'REF-' . time(),
            'notes' => 'اختبار النموذج عبر HTTP'
        ];

        try {
            $this->info("إرسال طلب POST إلى: /warehouses/{$warehouseId}/store-spare-part");

            // محاكاة طلب HTTP
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->post("http://127.0.0.1:8000/warehouses/{$warehouseId}/store-spare-part", $formData);

            $this->info("Status Code: " . $response->status());
            $this->info("Response Headers:");
            foreach ($response->headers() as $key => $values) {
                $this->line("  {$key}: " . implode(', ', $values));
            }

            $this->line("");
            $this->info("Response Body:");
            $this->line(substr($response->body(), 0, 500) . '...');

            if ($response->successful()) {
                $this->info("✅ الطلب نجح!");
            } else {
                $this->error("❌ الطلب فشل!");
            }
        } catch (\Exception $e) {
            $this->error("خطأ في الإرسال: " . $e->getMessage());
        }
    }
}
