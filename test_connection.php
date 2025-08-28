<?php
/**
 * اختبار الاتصال بقاعدة البيانات
 */

echo "<h1>اختبار الاتصال بقاعدة البيانات</h1>";

try {
    $mysqli = new mysqli('localhost', 'root', '771603365', 'real_estate_db');
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>❌ خطأ في الاتصال: " . $mysqli->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>✅ الاتصال بقاعدة البيانات ناجح!</p>";
        
        // فحص عدد الجداول
        $result = $mysqli->query("SHOW TABLES");
        $table_count = $result->num_rows;
        echo "<p>عدد الجداول في قاعدة البيانات: <strong>$table_count</strong></p>";
        
        // فحص بعض الجداول المهمة
        $important_tables = ['user', 'property', 'settings', 'language'];
        echo "<h3>فحص الجداول المهمة:</h3>";
        foreach ($important_tables as $table) {
            $result = $mysqli->query("SHOW TABLES LIKE '$table'");
            if ($result->num_rows > 0) {
                echo "<p style='color: green;'>✅ جدول '$table' موجود</p>";
            } else {
                echo "<p style='color: red;'>❌ جدول '$table' غير موجود</p>";
            }
        }
        
        // فحص بيانات المستخدمين
        $result = $mysqli->query("SELECT COUNT(*) as count FROM user");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>عدد المستخدمين: <strong>" . $row['count'] . "</strong></p>";
        }
        
        // فحص العقارات
        $result = $mysqli->query("SELECT COUNT(*) as count FROM property");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>عدد العقارات: <strong>" . $row['count'] . "</strong></p>";
        }
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ خطأ: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>الروابط المهمة:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/'>الموقع الرئيسي</a></li>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/admin'>لوحة الإدارة</a></li>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/check_status.php'>فحص الحالة الكامل</a></li>";
echo "</ul>";

echo "<p><small>تم الاختبار في: " . date('Y-m-d H:i:s') . "</small></p>";
?> 