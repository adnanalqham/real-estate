<?php
/**
 * فحص حالة الموقع
 * هذا الملف يتحقق من جاهزية الموقع للتشغيل
 */

echo "<h1>فحص حالة موقع وكالة العقارات</h1>";
echo "<hr>";

// فحص PHP
echo "<h2>1. فحص إعدادات PHP</h2>";
echo "إصدار PHP: " . phpversion() . "<br>";
echo "الحد الأقصى لرفع الملفات: " . ini_get('upload_max_filesize') . "<br>";
echo "الحد الأقصى للذاكرة: " . ini_get('memory_limit') . "<br>";
echo "allow_url_fopen: " . (ini_get('allow_url_fopen') ? 'مفعل' : 'معطل') . "<br>";

// فحص Extensions المطلوبة
$required_extensions = ['mysqli', 'gd', 'curl', 'mbstring', 'openssl'];
echo "<h3>Extensions المطلوبة:</h3>";
foreach ($required_extensions as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? '✅ متوفر' : '❌ غير متوفر') . "<br>";
}

// فحص قاعدة البيانات
echo "<h2>2. فحص قاعدة البيانات</h2>";
$db_config = [
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '771603365',
    'database' => 'real_estate_db'
];

try {
    $mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password']);
    if ($mysqli->connect_error) {
        echo "❌ خطأ في الاتصال بقاعدة البيانات: " . $mysqli->connect_error . "<br>";
    } else {
        echo "✅ الاتصال بقاعدة البيانات ناجح<br>";
        
        // فحص وجود قاعدة البيانات
        $result = $mysqli->query("SHOW DATABASES LIKE 'real_estate_db'");
        if ($result->num_rows > 0) {
            echo "✅ قاعدة البيانات 'real_estate_db' موجودة<br>";
            
            // فحص الجداول
            $mysqli->select_db('real_estate_db');
            $result = $mysqli->query("SHOW TABLES");
            $table_count = $result->num_rows;
            echo "عدد الجداول: $table_count<br>";
            
            if ($table_count > 0) {
                echo "✅ قاعدة البيانات تحتوي على بيانات<br>";
            } else {
                echo "⚠️ قاعدة البيانات فارغة - يجب استيراد البيانات<br>";
            }
        } else {
            echo "❌ قاعدة البيانات 'real_estate_db' غير موجودة<br>";
        }
    }
    $mysqli->close();
} catch (Exception $e) {
    echo "❌ خطأ: " . $e->getMessage() . "<br>";
}

// فحص الملفات والمجلدات
echo "<h2>3. فحص الملفات والمجلدات</h2>";

$required_files = [
    'index.php',
    'application/config/config.php',
    'application/config/database.php',
    'system/core/CodeIgniter.php'
];

foreach ($required_files as $file) {
    echo "$file: " . (file_exists($file) ? '✅ موجود' : '❌ غير موجود') . "<br>";
}

$writable_dirs = [
    'application/cache',
    'application/logs',
    'files',
    'files/thumbnail',
    'files/strict_cache'
];

echo "<h3>المجلدات القابلة للكتابة:</h3>";
foreach ($writable_dirs as $dir) {
    if (file_exists($dir)) {
        echo "$dir: " . (is_writable($dir) ? '✅ قابل للكتابة' : '❌ غير قابل للكتابة') . "<br>";
    } else {
        echo "$dir: ❌ غير موجود<br>";
    }
}

// فحص وضع التثبيت
echo "<h2>4. فحص وضع التثبيت</h2>";
if (file_exists('install.txt')) {
    echo "⚠️ وضع التثبيت مفعل - يجب حذف ملف install.txt<br>";
} else {
    echo "✅ وضع التثبيت معطل<br>";
}

// فحص .htaccess
echo "<h2>5. فحص ملف .htaccess</h2>";
if (file_exists('.htaccess')) {
    echo "✅ ملف .htaccess موجود<br>";
} else {
    echo "⚠️ ملف .htaccess غير موجود<br>";
}

echo "<hr>";
echo "<h2>التوصيات:</h2>";

if (!file_exists('install.txt')) {
    echo "✅ الموقع جاهز للتشغيل!<br>";
    echo "يمكنك الوصول للموقع على: <a href='http://localhost/real-estate-agency-portal-master/'>http://localhost/real-estate-agency-portal-master/</a><br>";
    echo "لوحة الإدارة: <a href='http://localhost/real-estate-agency-portal-master/admin'>http://localhost/real-estate-agency-portal-master/admin</a><br>";
} else {
    echo "❌ الموقع غير جاهز - يجب إكمال التثبيت<br>";
}

echo "<br><small>تم إنشاء هذا الملف في: " . date('Y-m-d H:i:s') . "</small>";
?> 