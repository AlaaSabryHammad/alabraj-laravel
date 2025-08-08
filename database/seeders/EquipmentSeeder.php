<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Models\Location;
use App\Models\Employee;
use Faker\Factory as Faker;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        
        // الحصول على البيانات المطلوبة
        $equipmentTypes = EquipmentType::all();
        $locations = Location::where('status', 'active')->get();
        $drivers = Employee::where('status', 'active')->get();

        // التحقق من وجود البيانات المطلوبة
        if ($equipmentTypes->isEmpty()) {
            $this->command->error('لا توجد أنواع معدات في قاعدة البيانات. يرجى تشغيل EquipmentTypeSeeder أولاً.');
            return;
        }

        if ($locations->isEmpty()) {
            $this->command->error('لا توجد مواقع نشطة في قاعدة البيانات. يرجى إضافة مواقع أولاً.');
            return;
        }

        // قائمة الشركات المصنعة
        $manufacturers = [
            'كاتربيلر', 'كوماتسو', 'فولفو', 'ليبهر', 'مرسيدس', 'سكانيا', 'مان', 'دايو',
            'هيونداي', 'دوسان', 'جي سي بي', 'كيس', 'نيو هولاند', 'هيتاشي', 'كوبيلكو',
            'يونيك', 'تاداو كرين', 'جروف', 'ديماج', 'تيريكس', 'ساني', 'زوملايون'
        ];

        // قائمة أسماء المعدات حسب النوع
        $equipmentNames = [
            'حفار' => [
                'حفار كاتربيلر 320D', 'حفار كوماتسو PC200', 'حفار فولفو EC140E', 'حفار هيتاشي ZX200',
                'حفار دوسان DX225LC', 'حفار هيونداي R220LC', 'حفار جي سي بي JS220LC', 'حفار كيس CX210D',
                'حفار نيو هولاند E215C', 'حفار كوبيلكو SK200', 'حفار ساني SY215C', 'حفار زوملايون ZE215E'
            ],
            'رافعة' => [
                'رافعة ليبهر LTM 1100', 'رافعة جروف RT890E', 'رافعة تاداو AR-2500M', 'رافعة ديماج AC100',
                'رافعة تيريكس RT555', 'رافعة يونيك UR-V375', 'رافعة كاتو NK-200H', 'رافعة كوماتسو RT200',
                'رافعة فولفو RT300', 'رافعة ساني STC750', 'رافعة زوملايون QY50V', 'رافعة هيونداي HLC-85T'
            ],
            'شاحنة' => [
                'شاحنة مرسيدس أكتروس', 'شاحنة سكانيا R450', 'شاحنة مان TGX', 'شاحنة فولفو FH16',
                'شاحنة إيفيكو ستراليس', 'شاحنة داف XF', 'شاحنة هيونداي Xcient', 'شاحنة دونجفنغ KX',
                'شاحنة شاكمان X3000', 'شاحنة فاو J6P', 'شاحنة هوو A7', 'شاحنة فوتون أومان GTL'
            ],
            'جرافة' => [
                'جرافة كاتربيلر D6T', 'جرافة كوماتسو D65PX', 'جرافة شانتوي SD22', 'جرافة ليوجونغ CLG922',
                'جرافة XCMG TY230', 'جرافة Case 1650M', 'جرافة نيو هولاند D180', 'جرافة جون دير 750K',
                'جرافة دريسر TD-15M', 'جرافة ساني SD13YS', 'جرافة زوملايون ZD160-3', 'جرافة دايو S340LC'
            ],
            'خلاطة خرسانة' => [
                'خلاطة فولفو FMX 500', 'خلاطة مرسيدس أريوكس', 'خلاطة سكانيا G450', 'خلاطة مان TGS',
                'خلاطة إيفيكو تراكر', 'خلاطة رينو كيراكس', 'خلاطة هيونداي HD270', 'خلاطة شاكمان F3000',
                'خلاطة دونجفنغ KL', 'خلاطة فاو CA5250', 'خلاطة بيبين BSW', 'خلاطة سيتشيوان SC5250'
            ],
            'مولد كهربائي' => [
                'مولد كاتربيلر C18', 'مولد كمنز QSK19', 'مولد بيركنز 2506A', 'مولد دويتز TCD2015',
                'مولد يانمار 6EY22W', 'مولد إيسوزو 6WG1X', 'مولد دايو P222LE', 'مولد شانجشاي R6105AZLD',
                'مولد ويتشاي WP12D264E200', 'مولد يوتشاي YC6A230L-D20', 'مولد FAW CA6DL1-24E3', 'مولد جاك J190'
            ],
            'ضاغط هواء' => [
                'ضاغط أطلس كوبكو XAS97', 'ضاغط انجرسول راند P185', 'ضاغط دوسان 7/53', 'ضاغط كايزر M43',
                'ضاغط سكرو كوبيلكو', 'ضاغط بورتابل P&H', 'ضاغط ديزل جاكوبس', 'ضاغط ليروي سومر',
                'ضاغط شولاير 650', 'ضاغط كومبايلر C55', 'ضاغط إيرمان KK15', 'ضاغط ألمانيا AC22'
            ],
            'كرين' => [
                'كرين برجي ليبهر 256 HC', 'كرين برجي بوتين MDT 219', 'كرين برجي كوماتسو TC-5513',
                'كرين برجي زوملايون T7015', 'كرين برجي ساني SCT7528', 'كرين برجي XCMG XGT8038',
                'كرين برجي فانغيوان FY8040', 'كرين برجي ترول CL125', 'كرين برجي دونجيانغ TC7135',
                'كرين برجي بيهاي BHS3606', 'كرين برجي جون دير JD190', 'كرين برجي هواكسينغ HX7016'
            ],
            'قلاب' => [
                'قلاب مرسيدس أكتروس', 'قلاب سكانيا P450', 'قلاب مان TGS 35.440', 'قلاب فولفو FMX 460',
                'قلاب إيفيكو تراكر AD450', 'قلاب رينو كيراكس 450', 'قلاب هيونداي HD1000', 'قلاب شاكمان F3000',
                'قلاب دونجفنغ KL3258', 'قلاب فاو CA3250', 'قلاب هوو ZZ3257', 'قلاب فوتون أومان BJ3313'
            ],
            'آلة لحام' => [
                'آلة لحام لينكولن SAE-400', 'آلة لحام ميلر Trailblazer 325', 'آلة لحام ESAB Buddy Arc 180',
                'آلة لحام كيمبو MinArcTig 200', 'آلة لحام فرونيوس TPS 320i', 'آلة لحام تلكو Tekniq 250',
                'آلة لحام أتلاس كوبكو WIA', 'آلة لحام هيونداي MIG-250', 'آلة لحام شينداو MMA-400',
                'آلة لحام ريلون DC-400', 'آلة لحام دايهين DM-350', 'آلة لحام كوريا KMC-200'
            ]
        ];

        // إنشاء 200 معدة
        for ($i = 1; $i <= 200; $i++) {
            $equipmentType = $faker->randomElement($equipmentTypes->toArray());
            $location = $faker->randomElement($locations->toArray());
            $driver = $faker->optional(0.7)->randomElement($drivers->toArray()); // 70% من المعدات لها سائق
            $manufacturer = $faker->randomElement($manufacturers);

            // التحقق من صحة البيانات
            if (!$equipmentType || !isset($equipmentType['name'])) {
                $this->command->warn("تخطي المعدة رقم $i - نوع المعدة غير صالح");
                continue;
            }

            // اختيار اسم مناسب حسب نوع المعدة
            $typeNames = $equipmentNames[$equipmentType['name']] ?? ['معدة ' . $equipmentType['name']];
            $equipmentName = $faker->randomElement($typeNames) . ' - ' . str_pad($i, 3, '0', STR_PAD_LEFT);

            // إنتاج موديل عشوائي
            $models = [
                'كاتربيلر' => ['320D', '330C', '345B', '365C', 'D6T', 'D8T', '966H', '980H'],
                'كوماتسو' => ['PC200', 'PC300', 'D65PX', 'D155AX', 'WA380', 'WA470'],
                'فولفو' => ['EC140E', 'EC210B', 'EC380E', 'L120F', 'A40F', 'FMX500'],
                'مرسيدس' => ['Actros 3351', 'Arocs 3340', 'Atego 1518', 'Unimog U4000'],
                'سكانيا' => ['R450', 'P450', 'G450', 'S730', 'T450'],
                'مان' => ['TGX 18.440', 'TGS 35.440', 'TGL 12.250', 'TGM 18.290']
            ];

            $manufacturerModels = $models[$manufacturer] ?? ['Model-' . $faker->numberBetween(100, 999)];
            $model = $faker->randomElement($manufacturerModels);

            // حالة المعدة
            $statuses = ['available', 'in_use', 'maintenance', 'out_of_order'];
            $statusWeights = [60, 25, 10, 5]; // 60% متاحة، 25% قيد الاستخدام، 10% صيانة، 5% معطلة
            $status = $this->getWeightedRandomStatus($statuses, $statusWeights);

            // تاريخ الشراء والضمان
            $purchaseDate = $faker->dateTimeBetween('-10 years', '-1 month');
            $warrantyYears = $faker->numberBetween(1, 5);
            $warrantyExpiry = (clone $purchaseDate)->modify("+{$warrantyYears} years");

            // السعر حسب نوع المعدة
            $priceRanges = [
                'حفار' => [250000, 800000],
                'رافعة' => [400000, 1200000],
                'شاحنة' => [150000, 500000],
                'جرافة' => [200000, 600000],
                'خلاطة خرسانة' => [180000, 400000],
                'مولد كهربائي' => [50000, 300000],
                'ضاغط هواء' => [30000, 150000],
                'كرين' => [800000, 2000000],
                'قلاب' => [120000, 350000],
                'آلة لحام' => [5000, 25000]
            ];

            $priceRange = $priceRanges[$equipmentType['name']] ?? [50000, 200000];
            $purchasePrice = $faker->randomFloat(2, $priceRange[0], $priceRange[1]);

            Equipment::create([
                'name' => $equipmentName,
                'type' => $equipmentType['name'],
                'type_id' => $equipmentType['id'],
                'model' => $model,
                'manufacturer' => $manufacturer,
                'serial_number' => 'EQP-' . date('Y') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'status' => $status,
                'location_id' => $location['id'],
                'driver_id' => $driver ? $driver['id'] : null,
                'purchase_date' => $purchaseDate,
                'purchase_price' => $purchasePrice,
                'warranty_expiry' => $warrantyExpiry,
                'last_maintenance' => $status === 'maintenance' ? $faker->dateTimeBetween('-6 months', 'now') : $faker->optional(0.8)->dateTimeBetween('-1 year', 'now'),
                'description' => $faker->optional(0.6)->paragraph(1),
                'images' => null, // سيتم إضافة الصور لاحقاً إذا لزم الأمر
                'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * اختيار حالة عشوائية بناء على الأوزان المحددة
     */
    private function getWeightedRandomStatus($statuses, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        for ($i = 0; $i < count($statuses); $i++) {
            $random -= $weights[$i];
            if ($random <= 0) {
                return $statuses[$i];
            }
        }
        
        return $statuses[0]; // افتراضي
    }
}
