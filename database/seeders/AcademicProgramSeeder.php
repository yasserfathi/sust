<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\College;
use App\Models\Department;
use App\Models\AcademicProgram;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AcademicProgramSeeder extends Seeder
{
    public function run(): void
    {
        $inputData = <<<DATA
COLNAME	DEPNAME	ACERT_NAME	ECERT_NAME	NOOFYEARSNO	NOOFSEM
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس العلوم في الدراسات التجارية (إدارةالاعمال )	The Bachelor of Science in Business Studies ( BUSINESS ADMINISTRATION )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس العلوم في الدراسات التجارية (البنوك والتمويل )	The Bachelor of Science in Business Studies ( BANKING & FINANCE )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس العلوم في الدراسات التجارية (الإقتصاد التطبيقي )	The Bachelor of Science in Business Studies ( APPLIED ECONOMICS )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس العلوم في الدراسات التجارية (المحاسبة والتمويل )	The Bachelor of Science in Business Studies ( ACCOUNTING & FINANCE )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس التربية (علم نفس )	The degree of Bachelor of Education in ( Psychology )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس  علوم الإتصال (العلاقات العامة والإعلان )	 The degree of Bachelor of communication scince in ( Public Relations & Advertising )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكلاريوس الآداب ( لغة عربية)	The degree of Bachelor of Arts in (Arabic Language )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس الفنون الجميلة والتطبيقية ( التصميم الداخلي )	The degree of Bachelor of Fine and Applied Art in ( Interior Design )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس التربية (لغة عربية )	The degreeof Bachlor of Education in ( Arabic Language )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس التربية ( أساس )	The degree of Bachelor of Education in ( Basic )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس التربية ( لغة انجليزية )	The degree of Bachelor of Education in ( English Language )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس الفنون الجميلة والتطبيقية ( التصميم الداخلي )	The degree of Bachelor of Fine and Applied Art in ( Interior Design )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس الآداب (لغة إنجليزية )	The degree of Bachelor of Arts in ( English Language )	4	8
عمادة التعلبم عن بعد	الانتساب الداخلى	بكالريوس العلوم في (نظم المعلومات الإدارية )	The Bachelor of Science in ( MANAGEMENT INFORMATION SYSTEMS )	4	8
كلية التربية 	قسم العلوم	بكالريوس (شرف) التربية "كيمياء"	The Degree of Bachelor of Education (Honours) in "Chemistry"	4	8
كلية التربية 	قسم اللغات	بكالريوس (شرف) التربية "لغة إنجليزية"	The Degree of Bachelor of Education (Honours) in "English Language"	4	8
كلية التربية 	قسم اللغات	بكالريوس (شرف) التربية "لغة فرنسية"	The Degree of Bachelor of Education (Honours) in "French Language"	4	8
كلية التربية 	قسم اللغات	بكالريوس (شرف) التربية "لغة عربية"	The Degree of Bachelor of Education (Honours) in "Arabic Language"	4	8
كلية التربية 	قسم التربية التقنية	بكالريوس (شرف) التربية التقنية  ''مدنية''	The Degree of Bachelor of Education (Honours) in Technical Education "Civil"	4	8
كلية التربية 	قسم التربية فنون	بكالريوس (شرف) التربية  "تربية فنية"	The Degree of Bachelor of Education (Honours) in "Art Education"	4	8
كلية التربية 	قسم العلوم	بكالريوس (شرف) التربية "رياضيات"	The Degree of Bachelor of Education (Honours) in "Mathematics"	4	8
كلية التربية 	قسم علم النفس	بكالريوس (شرف) التربية "علم النفس"	The Degree of Bachelor of Education (Honours) in "Psychology"	4	8
كلية التربية 	قسم العلوم	بكالريوس (شرف) التربية "فيزياء"	The Degree of Bachelor of Education (Honours) in "Physics"	4	8
كلية التربية 	التربية اساس	بكالريوس (شرف) التربية "أساس"	The Degree of Bachelor of Education (Honours) in "Basic"	4	8
كلية التربية 	قسم التربية التقنية	بكالريوس (شرف) التربية  التقنية  "ميكانيكا"	The Degree of Bachelor of Education (Honours) in Technical Education "Mechanics"	4	8
كلية التربية 	قسم التربية التقنية	بكالريوس (شرف) التربية التقنية  "كهرباء"	The Degree of Bachelor of Education (Honours) in Technical Education "Electrical''	4	8
كلية التربية 	قسم علم النفس	بكالريوس (شرف) التربية "علم النفس"	The Degree of Bachelor of Education (Honours) in "Psychology"	4	8
كلية التربية 	قسم اللغات	بكالريوس (شرف) التربية "لغة عربية"	The Degree of Bachelor of Education (Honours) in "Arabic Language"	4	8
كلية التربية 	قسم اللغات	بكالريوس (شرف) التربية "لغة إنجليزية"	The Degree of Bachelor of Education (Honours) in "English Language"	4	8
كلية التربية 	قسم اللغات	بكالريوس (شرف) التربية "لغة فرنسية"	The Degree of Bachelor of Education (Honours) in "French Language"	4	8
كلية التربية 	التربية اساس	بكالريوس (شرف) التربية "أساس"	The Degree of Bachelor of Education (Honours) in "Basic"	4	8
كلية التربية البدنية والرياضة 	قسم التربية الرياضية	بكالوريوس التربية البدنية والرياضة	College Of Physical Education & Sport	4	8
كلية التربية البدنية والرياضة 	قسم الإدارة الرياضية	درجة بكالوريوس التربية البدنية والرياضة في الإدارة الرياضية	The degree of Bachelor of Physical Education and Sport in Sport Administration	4	8
كلية التربية البدنية والرياضة 	قسم الإعلام الرياضي	درجة بكالوريوس التربية البدنية والرياضة في الإعلام الرياضي	The degree of Bachelor of Physical Education and Sport in Sport Media	4	8
كلية التربية البدنية والرياضة 	قسم التربية البدنية المدرسية	درجة بكالوريوس التربية البدنية والرياضة في التربية البدنية المدرسية	The degree of Bachelor of Physical Education and Sport in School Physical Education	4	8
كلية التربية البدنية والرياضة 	قسم التدريب الرياضي	درجة بكالوريوس التربية البدنية والرياضة في التدريب الرياضي	The degree of Bachelor of Physical Education and Sport in Sport Coaching	4	8
كلية التربية البدنية والرياضة 	قسم التربية البدنية المدرسية	درجة بكالريوس التربية البدنية والرياضة في التربية البدنية المدرسية	The degree of Bachelor of Physical Education and Sport in School Physical Education	4	8
كلية التربية البدنية والرياضة 	قسم الإدارة الرياضية	درجة بكالوريوس التربية البدنية والرياضة في الإدارة الرياضية	The degree of Bachelor of Physical Education and Sport in Sport Administration	4	8
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في الهندسة الإلكترونية (الاتصالات)	The Diploma in Electronics Engineering (Communications)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في الهندسة الإلكترونية (الحاسوب)	The Diploma in Electronics Engineering (Computer)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في الهندسة المدنية (هندسة الري والمياه)	The Diploma in Civil Engineering (Irrigation and Water Engineering)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في الهندسة الكهربائية	The Diploma in Electrical Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم في هندسة المباني	The Diploma in Architecture (Building)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في هندسة العمارة (عمارة)	The Diploma in Architecture Engineering (Architecture)	3	6
كلية التكنولوجيا 	قسم الدراسات العلمية	الدبلوم في الميكنة الزراعية	The Diploma in Agricultural Mechanization	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في اللغة  العربية	The Diploma in Arabic Language	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في اللغة الانجليزية	The Diploma in English Language	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في المحاسبة والتمويل	The Diploma in Accounting and Finance	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في ادارة الاعمال	The Diploma in Business Administration	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في التجارة الالكترونية	The Diploma in Electronic Commerce	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في علم النفس التطبيقى	The Diploma in Applied Psychology	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في رياض الاطفال	The Diploma in Pre- School	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في الموسيقى	The Diploma in Music	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في الدراما	Electronics Maintenance	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني فى الهندسة المدنية (هندسة البلديات)	The Diploma in Civil Engineering (Municipal Engineering)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني فى هندسة النسيج	The Diploma in Textile Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني فى هندسة الطيران (صيانة الكترونيات الطائرات)	The Diploma in Aeronautical Engineering (Electronics Maintenance)	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم  في تقانة علوم المكتبات	The Diploma in Library Sciences & Technology	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في ادارة المكاتب	The Diploma in Offices Administration	3	6
كلية التكنولوجيا 	دبلومات اضافية القديمة سنتين	دبلومات اضافية القديمة سنتين ميكانيكا	(Maintenance in Aircraft Avionics)طيران الكترونيات	2	6
كلية التكنولوجيا 	دبلومات اضافية القديمة سنتين	دبلومات اضافية القديمة سنتين  معمار	(Mechanical Maintenance طيران الكترونات 2011	2	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	دبلوم أجهزة أشعة	The Diploma in engineering in Radiological and Medical Instrumentation	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	دبلوم هندسة ميكانيكا	سيارات	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	دبلوم الهندسة البيئية	The Diploma in Environmental Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات العلمية	دبلوم علوم الإحصاء	The Diploma in Applied Statistics	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في الفنون الجميلة والتطبيقية (الطباعة والتجليد)	The Diploma in Printing & Binding Section	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في الدراما (التمثيل و الإخراج)	The Diploma in Drama (Directing and Acting)	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في الدراما (النقد و الدراسات الدرامية)	The Diploma in Drama (Criticism and Drama Studies)	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في البنوك و التمويل	The Diploma in Banking and Finance	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في التسويق	The Diploma in Marketing	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في نظم المعلومات المحاسبية	The Diploma in Accounting Information Systems	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في التربية البدنية والرياضية	The Diploma in Physical Education and Sport	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في الخدمة الإجتماعية والعمل الطوعي	The Diploma in Social and Voluntary Work	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم فى هندسة حفر ابار المياه الجوفية	The Diploma in Water Wells Drilling Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم فى الهندسة المدنية (البيئية)	The Diploma in Civil Engineering (Environmental)	3	6
كلية التكنولوجيا 	قسم الدراسات العلمية	الدبلوم في الاحصاء التطبيقى	The Diploma in Applied Statistcs	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في علوم الاتصال (الصحافة والنشر)	The Diploma in Communication Science (Journalism & Publishing)	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في الاقتصاد	The Diploma in Economics	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم  فى هندسة  حفر ابار المياه الجوفية	The Diploma in Water Wells Drilling Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	دبلوم الترجمة	The Diploma in Translation	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	دبلوم تعليم اللغة العربية للناطقين بغيرها سنتان	(Maintenance in Aircraft Mechanics)طيران ميكانيكا 8/9/10	2	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في الهندسة المدنية (هندسة الطرق والنقل)	The Diploma in Civil Engineering (Highway and Transportation)	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في الدراسات المصرفية	The Diploma in Banking Studies	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في التأمين	The Diploma in Insurance	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في الفنون الجميلة والتطبيقية (طباعة المنسوجات)	The Diploma in  Fine And Applied Art (Textile Design & Printing)	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في الفنون الجميلة والتطبيقية (التصميم الإيضاحي)	The Diploma in  Fine And Applied Art (Graphic Design)	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في علوم الاتصال (العلاقات العامة)	The Diploma in Communication Science (Public Relations)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني فى الهندسة المدنية (هندسة التشييد)	The Diploma in Civil Engineering (Construction Engineering)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني فى الهندسة الميكانيكية (هندسة الصيانة)	The Diploma in Mechanical Engineering (Maintenance Engineering)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني فى هندسة المعدات الطبية	The Diploma in Medical Instrumentation Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم فى هندسة النفط	The Diploma in Petroleum Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم  فى هندسة تركيب محطات و شبكات  المياه	The Diploma in Installation of Water Yards and Networks Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات العلمية	الدبلوم في البيطرة والانتاج الحيوانى	The Diploma  in Veterinay & Animal Production	3	6
كلية التكنولوجيا 	قسم الدراسات العلمية	الدبلوم في تقنية المعلومات	The Diploma in Information Technology	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في الهندسة الميكانيكية (هندسة السيارات)	The Diploma in Mechanical Engineering (Automobiles Engineering)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني فى هندسة المساحة	The Diploma in Surveying Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في هندسة الطيران (صيانة ميكانيكا الطائرات)	The Diploma in Aeronautical Engineering (Mechanical Maintenance)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم فى هندسة أجهزة الاشعة والمعدات الطبية	The Diploma in Radiological and Medical Instrumentation Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم فى هندسة وتكنولوجيا الجلود	The Diploma in Leather Engineering Technology	3	6
كلية التكنولوجيا 	قسم الدراسات العلمية	الدبلوم في المختبرات العلمية (الفيزياء)	The Diploma in Laboratory Science (Physics)	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	الدبلوم في الفنون الجميلة والتطبيقية (الخطوط والزخرفة الاسلامية)	The Diploma in  Fine and Applied Art ( Calligraphy and Islamic Ornamentation)	3	6
كلية التكنولوجيا 	قسم الدراسات العلمية	الدبلوم في المختبرات العلمية (الكيمياء)	The Diploma in Laboratory Science (Chemistry)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في هندسة إستكشاف النفط	The Diploma in Petroleum Exploration Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في هندسة نقل وتكرير النفط	The Diploma in Petroleum Transportation Refining Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في الهندسة الميكانيكية (هندسة اللحام)	The Diploma in Mechanical Engineering (Welding Engineering)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في هندسة البلاستيك	The Diploma in Plastics Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في نظم المعلومات الادارية	The Diploma in Management Information Systems	3	6
كلية التكنولوجيا 	قسم الدراسات العلمية	دبلوم مختبرات علمية سنتان	تبريد وتكييف 2011 (Refrigeration and Air Conditioning)	2	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	دبلوم تربية رياضية سنتان	(Ref.&A/Condition) تبريد وتكييف 8/9/10	2	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	دبلوم علم النفس التطبيقي سنتان	- Refrigeration and Air Conditioning تبريد وتكييف 2011	2	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم  فى هندسة تركيب محطات و شبكات  المياه	The Diploma in Installation of Water Yards and Networks Engineering	3	6
كلية التكنولوجيا 	قسم الدراسات العلمية	الدبلوم في الطب البيطري	The Diploma in Veterinary Medicine	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني في الهندسة الميكانيكية (هندسة الإنتاج)	The Diploma in Mechanical Engineering (Production Engineering)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني فى الهندسة الميكانيكية (التبريد والتكييف)	The Diploma in Mechanical Engineering (Refrigeration and Air Conditioning)	3	6
كلية التكنولوجيا 	قسم الدراسات الانسانية	تكميلي دبلوم إعلام	Mechanical Maintenance	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية	الدبلوم التقني فى الهندسة المدنية (هندسة الإنشاءات)	The Diploma in Civil Engineering (Structural Engineering)	3	6
كلية التكنولوجيا 	قسم الدراسات التجارية	الدبلوم في التكاليف والمحاسبة الإدارية	The Diploma in Cost & Management Accounting	3	6
كلية التكنولوجيا 	قسم الدراسات العلمية		(Structural Engineering)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية		 (Structures)	3	6
كلية التكنولوجيا 	قسم الدراسات الهندسية		he Diploma in Architecture Engineering (Arch.)	3	6
كلية الدراسات التجارية	البنوك والتمويل	بكالريوس العلوم فى الدراسات التجارية   (البنوك والتمويل)	the Bachelor of Science in Business Studies (BANKING & FINANCE)	4	8
كلية الدراسات التجارية	 قسم نظم المعلومات الإدارية	بكالريوس العلوم  في (نظم المعلومات الادارية)	the Bachelor of science in (MANAGEMENT INFORMATION SYSTEMS)	4	8
كلية الدراسات التجارية	قسم التكاليف والمحاسبة الإدارية	بكالريوس العلوم فى  الدراسات التجارية   (التكاليف والمحاسبة الادارية)	the Bachelor of Science in Business Studies  (COST & MANAGEMENT  ACCOUNTING)	4	8
كلية الدراسات التجارية	قسم الاقتصاد التطبيقى	بكالريوس العلوم فى  الدراسات التجارية   (الاقتصاد التطبيقى)	the Bachelor of Science in Business Studies  (APPLIED ECONOMICS)	4	8
كلية الدراسات التجارية	قسم السكرتارية	بكالريوس العلوم فى (السكرتارية)	the Bachelor of Science in (SECRETARIAL)	4	8
كلية الدراسات التجارية	قسم إدارة الاعمال	بكالريوس العلوم فى  الدراسات التجارية  ( إدارة الاعمال)	the Bachelor of Science in Business Studies (BUSINESS ADMINISTRATION)	4	8
كلية الدراسات التجارية	قسم المحاسبة والتمويل	بكالريوس العلوم  فى الدراسات التجارية   (المحاسبة والتمويل)	the Bachelor of Science in Business Studies (ACCOUNTING & FINANCE)	4	8
كلية الدراسات التجارية	قسم الاقتصاد التطبيقى	بكالريوس العلوم فى الدراسات التجارية  (الاقتصاد التطبيقي)	the Bachelor of Science in Business Studies (APPLIED ECONOMICS)	4	8
كلية الدراسات التجارية	قسم التسويق	بكالريوس العلوم فى الدراسات التجارية (التسويق)	the Bachelor of Science in Business Studies (MARKETING)	4	8
كلية الدراسات التجارية	 قسم نظم المعلومات الإدارية	بكالريوس العلوم فى (نظم المعلومات الادارية)	the Bachelor of Science in (MANAGEMENT INFORMATION SYSTEMS)	4	8
كلية الدراسات التجارية	قسم السكرتارية	بكالريوس العلوم فى (السكرتارية)	the Bachelor of Science in (SECRETARIAL)	4	8
كلية الدراسات التجارية	قسم المحاسبة والتمويل	بكالريوس العلوم فى الدراسات التجارية (المحاسبة والتمويل)	the Bachelor of Science in Business Studies (ACCOUNTING & FINANCE)	4	8
كلية الدراسات التجارية	قسم التسويق	بكالريوس العلوم فى الدراسات التجارية  (التسويق)	the Bachelor of Science in Business Studies (MARKETING)	4	8
كلية الدراسات التجارية	البنوك والتمويل	بكالريوس العلوم فى الدراسات التجارية  (البنوك والتمويل)	the Bachelor of Science in Business Studies (BANKING & FINANCE)	4	8
كلية الدراسات التجارية	قسم التكاليف والمحاسبة الإدارية	بكالريوس العلوم فى الدراسات التجارية (التكاليف والمحاسبة الادارية)	the Bachelor of Science in Business Studies (COST & MANAGEMENT ACCOUNTING)	4	8
كلية الدراسات التجارية	قسم إدارة الاعمال	بكالريوس العلوم فى الدراسات التجارية (إدارة الاعمال)	the Bachelor of Science in Business Studies (BUSINESS ADMINISTRATION)	4	8
كلية الدراسات الزراعية 	قسم علوم الاسرة والمجتمع	بكالريوس   العلوم (شرف) في الزراعة (علوم الاسرة والمجتمع)	the degree of Bachelor of Science  (Honours) in Agriculture (Family & Community Science)	4	10
كلية الدراسات الزراعية 	الميكنة الزراعية	بكالريوس  العلوم (شرف) في الزراعة (الميكنة الزراعية)	the degree of Bachelor of Science  (Honours) in Agriculture (Agricultural Mechanization)	4	10
كلية الدراسات الزراعية 	قسم الاقتصاد الزراعى	بكالريوس  العلوم (شرف) في الزراعة (الاقتصاد الزراعى)	the degree of Bachelor of Science  (Honours) in Agriculture (Agricultural Economics)	4	10
كلية الدراسات الزراعية 	قسم الانتاج الحيوانى	بكالريوس  العلوم (شرف) في الزراعة (الانتاج الحيوانى)	the degree of Bachelor of Science  (Honours) in Agriculture (Animal Production)	4	10
كلية الدراسات الزراعية 	قسم علوم المحاصيل الحقلية	بكالريوس  العلوم(شرف) في الزراعة (انتاج المحاصيل )	the degree of Bachelor of Science  (Honours) in Agriculture  (Crop Production)	4	10
كلية الدراسات الزراعية 	قسم علوم تكنولوجيا الاغذية	بكالريوس  العلوم (شرف) في الزراعة (علوم وتكنولوجيا الاغذية)	the degree of Bachelor of Science  (Honours) in Agriculture (Food Science &Technology)	4	10
كلية الدراسات الزراعية 	قسم البساتين	بكالريوس العلوم (شرف) في الزراعة ( البساتين)	the degree of Bachelor of Science  (Honours)  in Agriculture (Horticulture)	4	10
كلية الدراسات الزراعية 	قسم علوم التربة والمياه	بكالريوس  العلوم (شرف) في الزراعة (علوم التربة والمياه)	the degree of Bachelor of Science  (Honours) in Agriculture  (Soil & Water Science)	4	10
كلية الدراسات الزراعية 	قسم وقاية النبات	بكالريوس العلوم (شرف) في الزراعة (وقاية النبات)	the degree of Bachelor of Science  (Honours) in Agriculture (Plant Protection)	4	10
كلية الدراسات الزراعية 	قسم الارشاد الزراعى والتنمية الريفية	بكالريوس  العلوم (شرف) في الزراعة(الارشاد الزراعى والتنمية الريفية)	the degree of Bachelor of Science  (Honours) in Agriculture (Agricultural Extension & Rural Development)	4	10
كلية الدراسات الزراعية 	قسم الهندسة الزراعية	بكالريوس العلوم (شرف) في الزراعة (الهندسة الزراعية)	the degree of Bachelor of Science  (Honours) in Agriculture (Agricultural Engineering)	5	10
كلية الصيدلة	قسم الصيدلة	درجة بكالريوس (الشرف) في الصيدلة	The Degree of Bachelor (Honours) in Pharmacy	5	10
كلية الطب	قسم الطب والجراحة	درجة البكالريوس في الطب والجراحة	Bachelor of Medicine and Surgery	7	14
كلية الطب البيطري	قسم الطب وجراحة الحيوان	درجة البكالوريوس (شرف) فى الطب البيطرى	The Degree of Bachelor of Science (Honours) in Veterinary Medicine	4	10
كلية الطب البيطري	قسم الطب وجراحة الحيوان	درجة البكالوريوس (شرف) في طب وجراحة الحيوان	The Degree of Bachelor of Science (Honours) in Veterinary Medicine & Surgery	4	10
كلية الطب البيطري	قسم اللحوم	درجة البكالوريوس (شرف) في علوم وتكنولوجيا الإنتاج الحيواني (اللحوم)	The degree of Bachelor of Science (Honours) in Animal Production Science & Technology (Meat)	4	10
كلية الطب البيطري	قسم الانتاج الحيوانى	درجة البكالوريوس (شرف) في علوم وتكنولوجيا الإنتاج الحيواني	The degree of Bachelor of Science (Honours) in Animal Production Science & Technology	5	10
كلية الطب البيطري	قسم الدواجن	درجة البكالوريوس (شرف) في علوم وتكنولوجيا الإنتاج الحيواني (الدواجن)	The degree of Bachelor of Science (Honours) in Animal Production Science & Technology (Poultry)	4	10
كلية الطب البيطري	قسم الألبان	درجة البكالوريوس (شرف) في علوم وتكنولوجيا الإنتاج الحيواني (الألبان)	The degree of Bachelor of Science (Honours) in Animal Production Science & Technology (Dairy)	4	10
كلية الطب البيطري	قسم علوم الاسماك والحياة البرية	درجة البكالوريوس (شرف) في علوم الاسماك والحياة البرية	The degree of Bachelor of Science (Honours) in Fisheries & Wildlife Science	4	10
كلية الطب البيطري	دبلوم بيطرة التابع للبيطرة القديم	الدبلوم في البيطرة والإنتاج الحيواني بعد إكمال المنهج المقرر في ثلاث سنوات	The Diploma in Veterinary & Animal Production	3	10
كلية العلوم	قسم المختبرات العلمية	درجة بكالوريوس العلوم (شرف) في المختبرات العلمية (الكيمياء)	the degree of Bachelor of Science (Honours) in Scientific Laboratories (Chemistry)	4	8
كلية العلوم	قسم الفيزياء	درجة بكالوريوس العلوم (شرف) في الفيزياء	the degree of Bachelor of Science (Honours) in Physics	5	10
كلية العلوم	قسم الاحصاء	درجة بكالوريوس العلوم (شرف) في الإحصاء	the degree of Bachelor of Science (Honours) in Statistics	5	10
كلية العلوم	قسم الرياضيات	درجة بكالوريوس العلوم (شرف) في الرياضيات	the degree of Bachelor of Science (Honours) in Mathematics	5	10
كلية العلوم	قسم المختبرات العلمية	درجة بكالوريوس العلوم (شرف) في المختبرات العلمية (الفيزياء)	the degree of Bachelor of Science (Honours) in Scientific Laboratories (Physics)	4	8
كلية العلوم	قسم الكيمياء	درجة بكالوريوس العلوم (شرف) في الكيمياء	the degree of Bachelor of Science (Honours) in Chemistry	5	10
كلية العلوم	قسم الفيزياء	درجة بكالوريوس العلوم (شرف) في الفيزياء	the degree of Bachelor of Science (Honours) in Physics	4	8
كلية العلوم	قسم الاحصاء	درجة بكالوريوس العلوم (شرف) في الإحصاء التطبيقي	the degree of Bachelor of Science (Honours) in Applied Statistics	4	8
كلية العلوم	قسم الجيولوجيا	درجة بكالوريوس العلوم (شرف) في الجيولوجيا	the degree of Bachelor of Science (Honours) in Geology	5	10
كلية العلوم	بكالريوس تكنولوجي فيزياء	درجة البكالريوس التكنولوجي (شرف) في الفيزياء ( تقنيات الطاقة المتجددة)	the degree of Bachelor of Technology (Honors) in Physics (Renewable Energy Techniques)	5	10
كلية العمارة والتخطيط	العمارة والتخطيط	بكلاريوس العلوم (شرف) فى العمارة والتخطيط (التصميم المعمارى)	Bachelor of Science (Honours) in Architecture and Planning (Architectural Design)	5	10
كلية الفنون الجميلة والتطبيقية 	التصميم الصناعي	درجة بكالريوس الفنون الجميلة والتطبيقية في (التصميم الصناعي )	The degree of Bachelor of Fine and Applied Art in( Industrial Design)	4	8
كلية الفنون الجميلة والتطبيقية 	النحت	درجة بكالريوس الفنون الجميلة والتطبيقية في ( النحت )	The degree of Bachelor of Fine and Applied Art in(Sculpture )	4	8
كلية الفنون الجميلة والتطبيقية 	الخطوط والزخرفة الاسلامية	درجة بكالريوس الفنون الجميلة والتطبيقية في (الخطوط والزخرفة الاسلامية )	The degree of Bachelor of Fine and Applied Art in(Calligraphy &Islamic Ornamentation)	4	8
كلية الفنون الجميلة والتطبيقية 	التصميم الإيضاحي	درجة بكالريوس الفنون الجميلة والتطبيقية في (التصميم الإيضاحي)	The degree of Bachelor of Fine and Applied Art in (Graphic Design)	4	8
كلية الفنون الجميلة والتطبيقية 	الطباعة والتجليد	درجة بكالريوس الفنون الجميلة والتطبيقية في (الطباعة والتجليد )	The degree of Bachelor of Fine and Applied Art in (Printing & Binding)	4	8
كلية الفنون الجميلة والتطبيقية 	تلوين	درجة بكالريوس الفنون الجميلة والتطبيقية في  (التلوين )	The degree of Bachelor of Fine and Applied Art in (Painting)	4	8
كلية الفنون الجميلة والتطبيقية 	الخزف	درجة بكالريوس الفنون الجميلة والتطبيقية في ( الخزف )	The degree of Bachelor of Fine and Applied Art in ( Ceramics)	4	8
كلية الفنون الجميلة والتطبيقية 	التصميم الداخلي	درجة بكالريوس الفنون الجميلة والتطبيقية في  (التصميم الداخلي )	The degree of Bachelor of Fine and Applied Art in ( Interior Design)	4	8
كلية الفنون الجميلة والتطبيقية 	تصميم وطباعة المنسوجات	درجة بكالريوس الفنون الجميلة والتطبيقية في تصميم وطباعة المنسوجات ( تصميم الازياء)	The degree of Bachelor of Fine and Applied Art in Textile Design and Printing (Fashion Design)	4	8
كلية الفنون الجميلة والتطبيقية 	تصميم وطباعة المنسوجات	درجة بكالريوس الفنون الجميلة والتطبيقية في (تصميم وطباعة المنسوجات)	The degree of Bachelor of Fine and Applied Art in (Textile Design and Printing)	4	8
كلية اللغات 	قسم اللغة الفرنسية	درجة بكالريوس الآداب في اللغة الفرنسية	the degree of Bachelor of Arts in French Language	4	8
كلية اللغات 	قسم اللغة العربية	درجة بكالريوس الآداب في اللغة العربية	the degree of Bachelor of Arts in Arabic Language	4	8
كلية اللغات 	قسم اللغة الإنجليزية	درجة بكالريوس الآداب في اللغة الانجليزية	 the degree of Bachelor of Arts in English Language	4	8
كلية اللغات 	قسم اللغة العربية	درجة بكالريوس الآداب في اللغة العربية	the degree of Bachelor of Arts in Arabic Language	4	8
كلية اللغات 	قسم اللغة الفرنسية	درجة بكالريوس الآداب في اللغة الفرنسية	the degree of Bachelor of Arts in French Language	4	8
كلية اللغات 	قسم اللغة الإنجليزية	درجة بكالريوس الآداب في اللغة الإنجليزية	the degree of Bachelor of Arts in English Language	4	8
كلية الموسيقى والدراما 	قسم الدراما	درجة بكلاريوس الدراما  ( نقد ودراسات درامية)	the degree of Bachelor of DRAMA (CRITICISM & DRAMA STUDIES )	4	8
كلية الموسيقى والدراما 	قسم الموسيقى	بكلاريوس  الموسيقى (اكورديون)	The Degree of Bachelor of MUSIC (Accordion)	5	10
كلية الموسيقى والدراما 	قسم الدراما	بكلاريوس الدراما ( إخراج )	The Degree of Bachelor of MUSIC (DIRECTING )	4	8
كلية الموسيقى والدراما 	قسم الدراما	بكلاريوس الدراما  ( فنيات مسرح)	The Degree of Bachelor of Drama  (STAGE DESIGN)	4	8
كلية الموسيقى والدراما 	قسم الدراما	بكلاريوس الدراما  ( تمثيل)	The Degree of  Bachelor of DRAMA  (Acting)	4	8
كلية الموسيقى والدراما 	قسم الموسيقى	بكلاريوس الموسيقى (تأليف)	The Honors Degree of Bachelor of MUSIC in (Composition)	5	10
كلية الموسيقى والدراما 	قسم الموسيقى	بكلاريوس شرف الموسيقى ( بيانو)	The Honors Degree of Bachelor of MUSIC  (PIANO)	5	10
كلية الموسيقى والدراما 	قسم الموسيقى	بكلاريوس شرف الموسيقى (ترمبون)	The Honors Degree of Bachelor of MUSIC  (Trombone)	5	10
كلية الموسيقى والدراما 	قسم الموسيقى	بكلاريوس شرف الموسيقى (ترمبون)	The Honors Degree of Bachelor of MUSIC  (PIANO)	5	10
كلية الموسيقى والدراما 	قسم الدراما	بكلاريوس الدراما  (التمثيل والإخراج)	 The degree of Bachelor of Drama (ACTING & DIRCTING)	4	8
كلية الموسيقى والدراما 	قسم الدراما		 the degree of Bachelor of DRAMA	4	8
كلية الموسيقى والدراما 	قسم الدراما	بكلاريوس الدراما ( راديو و تلفزيون)	 The degree of Bachelor of DRAMA  (Radio & Television)	4	8
كلية الموسيقى والدراما 	قسم الدراما	بكلاريوس الدراما ( التصميم المسرحي )	  the drgree of Bachelor of Drama ( Stage Design )	4	8
كلية الموسيقى والدراما 	قسم الموسيقى	درجة بكالريوس شرف الموسيقى (كونترباص)	The Honors Degree of Bachelor of Music (Caunter Bass)	5	10
كلية الموسيقى والدراما 	قسم الموسيقى		violin	5	10
كلية الموسيقى والدراما 	قسم الموسيقى	بكلاريوس الموسيقى (تأليف)	The Degree of Bachelor of MUSIC  (Vocal)	5	10
كلية الموسيقى والدراما 	قسم الموسيقى	بكلاريوس الموسيقى (الات إيقاعية)	The Degree of Bachelor of MUSIC  (Percussions)	5	10
كلية الموسيقى والدراما 	قسم الموسيقى	بكلاريوس الموسيقى (كونتر باص)	The Degree of Bachelor of MUSIC (Caunter Bass)	5	10
كلية الهندسة	الهندسة  الميكانيكية	بكالريوس  الهندسة  الميكانيكية	Bachelor of Engineering (Honours) in Mechanical Engineering	5	10
كلية الهندسة	قسم هندسة النسيج	درجة بكالريوس الهندسة (الشرف) في هندسة النسيج	the Degree of Bachelor of Engineering (Honours) in Textile Engineering	5	10
كلية الهندسة	الهندسة المدنية	بكالريوس  الهندسة   المدنية	Bachelor of Engineering (Honours) in Civil Engineering	5	10
كلية الهندسة	الهندسة الكهربائية	بكالريوس  الهندسة  الكهربائية	Bachelor of Engineering (Honours) in Electrical Engineering	5	10
كلية الهندسة	الهندسة الالكترونية	درجة بكالريوس الهندسة (الشرف) في هندسة الالكترونيات(الاتصالات)	the Degree of Bachelor of Engineering (Honours) in Electronics Engineering (Communications)	5	10
كلية الهندسة	الهندسة الكهربائية	درجة بكالريوس الهندسة  (الشرف) في الهندسة الكهربائية (قوى وماكينات)	the Degree of Bachelor of Engineering (Honours) in Electrical Engineering (Power & Machines)	5	10
كلية الهندسة	الهندسة المدنية	درجة بكالريوس الهندسة (الشرف) في الهندسة المدنية (الانشاءات)	the Degree of Bachelor of Engineering (Honours) in Civil Engineering (Structures)	5	10
كلية الهندسة	الهندسة المدنية	درجة بكالريوس الهندسة (الشرف) في الهندسة المدنية (التشييد)	the Degree of Bachelor of Engineering (Honours) in Civil Engineering (Constructions)	5	10
كلية الهندسة	الهندسة الكهربائية	درجة بكالريوس الهندسة (الشرف) في الهندسة الكهربائية (تحكم)	the Degree of Bachelor of Engineering (Honours) in Electrical Engineering (Control)	5	10
كلية الهندسة	قسم هندسة الطيران	درجة بكالريوس الهندسة (الشرف) في هندسة الطيران (هياكل ومحركات الطائرات)	the Degree of Bachelor of Engineering (Honours) in Aeronautical Engineering (Airframe & Power Plant)	5	10
كلية الهندسة	الهندسة المدنية	درجة بكالريوس  الهندسة (الشرف) في الهندسة المدنية (الرى والمياه )	the Degree of Bachelor of Engineering (Honours) in Civil Engineering (Hydraulics)	5	10
كلية الهندسة	هندسة المساحة	درجة بكالريوس الهندسة (الشرف) في هندسة المساحة (المساحة الجيوديسيا)	the Degree of Bachelor of Engineering (Honours) in Surveying Engineering (Geodesy)	5	10
كلية الهندسة	هندسة المساحة	درجة بكالريوس الهندسة (الشرف) في هندسة المساحة (نظم المعلومات الجغرافية)	the Degree of Bachelor of Engineering (Honours) in Surveying Engineering (G.I.S)	5	10
كلية الهندسة	هندسة المساحة	درجة بكالريوس الهندسة (الشرف) في هندسة المساحة ( المساحة التصويرية والاستشعار عن بعد)	the Degree of Bachelor of Engineering (Honours) in Surveying Engineering (Photogrammetry & Remote Sensing)	5	10
كلية الهندسة	الهندسة  الميكانيكية	درجة بكالريوس الهندسة (الشرف) في الهندسة الميكانيكية (إنتاج)	the Degree of Bachelor of Engineering (Honours) in Mechanical Engineering (Production)	5	10
كلية الهندسة	الهندسة الالكترونية	درجة بكالريوس الهندسة (الشرف) في هندسة الالكترونيات(الالكترونيات الصناعية)	the Degree of Bachelor of Engineering (Honours) in Electronics Engineering (Industrial Electronics)	5	10
كلية الهندسة	الهندسة المدنية	درجة بكالريوس الهندسة (الشرف) في الهندسة المدنية (الطرق والنقل)	the Degree of Bachelor of Engineering (Honours) in Civil Engineering (Highway & Transportation)	5	10
كلية الهندسة	قسم هندسة  العمارة	درجة بكالريوس  الهندسة (الشرف) في  المعمار (العمارة)	the Degree of Bachelor of Engineering (Honours) in Architecture (Architecture)	5	10
كلية الهندسة	قسم الهندسة النووية	درجة بكالريوس الهندسة (الشرف) في الهندسة النووية	the Degree of Bachelor of Engineering (Honours) in Nuclear Engineering	5	10
كلية الهندسة	قسم هندسة الطيران	درجة بكالريوس الهندسة (الشرف) في هندسة الطيران (كهروالكترونيات)	the Degree of Bachelor of Engineering (Honours) in Aeronautical Engineering (Avionics)	5	10
كلية الهندسة	قسم الهندسة الكيميائية	درجة بكالريوس الهندسة (الشرف) في الهندسة الكيميائية	the Degree of Bachelor of Engineering (Honours) in Chemical Engineering	5	10
كلية الهندسة	قسم الهندسة الطبية الحيوية	درجة بكالريوس  الهندسة (الشرف) في الهندسة  الطبية الحيوية	the Degree of Bachelor of Engineering (Honours) in Biomedical Engineering	5	10
كلية الهندسة	الهندسة  الميكانيكية	درجة بكالريوس الهندسة (الشرف) في الهندسة الميكانيكية (قدرة)	the Degree of Bachelor of Engineering (Honours) in Mechanical Engineering (Power)	5	10
كلية الهندسة	قسم هندسة البلاستيك	درجة بكالريوس الهندسة (الشرف) في هندسة البلاستيك	the Degree of Bachelor of Engineering (Honours) in Plastic Engineering	5	10
كلية الهندسة	قسم هندسة الجلود	درجة بكالريوس الهندسة (الشرف) في هندسة الجلود	the Degree of Bachelor of Engineering (Honours) in Leather Engineering	5	10
كلية الهندسة	الهندسة الكهربائية	درجة البكالريوس التكنولوجي (الشرف) في الهندسة الكهربائية	the Degree of Bachelor of Technology (Honours) in Electrical Engineering	5	10
كلية الهندسة	الهندسة  الميكانيكية	درجة البكالريوس التكنولوجي (الشرف) في  الهندسة الميكانيكية (قدرة)	the Degree of Bachelor of Technology (Honours) in Mechanical Engineering (Power)	5	10
كلية الهندسة	الهندسة المدنية	درجة البكالريوس التكنولوجي (الشرف) في  الهندسة المدنية	the Degree of Bachelor of Technology (Honours) in Civil Engineering	5	10
كلية الهندسة	الهندسة الالكترونية	درجة البكالريوس التكنولوجي (الشرف) في  الهندسة الالكترونية	the Degree of Bachelor of Technology (Honours) in Electronics Engineering	5	10
كلية الهندسة	قسم هندسة النسيج	درجة بكالريوس الهندسة (الشرف) في هندسة النسيج (انتاج المنسوجات)	the Degree of Bachelor of Engineering (Honours) in Textile Engineering (Textile Production)	5	10
كلية الهندسة	قسم هندسة النسيج	درجة بكالريوس الهندسة (الشرف) في هندسة النسيج (صناعة الملبوسات الجاهزة)	the Degree of Bachelor of Engineering (Honours) in Textile Engineering (Ready-made Clothing Manufacturing)	5	10
كلية الهندسة	الهندسة  الميكانيكية	درجة البكالريوس التكنولوجي (الشرف) في  الهندسة الميكانيكية (قدرة)	the Degree of Bachelor of Technology (Honours) in Mechanical Engineering (Power)	5	10
كلية الهندسة	الهندسة  الميكانيكية	درجة البكالريوس التكنولوجي (الشرف) في الهندسة الميكانيكية (تصنيع)	the Degree of Bachelor of Technology (Honours) in Mechanical Engineering (Manufacturing)	5	10
كلية الهندسة	الهندسة الالكترونية	درجة بكالريوس  الهندسة (الشرف) في هندسة الالكترونيات(الحاسوب والشبكات)	the Degree of Bachelor of Engineering (Honours) in Electronics Engineering (Computer & Networks)	5	10
كلية طب الأسنان	قسم طب الأسنان	بكالريوس جراحة الفم والاسنان	Bachelor of Dental Surgery	5	10
كلية علوم الاتصال	قسم الوسائط المتعددة	بكالوريوس علوم الاتصال في الوسائط المتعددة	The degree of Bachelor of Communication Science in Multimedia	4	8
كلية علوم الاتصال	قسم الإذاعة	بكالوريوس علوم الاتصال  في الإذاعة ( راديو وتلفزيون)	The degree of Bachelor of Communication science in the Broadcasting (Radio & Television)	4	8
كلية علوم الاتصال	قسم الصحافة والنشر	بكالوريوس علوم الاتصال في الصحافة والنشر	 The degree of Bachelor of Communication Science in Journalism & Publishing	4	8
كلية علوم الاتصال	قسم التصوير والسينما	 بكالوريوس علوم الاتصال في التصوير والسينما	The degree of Bacheior of Communication Science in  photography & Cinema	4	8
كلية علوم الاتصال	قسم العلاقات العامة والإعلان	بكالوريوس علوم الاتصال في العلاقات العامة والإعلان	The degree of Bachelor of Communication Science in Public Relations & Advertising	4	8
كلية علوم الاتصال	قسم الوسائط المتعددة	بكالوريوس علوم الاتصال في الوسائط المتعددة	The degree of Bachelor of Communication Science in  Multimedia	4	8
كلية علوم الاتصال	قسم الإذاعة	بكالوريوس علوم الاتصال  في الإذاعة ( راديو وتلفزيون)	The degree of Bachelor of Communication science in the Broadcasting (Radio & Television)	4	8
كلية علوم الاتصال	قسم الصحافة والنشر	بكالوريوس علوم الاتصال في الصحافة والنشر	The degree of Bachelor of Communication Science in Journalism	4	8
كلية علوم الاتصال	قسم التصوير والسينما	بكالوريوس علوم الاتصال في التصوير والسينما	The degree of Bachelor of Communication Science in  photography &Cinema	4	8
كلية علوم الاتصال	قسم العلاقات العامة والإعلان	 بكالوريوس علوم الاتصال في العلاقات العامة والإعلان	The degree of Bachelor of Communication Science in Public Relation & Advertising	4	8
كلية علوم الاشعة الطبية 	قسم الاشعة العلاجية	درجة بكالوريوس العلوم (شرف) في تكنولوجيا العلاج بالأشعة	The degree of Bachelor of Science (Honours) in Radiotherapy Technology	4	8
كلية علوم الاشعة الطبية 	قسم الاشعة التشخيصية	درجة بكالوريوس العلوم (شرف) في تكنولوجيا الاشعة التشخيصية	The degree of Bachelor of Science (Honours) in Diagnostic Radiological Technology	4	8
كلية علوم الاشعة الطبية 	قسم الاشعة التشخيصية	درجة بكالوريوس العلوم (شرف) في تكنولوجيا الأشعة التشخيصية	The degree of Bachelor of Science (Honours) in Diagnostic Radiological Technology	4	8
كلية علوم الحاسوب وتقانة المعلومات	قسم  الحاسوب نظم المعلومات	درجة البكالريوس (شرف) في الحاسوب ونظم المعلومات	the Degree of Bachelor (HONOURS) of COMPUTER & INFORMATION SYSTEMS	4	8
كلية علوم الحاسوب وتقانة المعلومات	قسم نظم الحاسوب والشبكات	درجة البكالريوس (شرف) في نظم الحاسوب والشبكات	the Degree of Bachelor (HONOURS) of COMPUTER SYSTEMS & NETWORKS	4	8
كلية علوم الحاسوب وتقانة المعلومات	قسم هندسة  البرمجيات	درجة البكالريوس (شرف) في هندسة البرمجيات	the Degree of Bachelor (HONOURS) of SOFTWARE ENGINEERING	4	8
كلية علوم الحاسوب وتقانة المعلومات	قسم علوم الحاسوب	 درجة البكالريوس (شرف) في علوم الحاسوب	the Degree of Bachelor (HONOURS) of COMPUTER SCIENCE	4	8
كلية علوم الحاسوب وتقانة المعلومات	قسم نظم الحاسوب والشبكات	درجة البكالريوس (شرف) في نظم الحاسوب والشبكات	the Degree of Bachelor (HONOURS) of COMPUTER SYSTEMS & NETWORKS	4	8
كلية علوم الحاسوب وتقانة المعلومات	قسم هندسة  البرمجيات	درجة البكاريوس (شرف) في هندسة البرمجيات	the Degree of Bachelor (HONOURS) of SOFTWARE ENGINEERING	4	8
كلية علوم الحاسوب وتقانة المعلومات	قسم تقانة المعلومات	درجة البكالريوس (شرف) في تقانة المعلومات	the Degree of Bachelor (HONOURS) of  INFORMATION TECHNOLOGY	4	8
كلية علوم الحاسوب وتقانة المعلومات	قسم  الحاسوب نظم المعلومات	درجة البكالريوس (شرف) في الحاسوب ونظم المعلومات	the Degree of Bachelor (HONOURS) of COMPUTER & INFORMATION SYSTEMS	4	8
كلية علوم الحاسوب وتقانة المعلومات	قسم علوم الحاسوب	درجة البكالريوس (شرف) في علوم الحاسوب	the Degree of Bachelor (HONOURS) of COMPUTER SCIENCE	4	8
كلية علوم الغابات والمراعى 	قسم المراعى	درجة البكالريوس (شرف)  فى علوم  المراعى	The degree of Bachelor of Science (Honours) in Range Science	4	10
كلية علوم الغابات والمراعى 	قسم الغابات	درجة البكالريوس (شرف) فى علوم  الغابات	The degree of Bachelor of Science (Honours) in Forestry Science	4	10
كلية علوم المختبرات الطبية	قسم الكيمياء السريريه	درجة البكالوريوس (شرف) فى علوم المختبرات الطبية (الكيمياء السريرية)	B.Sc (Honours) in Medical Laboratory Science in ( Clinical Chemistry)	5	10
كلية علوم المختبرات الطبية	قسم الاحياء الدقيقة	درجة البكالوريوس (شرف) فى علوم المختبرات الطبية (علم الأحياء الدقيقة)	B.Sc (Honours) in Medical Laboratory Science in (Microbiology)	5	10
كلية علوم المختبرات الطبية	قسم امراض الانسجة والخلايا	درجة البكالوريوس (شرف) فى علوم المختبرات الطبية (علم امراض الأنسجة والخلايا)	B.Sc (Honours) in Medical Laboratory Science in (Histopathology and Cytology)	5	10
كلية علوم المختبرات الطبية	قسم الطفيليات والحشرات الطبية	درجة البكالوريوس (شرف) فى علوم المختبرات الطبية (علم الطفيليات والحشرات الطبية)	B.Sc (Honours) in Meadical Laboratory Science in (Parasitology and Medical Entomology)	5	10
كلية علوم المختبرات الطبية	القسم العام	درجة البكالوريوس فى علوم المختبرات الطبية	The degree of Bachelor of Science in Medical Laboratory Science	5	10
كلية علوم المختبرات الطبية	قسم علم الدم ومبحث المناعة	درجة البكالوريوس (شرف) في علوم المختبرات الطبية  (علم الدم والمناعة الدموية)	B.Sc (Honours) in Medical Laboratory Science in (Hematology and Immunohematology)	5	10
كلية علوم وتكنولوجيا الانتاج الحيواني 	علوم وتكنولوجيا الانتاج الحيواني	درجة البكالوريوس (شرف) في علوم وتكنولوجيا الانتاج الحيواني	The degree of Bachelor of Science (Honours) in Animal Production Science & Technology	5	10
كلية علوم وتكنولوجيا الانتاج الحيواني 	علوم وتكنولوجيا اللحوم	درجة البكالوريوس (شرف) في علوم وتكنولوجيا الإنتاج الحيواني(اللحوم)	The degree of Bachelor of Science (Honours) in Animal Production Science  & Techology (Meat)	5	10
كلية علوم وتكنولوجيا الانتاج الحيواني 	علوم وتكنولوجيا الالبان	درجة البكالوريوس (شرف) في علوم وتكنولوجيا الإنتاج الحيواني (الألبان)	The degree of Bachelor of Science (Honours) in Animal Production Science & Technology (Dairy)	5	10
كلية علوم وتكنولوجيا الانتاج الحيواني 	علوم وتكنولوجيا الدواجن	درجة البكالوريوس (شرف) في علوم وتكنولوجيا الإنتاج الحيواني (الدواجن)	The degree of Bachelor of Science (Honours) in Animal Production Science & Technology (Poultry)	4	10
كلية علوم وتكنولوجيا الانتاج الحيواني 	علوم الاسماك والحياة البرية	درجة البكالوريوس (شرف) في علوم الاسماك والحياة البرية	The degree of Bachelor of Science (Honours) in Fisheries & Wildlife Science	5	10
كلية هندسة  النفط والتعدين	قسم هندسة النفط	بكالريوس  العلوم (شرف) في هندسة النفط	The Degree of Bachelor of Science (Honours) in Petroleum Engineering	5	10
كلية هندسة  النفط والتعدين	قسم هندسة الاستكشاف	بكالريوس  العلوم (شرف) في هندسة استكشاف النفط	The Degree of Bachelor of Science (Honours) in Petroleum Exploration Engineering	5	10
كلية هندسة  النفط والتعدين	قسم هندسة النقل والتكرير	بكالريوس  العلوم (شرف) في هندسة نقل وتكرير النفط	The Degree of Bachelor of Science (Honours) in Petroleum Transportation & Refining Engineering	5	10
كلية هندسة  النفط والتعدين	قسم هندسة النفط	البكالوريوس التكنولوجي (شرف) في هندسة النفط	The Degree of Bachelor of Technology (Honours) in Petroleum Engineering	5	10
كلية هندسة  النفط والتعدين	قسم هندسة الاستكشاف	البكالوريس التكنولوجى(شرف) فى هندسة استكشاف النفط	The Degree of Bachelor of Technology (Honours) in Petroleum Exploration Engineering	5	10
كلية هندسة  النفط والتعدين	قسم هندسة النقل والتكرير	البكالوريس التكلنولوجى (شرف) فى هندسة نقل و تكرير النفط	The Degree of Bachelor of Technology (Honours) in Transportation & Refining Engineering	5	10
كلية هندسة المياه والبيئة 	الهندسة البيئية	درجة بكالوريوس العلوم (شرف) فى الهندسة البيئية	the Degree of Bachelor of Science (Honours) in Environmental Engineering	5	10
كلية هندسة المياه والبيئة 	هندسة موارد المياه	درجة بكالوريوس العلوم (شرف) في هندسة موارد المياه	the Degree of Bachelor of Science (Honours) in Water Resources Engineering	4	10
كلية هندسة وتكنولوجيا الصناعات	قسم هندسة النسيج	درجة بكالريوس الهندسة (الشرف) في هندسة النسيج (انتاج المنسوجات)	the Degree of Bachelor of Engineering (Honours) in Textile Engineering (Textile Production)	5	10
كلية هندسة وتكنولوجيا الصناعات	قسم الهندسة الكيميائية	درجة بكالريوس الهندسة (الشرف) في الهندسة الكيميائية	the Degree of Bachelor of Engineering (Honours) in Chemical Engineering	5	10
كلية هندسة وتكنولوجيا الصناعات	قسم الهندسة الكيميائية	درجة البكالريوس التكنولوجي (شرف) فى هندسة تصنيع الأغذية	the Degree of Bachelor of Technology (Honours) in Food Processing Engineering	5	10
كلية هندسة وتكنولوجيا الصناعات	قسم هندسة النسيج	درجة بكالريوس الهندسة (الشرف) في هندسة النسيج (صناعة الملبوسات الجاهزة)	the Degree of Bachelor of Engineering (Honours) in Textile Engineering (Ready-made Clothing Manufacturing)	5	10
كلية هندسة وتكنولوجيا الصناعات	قسم هندسة الجلود	درجة بكالريوس العلوم (الشرف) في هندسة الصناعات الجلدية	the Degree of Bachelor (Honours) of Science in Leather Industries Engineering	5	10
كلية هندسة وتكنولوجيا الصناعات	قسم هندسة البلاستيك	درجة بكالريوس الهندسة (الشرف) في هندسة البلاستيك	the Degree of Bachelor of Engineering (Honours) in Plastic Engineering	5	10
كلية هندسة وتكنولوجيا الصناعات	قسم هندسة الجلود	درجة بكالريوس الهندسة (الشرف) في هندسة الجلود	the Degree of Bachelor of Engineering (Honours) in Leather Engineering	5	10
DATA;

        $lines = explode("\n", trim($inputData));
        $header = array_shift($lines);

        // Disable foreign key checks to safely clean table before seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AcademicProgram::query()->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $dbColleges = College::all();
        $collegeMap = [];
        foreach ($dbColleges as $c) {
            $collegeMap[$this->norm($c->name)] = $c;
        }

        $dbDepartments = Department::all();
        $deptMap = [];
        foreach ($dbDepartments as $d) {
            $deptMap[$d->college_id][] = $d;
        }

        foreach ($lines as $line) {
            $cols = explode("\t", trim($line));
            if (count($cols) < 2) continue;

            $rawCol = trim($cols[0]);
            $rawDep = trim($cols[1]);
            $progAr = trim($cols[2] ?? '');
            $progEn = trim($cols[3] ?? '');
            $years = isset($cols[4]) && is_numeric(trim($cols[4])) ? (int)trim($cols[4]) : null;
            $semesters = isset($cols[5]) && is_numeric(trim($cols[5])) ? (int)trim($cols[5]) : null;

            if (empty($progAr)) {
                $progAr = $progEn;
            }

            if (empty($progAr)) continue;

            $normCol = $this->norm($rawCol);
            $normDep = $this->norm($rawDep);

            // 1. Match College strictly against DB
            $college = $collegeMap[$normCol] ?? null;
            if (!$college) {
                foreach ($collegeMap as $normName => $cObj) {
                    if (str_contains($normName, $normCol) || str_contains($normCol, $normName)) {
                        $college = $cObj;
                        break;
                    }
                }
            }

            if (!$college) {
                $college = College::firstOrCreate(
                    ['name' => $rawCol],
                    [
                        'name_en' => $rawCol,
                        'slug' => Str::slug($rawCol),
                        'user_id' => 1,
                        'active' => 1,
                        'logo' => '',
                        'logo_en' => '',
                        'banner' => '',
                        'college_type' => 'college',
                    ]
                );
                $collegeMap[$normCol] = $college;
            }

            // 2. Match Department (EXACTLY 1 department per Excel line for 1-to-1 match)
            $cDepts = $deptMap[$college->id] ?? [];
            $department = null;

            // A. Exact norm match
            foreach ($cDepts as $d) {
                if ($this->norm($d->name) === $normDep) {
                    $department = $d;
                    break;
                }
            }

            // B. Substring match against DB department names
            if (!$department) {
                foreach ($cDepts as $d) {
                    $dNorm = $this->norm($d->name);
                    if (!empty($dNorm) && $dNorm !== 'القسم العام' && (str_contains($normDep, $dNorm) || str_contains($dNorm, $normDep))) {
                        $department = $d;
                        break;
                    }
                }
            }

            // C. Fallback: create department if missing
            if (!$department && !empty($rawDep)) {
                $department = Department::firstOrCreate(
                    ['college_id' => $college->id, 'name' => $rawDep],
                    [
                        'name_en' => $rawDep,
                        'keywords' => '',
                        'description' => '',
                        'keywords_ar' => '',
                        'description_ar' => '',
                        'user_id' => 1,
                        'active' => 1,
                    ]
                );
                $deptMap[$college->id][] = $department;
            }

            if (!$department) {
                $department = Department::firstOrCreate(
                    ['college_id' => $college->id, 'name' => 'القسم العام'],
                    [
                        'name_en' => 'General Department',
                        'keywords' => '',
                        'description' => '',
                        'keywords_ar' => '',
                        'description_ar' => '',
                        'user_id' => 1,
                        'active' => 1,
                    ]
                );
            }

            $progType = $this->determineProgramType($progAr, $progEn);

            // Create EXACTLY 1 DB row per Excel line
            AcademicProgram::create([
                'department_id' => $department->id,
                'program_name' => $progAr,
                'program_type' => $progType,
                'program_name_en' => $progEn ?: $progAr,
                'NOOFYEARSNO' => $years,
                'NOOFSEM' => $semesters,
                'active' => 1,
                'user_id' => 1,
            ]);
        }
    }

    private function determineProgramType($programName, $programNameEn = '') {
        $p = $this->norm($programName);
        $pen = $this->norm($programNameEn);
        if (str_contains($p, 'دبلوم') || str_contains($pen, 'diploma')) return 4;
        if (str_contains($p, 'ماجستير') || str_contains($pen, 'master')) return 2;
        if (str_contains($p, 'دكتوراة') || str_contains($p, 'دكتوراه') || str_contains($pen, 'phd')) return 3;
        return 1;
    }

    private function norm($str) {
        $str = str_replace(['أ', 'إ', 'آ'], 'ا', $str);
        $str = str_replace('ى', 'ي', $str);
        $str = str_replace('ة', 'ه', $str);
        $str = preg_replace('/\s+/', ' ', $str);
        return trim($str);
    }
}
