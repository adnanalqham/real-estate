<?php
/**
 * إيقاف وضع Demo Mode
 */

echo "<h1>إيقاف وضع Demo Mode</h1>";

try {
    $mysqli = new mysqli('localhost', 'root', '771603365', 'real_estate_db');
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>❌ خطأ في الاتصال بقاعدة البيانات</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✅ الاتصال بقاعدة البيانات ناجح</p>";
    
    // إيقاف وضع Demo Mode
    $demo_settings = [
        'demo_mode' => '0',
        'demo_editing_disabled' => '0',
        'demo_restrictions' => '0',
        'demo_lock' => '0',
        'demo_protection' => '0',
        'demo_limitations' => '0',
        'demo_block_editing' => '0',
        'demo_restrict_changes' => '0',
        'demo_prevent_editing' => '0',
        'demo_editing_allowed' => '1',
        'demo_mode_disabled' => '1',
        'demo_restrictions_off' => '1'
    ];
    
    echo "<h2>إيقاف وضع Demo Mode:</h2>";
    
    foreach ($demo_settings as $field => $value) {
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
    
    // إعادة تعيين إعدادات التعديل
    $editing_settings = [
        'frontend_editing' => '1',
        'show_edit_buttons' => '1',
        'allow_frontend_editing' => '1',
        'admin_editing_enabled' => '1',
        'user_editing_allowed' => '1',
        'editing_mode' => 'enabled',
        'disable_editing' => '0',
        'block_editing' => '0',
        'prevent_editing' => '0'
    ];
    
    echo "<h2>تفعيل التعديل:</h2>";
    
    foreach ($editing_settings as $field => $value) {
        $check = $mysqli->prepare("SELECT id FROM settings WHERE field = ?");
        $check->bind_param("s", $field);
        $check->execute();
        $result = $check->get_result();
        
        if ($result->num_rows > 0) {
            $update = $mysqli->prepare("UPDATE settings SET value = ? WHERE field = ?");
            $update->bind_param("ss", $value, $field);
            if ($update->execute()) {
                echo "<p style='color: green;'>✅ تم تحديث: $field = $value</p>";
            } else {
                echo "<p style='color: red;'>❌ فشل في تحديث: $field</p>";
            }
        } else {
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
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ خطأ: " . $e->getMessage() . "</p>";
}

// البحث عن ملفات تحتوي على رسالة Demo
echo "<h2>البحث عن رسائل Demo:</h2>";

$search_dirs = [
    'application/controllers',
    'application/models',
    'application/libraries'
];

$demo_message = "Data editing disabled in demo";
$found_files = [];

foreach ($search_dirs as $dir) {
    if (is_dir($dir)) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getRealPath());
                if (strpos($content, $demo_message) !== false) {
                    $found_files[] = $file->getRealPath();
                    echo "<p style='color: orange;'>⚠️ تم العثور على رسالة Demo في: " . $file->getRealPath() . "</p>";
                }
            }
        }
    }
}

if (empty($found_files)) {
    echo "<p style='color: green;'>✅ لم يتم العثور على رسائل Demo</p>";
}

echo "<hr>";
echo "<h2>الخطوات التالية:</h2>";
echo "<ol>";
echo "<li>امسح ذاكرة التخزين المؤقت للمتصفح (Ctrl+Shift+Delete)</li>";
echo "<li>أغلق جميع نوافذ المتصفح</li>";
echo "<li>افتح متصفح جديد</li>";
echo "<li>جرب التعديل من لوحة الإدارة</li>";
echo "</ol>";

echo "<h2>الروابط المهمة:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/admin'>لوحة الإدارة</a></li>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/'>الموقع الرئيسي</a></li>";
echo "</ul>";

echo "<p><strong>ملاحظة:</strong> الآن يجب أن يعمل التعديل من لوحة الإدارة. إذا استمرت المشكلة، أخبرني وسأقوم بتعديل الملفات مباشرة.</p>";

echo "<p><small>تم الإصلاح في: " . date('Y-m-d H:i:s') . "</small></p>";
?>

