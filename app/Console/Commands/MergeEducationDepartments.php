<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\College;
use App\Models\Department;
use App\Models\StaffEmploy;
use Illuminate\Support\Facades\DB;

class MergeEducationDepartments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'departments:merge-education';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge sub-departments in College of Education to their main departments and delete the sub-departments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // البحث عن كلية التربية
        $college = College::where('name', 'like', '%كلية التربية%')->first();
        
        if (!$college) {
            $this->error("لم يتم العثور على 'كلية التربية'. تأكد من اسم الكلية في قاعدة البيانات.");
            return;
        }

        $this->info("تم العثور على كلية التربية. جاري معالجة الأقسام...");

        $departments = Department::where('college_id', $college->id)->get();
        $mergedStaffCount = 0;
        $deletedDeptCount = 0;

        foreach ($departments as $dept) {
            // التحقق مما إذا كان اسم القسم يحتوي على شرطة "-"
            if (preg_match('/^(.*?)\s*-\s*(.*)$/', $dept->name, $matches)) {
                $mainName = trim($matches[1]);
                $subName = trim($matches[2]);

                // البحث عن القسم الرئيسي بنفس الكلية
                $mainDept = Department::where('college_id', $college->id)
                                      ->where('name', $mainName)
                                      ->first();

                if (!$mainDept) {
                    $this->warn("القسم الرئيسي '{$mainName}' غير موجود. سيتم إنشاؤه الآن...");
                    $mainDept = Department::create([
                        'college_id' => $college->id,
                        'name' => $mainName,
                        'name_en' => null, // يمكنك إضافة ترجمة لاحقاً
                        'active' => 1,
                    ]);
                }

                // حساب عدد الأساتذة المرتبطين بالقسم الفرعي
                $staffCount = StaffEmploy::where('department_id', $dept->id)->count();

                // نقل الأساتذة إلى القسم الرئيسي
                if ($staffCount > 0) {
                    StaffEmploy::where('department_id', $dept->id)->update([
                        'department_id' => $mainDept->id
                    ]);
                }

                // يمكنك إضافة جداول أخرى هنا إذا كانت مرتبطة بالقسم مثل الأقسام الأكاديمية (AcademicProgram)

                $this->info("تم نقل {$staffCount} أستاذ من '{$dept->name}' إلى '{$mainDept->name}'.");
                $mergedStaffCount += $staffCount;
                
                // محاولة حذف القسم الفرعي
                try {
                    $dept->delete();
                    $this->info("تم حذف القسم الفرعي '{$dept->name}' بنجاح.");
                    $deletedDeptCount++;
                } catch (\Exception $e) {
                    $this->error("فشل حذف القسم '{$dept->name}'. قد يكون مرتبطاً ببيانات في جداول أخرى لا يمكن حذفها.");
                }
            }
        }

        $this->info("=========================================");
        $this->info("عملية الدمج اكتملت!");
        $this->info("إجمالي الأساتذة الذين تم نقلهم: {$mergedStaffCount}");
        $this->info("إجمالي الأقسام الفرعية التي تم حذفها: {$deletedDeptCount}");
    }
}
