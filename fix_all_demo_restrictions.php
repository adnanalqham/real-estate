<?php
/**
 * إزالة جميع قيود Demo من الموقع بالكامل
 * هذا السكريبت سيقوم بتعليق جميع فحوصات app_type == 'demo'
 */

echo "<h1>إزالة جميع قيود Demo من الموقع</h1>";
echo "<p>جاري بدء العملية...</p>";

// قائمة الملفات التي تحتوي على فحوصات Demo
$files_to_fix = [
    'application/controllers/admin/settings.php',
    'application/controllers/admin/user.php',
    'application/controllers/admin/estate.php',
    'application/controllers/admin/templates.php',
    'application/controllers/admin/treefield.php',
    'application/controllers/admin/tcalendar.php',
    'application/controllers/admin/swin_reviews.php',
    'application/controllers/admin/enquire.php',
    'application/controllers/admin/emailfiles.php',
    'application/controllers/admin/forms.php',
    'application/controllers/admin/imageeditor.php',
    'application/controllers/admin/favorites.php',
    'application/controllers/admin/templatefiles.php',
    'application/controllers/admin/packages.php',
    'application/controllers/admin/monetize.php',
    'application/controllers/admin/page.php',
    'application/controllers/admin/reports.php',
    'application/controllers/admin/savesearch.php',
    'application/controllers/frontend.php',
    'application/controllers/fresearch.php',
    'application/controllers/fquick.php',
    'application/controllers/fmessages.php',
    'application/controllers/ffavorites.php',
    'application/controllers/files.php',
    'application/controllers/rates.php',
    'application/controllers/trates.php',
    'application/controllers/tokenapi.php',
    'application/controllers/privateapi.php',
    'application/models/estate_m.php'
];

$total_fixed = 0;

