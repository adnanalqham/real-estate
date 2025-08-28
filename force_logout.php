<?php
/**
 * إجبار تسجيل الخروج وإعادة تعيين الجلسة
 */

echo "<h1>إجبار تسجيل الخروج وإعادة تعيين الجلسة</h1>";

// بدء الجلسة
session_start();

// حذف جميع متغيرات الجلسة
$_SESSION = array();

// حذف كوكيز الجلسة
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// تدمير الجلسة
session_destroy();

echo "<p style='color: green;'>✅ تم تسجيل الخروج بنجاح</p>";

// حذف ملفات الجلسة من قاعدة البيانات
try {
    $mysqli = new mysqli('localhost', 'root', '771603365', 'real_estate_db');
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>❌ خطأ في الاتصال بقاعدة البيانات</p>";
    } else {
        echo "<p style='color: green;'>✅ الاتصال بقاعدة البيانات ناجح</p>";
        
        // حذف جلسات المستخدمين
        $result = $mysqli->query("DELETE FROM ci_sessions WHERE timestamp < " . (time() - 3600));
        if ($result) {
            echo "<p style='color: green;'>✅ تم حذف الجلسات القديمة</p>";
        } else {
            echo "<p style='color: orange;'>⚠️ لم يتم العثور على جلسات لحذفها</p>";
        }
        
        // إعادة تعيين إعدادات الأمان
        $security_updates = [
            'frontend_editing' => '0',
            'admin_access_only' => '1',
            'user_permissions' => 'restricted',
            'debug_mode' => '0',
            'development_mode' => '0'
        ];
        
        echo "<h2>تحديث إعدادات الأمان:</h2>";
        
        foreach ($security_updates as $field => $value) {
            $update = $mysqli->prepare("UPDATE settings SET value = ? WHERE field = ?");
            $update->bind_param("ss", $value, $field);
            if ($update->execute()) {
                echo "<p style='color: green;'>✅ تم تحديث: $field = $value</p>";
            } else {
                echo "<p style='color: red;'>❌ فشل في تحديث: $field</p>";
            }
        }
        
        $mysqli->close();
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ خطأ: " . $e->getMessage() . "</p>";
}

// حذف ملفات الكاش
$cache_dirs = [
    'application/cache',
    'files/cache',
    'files/strict_cache'
];

echo "<h2>تنظيف الكاش:</h2>";

foreach ($cache_dirs as $dir) {
    if (is_dir($dir)) {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "<p style='color: green;'>✅ تم تنظيف: $dir</p>";
    }
}

echo "<hr>";
echo "<h2>الخطوات التالية:</h2>";
echo "<ol>";
echo "<li>امسح ذاكرة التخزين المؤقت للمتصفح (Ctrl+Shift+Delete)</li>";
echo "<li>أغلق جميع نوافذ المتصفح</li>";
echo "<li>افتح متصفح جديد</li>";
echo "<li>اذهب للموقع: <a href='http://localhost/real-estate-agency-portal-master/'>الموقع الرئيسي</a></li>";
echo "<li>تأكد من أن أدوات التعديل اختفت</li>";
echo "</ol>";

echo "<h2>الروابط المهمة:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/'>الموقع الرئيسي (بدون تسجيل دخول)</a></li>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/admin'>لوحة الإدارة (تسجيل دخول مطلوب)</a></li>";
echo "</ul>";

echo "<p><strong>ملاحظة:</strong> يجب أن تختفي أدوات التعديل الآن. إذا كنت تريد الوصول للوحة الإدارة، سجل دخول من <a href='http://localhost/real-estate-agency-portal-master/admin'>هنا</a></p>";

echo "<p><small>تم الإصلاح في: " . date('Y-m-d H:i:s') . "</small></p>";
?> 