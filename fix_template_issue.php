<?php
/**
 * إصلاح مشكلة القالب
 */

echo "<h1>إصلاح مشكلة القالب</h1>";

// إنشاء مجلد components إذا لم يكن موجوداً
$components_dir = 'templates/selio/components';
if (!is_dir($components_dir)) {
    if (mkdir($components_dir, 0755, true)) {
        echo "<p style='color: green;'>✅ تم إنشاء مجلد components</p>";
    } else {
        echo "<p style='color: red;'>❌ فشل في إنشاء مجلد components</p>";
    }
} else {
    echo "<p style='color: green;'>✅ مجلد components موجود</p>";
}

// إنشاء ملف index.html في مجلد components
$index_file = $components_dir . '/index.html';
if (!file_exists($index_file)) {
    $content = '<!DOCTYPE html><html><head><title>Access Denied</title></head><body><h1>Access Denied</h1><p>Direct access to this directory is not allowed.</p></body></html>';
    if (file_put_contents($index_file, $content)) {
        echo "<p style='color: green;'>✅ تم إنشاء ملف index.html</p>";
    } else {
        echo "<p style='color: red;'>❌ فشل في إنشاء ملف index.html</p>";
    }
} else {
    echo "<p style='color: green;'>✅ ملف index.html موجود</p>";
}

// إنشاء ملف .htaccess في مجلد components
$htaccess_file = $components_dir . '/.htaccess';
if (!file_exists($htaccess_file)) {
    $content = "Options -Indexes\nDeny from all";
    if (file_put_contents($htaccess_file, $content)) {
        echo "<p style='color: green;'>✅ تم إنشاء ملف .htaccess</p>";
    } else {
        echo "<p style='color: red;'>❌ فشل في إنشاء ملف .htaccess</p>";
    }
} else {
    echo "<p style='color: green;'>✅ ملف .htaccess موجود</p>";
}

// فحص إعدادات قاعدة البيانات
try {
    $mysqli = new mysqli('localhost', 'root', '771603365', 'real_estate_db');
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>❌ خطأ في الاتصال بقاعدة البيانات</p>";
    } else {
        echo "<p style='color: green;'>✅ الاتصال بقاعدة البيانات ناجح</p>";
        
        // فحص إعدادات القالب
        $result = $mysqli->query("SELECT * FROM settings WHERE field = 'template'");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<p>القالب الحالي: <strong>" . $row['value'] . "</strong></p>";
            
            if ($row['value'] == 'selio') {
                echo "<p style='color: green;'>✅ القالب مضبوط على selio</p>";
            } else {
                echo "<p style='color: orange;'>⚠️ القالب مضبوط على: " . $row['value'] . "</p>";
            }
        } else {
            echo "<p style='color: red;'>❌ لم يتم العثور على إعدادات القالب</p>";
        }
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ خطأ: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>الخطوات التالية:</h2>";
echo "<ol>";
echo "<li>تأكد من أن جميع الملفات تم إنشاؤها بنجاح</li>";
echo "<li>جرب الوصول للموقع مرة أخرى</li>";
echo "<li>إذا استمرت المشكلة، تحقق من ملفات السجل</li>";
echo "</ol>";

echo "<h2>الروابط المهمة:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/'>الموقع الرئيسي</a></li>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/admin'>لوحة الإدارة</a></li>";
echo "<li><a href='http://localhost/real-estate-agency-portal-master/test_empty_db.php'>اختبار قاعدة البيانات</a></li>";
echo "</ul>";

echo "<p><small>تم الإصلاح في: " . date('Y-m-d H:i:s') . "</small></p>";
?> 