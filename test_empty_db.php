<?php
/**
 * اختبار الاتصال بقاعدة البيانات الفارغة
 */

echo "<h1>اختبار قاعدة البيانات الفارغة</h1>";

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
        
        if ($table_count == 0) {
            echo "<p style='color: green;'>✅ قاعدة البيانات فارغة تماماً - جاهزة للتثبيت!</p>";
        } else {
            echo "<p style='color: red;'>❌ قاعدة البيانات تحتوي على جداول</p>";
        }
        
        // فحص إعدادات قاعدة البيانات
        echo "<h2>إعدادات قاعدة البيانات:</h2>";
        echo "<ul>";
        echo "<li><strong>Hostname:</strong> localhost</li>";
        echo "<li><strong>Username:</strong> root</li>";
        echo "<li><strong>Password:</strong> 771603365</li>";
        echo "<li><strong>Database:</strong> real_estate_db</li>";
        echo "<li><strong>Driver:</strong> mysqli</li>";
        echo "<li><strong>Port:</strong> 3306</li>";
        echo "</ul>";
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ خطأ: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>البيانات المطلوبة للتثبيت:</h2>";
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>الحقل</th><th>القيمة</th></tr>";
echo "<tr><td>Application type</td><td>demo</td></tr>";
echo "<tr><td>Your email</td><td>adnanalqham11@gmail.com</td></tr>";
echo "<tr><td>Admin username</td><td>admin</td></tr>";
echo "<tr><td>Admin password</td><td>c769e</td></tr>";
echo "<tr><td>Agent username</td><td>agent</td></tr>";
echo "<tr><td>Agent password</td><td>fe6f5</td></tr>";
echo "<tr><td>MySQL database name</td><td>real_estate_db</td></tr>";
echo "<tr><td>Database hostname</td><td>localhost</td></tr>";
echo "<tr><td>Database port</td><td>3306</td></tr>";
echo "<tr><td>Database driver</td><td>mysqli</td></tr>";
echo "<tr><td>Database username</td><td>root</td></tr>";
echo "<tr><td>Database password</td><td>771603365</td></tr>";
echo "<tr><td>CodeCanyon username</td><td>demo</td></tr>";
echo "<tr><td>Purchase code</td><td>DEMO-CODE-123</td></tr>";
echo "</table>";

echo "<hr>";
echo "<h2>الخطوات التالية:</h2>";
echo "<ol>";
echo "<li>تأكد من أن قاعدة البيانات فارغة تماماً</li>";
echo "<li>أعد إدخال البيانات في صفحة التثبيت</li>";
echo "<li>اضغط على زر التثبيت</li>";
echo "</ol>";

echo "<p><small>تم الاختبار في: " . date('Y-m-d H:i:s') . "</small></p>";
?> 