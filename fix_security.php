<?php
/**
 * إصلاح إعدادات الأمان
 */

echo "<h1>إصلاح إعدادات الأمان</h1>";

try {
    $mysqli = new mysqli('localhost', 'root', '771603365', 'real_estate_db');
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>❌ خطأ في الاتصال بقاعدة البيانات</p>";
        exit;
    }
    
    echo "<p style='color: green;'>✅ الاتصال بقاعدة البيانات ناجح</p>";
    
    // إضافة إعدادات الأمان
    $security_settings = [
        'debug_mode' => '0',
        'development_mode' => '0',
        'maintenance_mode' => '0',
        'admin_access_only' => '1',
        'frontend_editing' => '0',
        'user_permissions' => 'restricted'
    ];
    
    echo "<h2>إضافة إعدادات الأمان:</h2>";
    
    foreach ($security_settings as $field => $value) {
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
    
    // التحقق من إعدادات المستخدمين
    echo "<h2>فحص إعدادات المستخدمين:</h2>";
    
    $result = $mysqli->query("SELECT * FROM user WHERE username = 'admin'");
    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        echo "<p>المدير موجود: " . $admin['username'] . "</p>";
        echo "<p>نوع المستخدم: " . $admin['type'] . "</p>";
    } else {
        echo "<p style='color: red;'>❌ لم يتم العثور على المدير</p>";
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ خطأ: " . $e->getMessage() . "</p>";
}

// إصلاح ملف index.php
echo "<h2>إصلاح ملف index.php:</h2>";

$index_content = file_get_contents('index.php');
if (strpos($index_content, "define('ENVIRONMENT', 'production');") !== false) {
    echo "<p style='color: green;'>✅ البيئة مضبوطة على production</p>";
} else {
    echo "<p style='color: red;'>❌ البيئة ليست على production</p>";
}

echo "<hr>";
echo "<h2>الخطوات التالية:</h2>";
echo "<ol>";
echo "<li>امسح ذاكرة التخزين المؤقت للمتصفح (Ctrl+F5)</li>";
echo "<li>جرب الوصول للموقع مرة أخرى</li>";
echo "<li>تأكد من أن التعديل محظور للمستخدمين العاديين</li>";
echo "</ol>";

echo "<h2>الروابط المهمة:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/'>الموقع الرئيسي</a></li>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/admin'>لوحة الإدارة</a></li>";
echo "</ul>";

echo "<p><small>تم الإصلاح في: " . date('Y-m-d H:i:s') . "</small></p>";
?> 