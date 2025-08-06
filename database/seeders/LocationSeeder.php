<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\LocationType;
use App\Models\Employee;
use App\Models\Project;
use Faker\Factory as Faker;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        
        // الحصول على أنواع المواقع والموظفين والمشاريع المتاحة
        $locationTypes = LocationType::all();
        $managers = Employee::where('status', 'active')
            ->whereIn('role', ['manager', 'supervisor', 'admin'])
            ->get();
        $projects = Project::all();

        // أسماء المواقع بالعربية
        $locationNames = [
            'موقع الرياض الرئيسي',
            'مستودع الدمام الكبير',
            'فرع جدة التجاري',
            'موقع الخبر الصناعي',
            'مجمع مكة الإداري',
            'مستودع المدينة المنورة',
            'موقع الطائف الجبلي',
            'فرع الأحساء الزراعي',
            'مجمع القصيم التجاري',
            'موقع حائل الشمالي',
            'مستودع تبوك الحدودي',
            'فرع عسير الجنوبي',
            'موقع نجران الحديث',
            'مجمع الباحة السياحي',
            'مستودع الجوف الكبير',
            'فرع سكاكا الإداري',
            'موقع رفحاء الحدودي',
            'مجمع عرعر الصناعي',
            'مستودع القريات التجاري',
            'فرع طريف الشمالي',
            'موقع الرس الزراعي',
            'مجمع بريدة الرئيسي',
            'مستودع عنيزة الحديث',
            'فرع البكيرية الصغير',
            'موقع المذنب الإداري',
            'مجمع الزلفي التجاري',
            'مستودع شقراء الكبير',
            'فرع الدوادمي الجنوبي',
            'موقع الأفلاج الزراعي',
            'مجمع وادي الدواسر الحديث',
            'مستودع السليل الصحراوي',
            'فرع الخرج الإنتاجي',
            'موقع الدلم الإداري',
            'مجمع المزاحمية التجاري',
            'مستودع ضرما الأخير'
        ];

        // المدن السعودية
        $cities = [
            'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 'الخبر', 'الطائف',
            'بريدة', 'تبوك', 'خميس مشيط', 'الأحساء', 'حفر الباطن', 'الجبيل', 'نجران',
            'ينبع', 'الباحة', 'عرعر', 'سكاكا', 'جازان', 'القطيف', 'الرس', 'عنيزة',
            'الظهران', 'الخفجي', 'رابغ', 'القريات', 'طريف', 'الدوادمي', 'الزلفي',
            'الأفلاج', 'وادي الدواسر', 'الخرج', 'شقراء', 'المذنب', 'البكيرية'
        ];

        // المناطق السعودية
        $regions = [
            'منطقة الرياض', 'منطقة مكة المكرمة', 'المنطقة الشرقية', 'منطقة عسير',
            'منطقة المدينة المنورة', 'منطقة القصيم', 'منطقة حائل', 'منطقة تبوك',
            'منطقة الحدود الشمالية', 'منطقة جازان', 'منطقة نجران', 'منطقة الباحة', 'منطقة الجوف'
        ];

        // إنشاء 35 موقع
        for ($i = 0; $i < 35; $i++) {
            $locationType = $faker->randomElement($locationTypes);
            $manager = $faker->randomElement($managers);
            $project = $projects->isNotEmpty() ? $faker->optional(0.7)->randomElement($projects) : null;
            
            Location::create([
                'name' => $locationNames[$i],
                'type' => $locationType->name,
                'location_type_id' => $locationType->id,
                'address' => $faker->address,
                'city' => $faker->randomElement($cities),
                'region' => $faker->randomElement($regions),
                'coordinates' => $faker->latitude . ',' . $faker->longitude,
                'description' => $faker->paragraph(2),
                'status' => $faker->randomElement(['active', 'inactive', 'maintenance']),
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
                'contact_phone' => $faker->phoneNumber,
                'area_size' => $faker->randomFloat(2, 100, 10000), // مساحة من 100 إلى 10000 متر مربع
                'project_id' => $project ? $project->id : null,
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
