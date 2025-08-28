<?php
/**
 * إيقاف وضع التطوير بشكل كامل
 */

echo "<h1>إيقاف وضع التطوير بشكل كامل</h1>";

// إيقاف عرض الأخطاء
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

echo "<p style='color: green;'>✅ تم إيقاف عرض الأخطاء</p>";

// تحديث إعدادات قاعدة البيانات
try {
    $mysqli = new mysqli('localhost', 'root', '771603365', 'real_estate_db');
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>❌ خطأ في الاتصال بقاعدة البيانات</p>";
    } else {
        echo "<p style='color: green;'>✅ الاتصال بقاعدة البيانات ناجح</p>";
        
        // إعدادات إيقاف وضع التطوير
        $development_settings = [
            'debug_mode' => '0',
            'development_mode' => '0',
            'maintenance_mode' => '0',
            'frontend_editing' => '0',
            'admin_access_only' => '1',
            'user_permissions' => 'restricted',
            'show_admin_tools' => '0',
            'enable_designer_mode' => '0',
            'show_edit_buttons' => '0',
            'allow_frontend_editing' => '0',
            'production_mode' => '1',
            'hide_admin_toolbar' => '1'
        ];
        
        echo "<h2>تحديث إعدادات الإنتاج:</h2>";
        
        foreach ($development_settings as $field => $value) {
            // التحقق من وجود الإعداد
            $check = $mysqli->prepare("SELECT id FROM settings WHERE field = ?");
            $check->bind_param("s", $field);
            $check->execute();
            $result = $check->get_result();
            
            if ($result->num_rows > 0) {
                // تحديث الإعداد الموجود
                $update = $mysqli->prepare("UPDATE settings SET value = ? WHERE field = ?");
                $update->bind_param("ss", $value, $field);
                if ($update->execute()) {
                    echo "<p style='color: green;'>✅ تم تحديث: $field = $value</p>";
                } else {
                    echo "<p style='color: red;'>❌ فشل في تحديث: $field</p>";
                }
            } else {
                // إضافة إعداد جديد
                $insert = $mysqli->prepare("INSERT INTO settings (field, value) VALUES (?, ?)");
                $insert->bind_param("ss", $field, $value);
                if ($insert->execute()) {
                    echo "<p style='color: green;'>✅ تم إضافة: $field = $value</p>";
                } else {
                    echo "<p style='color: red;'>❌ فشل في إضافة: $field</p>";
                }
            }
        }
        
        // حذف جميع الجلسات
        $mysqli->query("DELETE FROM ci_sessions");
        echo "<p style='color: green;'>✅ تم حذف جميع الجلسات</p>";
        
        $mysqli->close();
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ خطأ: " . $e->getMessage() . "</p>";
}

// تحديث ملف index.php
$index_content = file_get_contents('index.php');
$index_content = str_replace(
    "define('ENVIRONMENT', 'development');",
    "define('ENVIRONMENT', 'production');",
    $index_content
);
file_put_contents('index.php', $index_content);
echo "<p style='color: green;'>✅ تم تحديث ملف index.php</p>";

// إنشاء ملف .env للإنتاج
$env_content = "ENVIRONMENT=production\n";
$env_content .= "DEBUG_MODE=false\n";
$env_content .= "DEVELOPMENT_MODE=false\n";
$env_content .= "FRONTEND_EDITING=false\n";
$env_content .= "SHOW_ADMIN_TOOLS=false\n";

if (file_put_contents('.env', $env_content)) {
    echo "<p style='color: green;'>✅ تم إنشاء ملف .env</p>";
} else {
    echo "<p style='color: red;'>❌ فشل في إنشاء ملف .env</p>";
}

// تنظيف الكاش
$cache_files = [
    'application/cache/*',
    'files/cache/*',
    'files/strict_cache/*'
];

echo "<h2>تنظيف الكاش:</h2>";

foreach ($cache_files as $pattern) {
    $files = glob($pattern);
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    echo "<p style='color: green;'>✅ تم تنظيف: $pattern</p>";
}

echo "<hr>";
echo "<h2>الخطوات التالية:</h2>";
echo "<ol>";
echo "<li>امسح ذاكرة التخزين المؤقت للمتصفح (Ctrl+Shift+Delete)</li>";
echo "<li>أغلق جميع نوافذ المتصفح</li>";
echo "<li>افتح متصفح جديد في وضع التصفح الخاص</li>";
echo "<li>اذهب للموقع: <a href='http://localhost/real-estate-agency-portal-master/'>الموقع الرئيسي</a></li>";
echo "<li>تأكد من أن أدوات التعديل اختفت تماماً</li>";
echo "</ol>";

echo "<h2>الروابط المهمة:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/'>الموقع الرئيسي (بدون أدوات تعديل)</a></li>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/admin'>لوحة الإدارة</a></li>";
echo "</ul>";

echo "<p><strong>ملاحظة مهمة:</strong> الآن يجب أن تختفي جميع أدوات التعديل من الموقع الرئيسي. إذا كنت تريد الوصول للوحة الإدارة، استخدم الرابط أعلاه.</p>";

echo "<p><small>تم الإصلاح في: " . date('Y-m-d H:i:s') . "</small></p>";
?> 