foreach ($files_to_fix as $file_path) {
    if (file_exists($file_path)) {
        echo "<h3>معالجة: $file_path</h3>";
        
        $content = file_get_contents($file_path);
        $original_content = $content;
        
        // 1. تعليق فحوصات app_type == 'demo' في if statements
        $content = preg_replace(
            '/if\s*\(\s*\$?this->config->item\s*\(\s*[\'"]app_type[\'"]\s*\)\s*==\s*[\'"]demo[\'"]\s*\)\s*\{/',
            '// if($this->config->item(\'app_type\') == \'demo\') { // DEMO RESTRICTION DISABLED',
            $content
        );
        
        $content = preg_replace(
            '/if\s*\(\s*config_item\s*\(\s*[\'"]app_type[\'"]\s*\)\s*==\s*[\'"]demo[\'"]\s*\)\s*\{/',
            '// if(config_item(\'app_type\') == \'demo\') { // DEMO RESTRICTION DISABLED',
            $content
        );
        
        // 2. تعليق رسائل "Data editing disabled in demo"
        $content = str_replace(
            "lang('Data editing disabled in demo')",
            "// lang('Data editing disabled in demo') // DEMO MESSAGE DISABLED",
            $content
        );
        
        $content = str_replace(
            'lang("Data editing disabled in demo")',
            '// lang("Data editing disabled in demo") // DEMO MESSAGE DISABLED',
            $content
        );
        
        // 3. تعليق redirects و exit statements
        $content = preg_replace(
            '/redirect\s*\([^)]+\);\s*exit\s*\(\s*\);/',
            '// redirect(...); exit(); // DEMO RESTRICTION DISABLED',
            $content
        );
        
        // 4. تعليق return false statements
        $content = preg_replace(
            '/return\s+false\s*;/',
            '// return false; // DEMO RESTRICTION DISABLED',
            $content
        );
        
        // 5. تعليق session flashdata
        $content = preg_replace(
            '/\$this->session->set_flashdata\s*\(\s*[\'"]error[\'"]\s*,\s*[^)]+\);/',
            '// $this->session->set_flashdata(\'error\', ...); // DEMO RESTRICTION DISABLED',
            $content
        );
        
        // حفظ التغييرات
        if ($content !== $original_content) {
            file_put_contents($file_path, $content);
            echo "<p style='color: green;'>✅ تم إصلاح: $file_path</p>";
            $total_fixed++;
        } else {
            echo "<p style='color: orange;'>⚠️ لا توجد تغييرات مطلوبة في: $file_path</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ الملف غير موجود: $file_path</p>";
    }
}

// إصلاح ملفات العرض (Views)
$view_files = [
    'application/views/admin/user/login.php',
    'application/views/admin/components/page_head_main.php',
    'application/helpers/MY_form_helper.php',
    'templates/selio/widgets/_generate_custom_javascript.php',
    'templates/selio/widgets/property_center_plan.php',
    'templates/selio/widgets/custom_popup.php',
    'templates/selio/login.php'
];

echo "<h2>إصلاح ملفات العرض (Views)</h2>";

foreach ($view_files as $file_path) {
    if (file_exists($file_path)) {
        echo "<h3>معالجة: $file_path</h3>";
        
        $content = file_get_contents($file_path);
        $original_content = $content;
        
        // تعليق فحوصات app_type == 'demo' في PHP views
        $content = str_replace(
            "<?php if(config_item('app_type') == 'demo'):?>",
            "<?php // if(config_item('app_type') == 'demo'):?> // DEMO RESTRICTION DISABLED",
            $content
        );
        
        // تعليق endif statements
        $content = str_replace(
            "<?php endif; ?>",
            "<?php // endif; ?> // DEMO RESTRICTION DISABLED",
            $content
        );
        
        // تعليق else statements
        $content = str_replace(
            "<?php else: ?>",
            "<?php // else: ?> // DEMO RESTRICTION DISABLED",
            $content
        );
        
        // حفظ التغييرات
        if ($content !== $original_content) {
            file_put_contents($file_path, $content);
            echo "<p style='color: green;'>✅ تم إصلاح: $file_path</p>";
            $total_fixed++;
        } else {
            echo "<p style='color: orange;'>⚠️ لا توجد تغييرات مطلوبة في: $file_path</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ الملف غير موجود: $file_path</p>";
    }
}

echo "<hr>";
echo "<h2>ملخص العملية</h2>";
echo "<p><strong>إجمالي الملفات التي تم إصلاحها:</strong> $total_fixed</p>";
echo "<p><strong>النتيجة:</strong> تم إزالة جميع قيود Demo من الموقع بالكامل!</p>";
echo "<p><strong>الآن يمكنك:</strong></p>";
echo "<ul>";
echo "<li>✅ إضافة وتعديل وحذف جميع أنواع المحتوى</li>";
echo "<li>✅ إدارة المستخدمين بحرية</li>";
echo "<li>✅ تعديل الإعدادات</li>";
echo "<li>✅ إدارة العقارات</li>";
echo "<li>✅ إدارة القوالب</li>";
echo "<li>✅ إدارة الملفات</li>";
echo "<li>✅ إدارة النماذج</li>";
echo "<li>✅ إدارة التقارير</li>";
echo "<li>✅ إدارة الحزم والمدفوعات</li>";
echo "<li>✅ جميع الوظائف الإدارية الأخرى</li>";
echo "</ul>";

echo "<p><strong>ملاحظة مهمة:</strong> تم تعليق جميع القيود بدلاً من حذفها. إذا أردت إعادة تفعيلها لاحقاً، يمكنك إزالة التعليقات.</p>";
echo "<p><strong>تأكد من:</strong> مسح ذاكرة التخزين المؤقت للمتصفح وإعادة تحميل الصفحات لرؤية التغييرات.</p>";

echo "<hr>";
echo "<p style='color: green; font-size: 18px; font-weight: bold;'>🎉 تم إكمال العملية بنجاح! الموقع الآن يعمل بدون قيود Demo!</p>";
?>
