<?php
/**
 * إيقاف جميع رسائل Demo نهائياً
 */

echo "<h1>إيقاف جميع رسائل Demo نهائياً</h1>";

// إنشاء مجلد النسخ الاحتياطية
$backupDir = __DIR__ . '/backup_all_files_' . date('Ymd_His');
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true);
    echo "<p style='color: green;'>✅ تم إنشاء مجلد النسخ الاحتياطية: $backupDir</p>";
}

// البحث عن جميع الملفات التي تحتوي على رسالة Demo
$search_dirs = [
    'application/controllers',
    'application/models', 
    'application/libraries',
    'application/views',
    'application/language',
    'templates'
];

$demo_message = "Data editing disabled in demo";
$total_files = 0;
$modified_files = 0;

echo "<h2>بدء البحث عن رسائل Demo:</h2>";

foreach ($search_dirs as $dir) {
    if (is_dir($dir)) {
        echo "<h3>البحث في: $dir</h3>";
        
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        
        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $total_files++;
                $filePath = $file->getRealPath();
                $relativePath = str_replace(__DIR__, '', $filePath);
                $backupPath = $backupDir . $relativePath;
                
                // إنشاء المجلدات في النسخة الاحتياطية
                if (!is_dir(dirname($backupPath))) {
                    mkdir(dirname($backupPath), 0777, true);
                }
                
                // حفظ نسخة احتياطية
                copy($filePath, $backupPath);
                
                $content = file_get_contents($filePath);
                $original_content = $content;
                
                // البحث عن رسائل Demo المختلفة وتعليقها
                $patterns = [
                    "/\\\$this->session->set_flashdata\\('error',\\s*lang\\('Data editing disabled in demo'\\)\\);\\s*redirect\\(.*?\\);\\s*$/m",
                    "/\\\$this->session->set_flashdata\\('error',\\s*lang\\('Data editing disabled in demo'\\)\\);\\s*return false;\\s*$/m",
                    "/\\\$this->session->set_flashdata\\('error',\\s*lang\\('Data editing disabled in demo'\\)\\);\\s*exit;\\s*$/m",
                    "/if\\s*\\(\\s*config_item\\('demo_mode'\\)\\s*==\\s*TRUE\\s*\\)\\s*\\{[^}]*\\}/s",
                    "/if\\s*\\(\\s*\\\$this->config->item\\('demo_mode'\\)\\s*==\\s*TRUE\\s*\\)\\s*\\{[^}]*\\}/s",
                    "/if\\s*\\(\\s*\\\$this->config->item\\('demo_mode'\\)\\s*==\\s*TRUE\\s*\\)\\s*\\{[^}]*\\}/s"
                ];
                
                $modified = false;
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $content)) {
                        $content = preg_replace_callback(
                            $pattern,
                            function ($matches) {
                                return "// " . trim($matches[0]) . " // DEMO MODE DISABLED";
                            },
                            $content
                        );
                        $modified = true;
                    }
                }
                
                // تعليق رسائل Demo الأخرى
                $content = str_replace(
                    "lang('Data editing disabled in demo')",
                    "// lang('Data editing disabled in demo') // DEMO MODE DISABLED",
                    $content
                );
                
                $content = str_replace(
                    "lang_check('Data editing disabled in demo')",
                    "// lang_check('Data editing disabled in demo') // DEMO MODE DISABLED",
                    $content
                );
                
                $content = str_replace(
                    "config_item('demo_mode')",
                    "false // config_item('demo_mode') // DEMO MODE DISABLED",
                    $content
                );
                
                $content = str_replace(
                    "\$this->config->item('demo_mode')",
                    "false // \$this->config->item('demo_mode') // DEMO MODE DISABLED",
                    $content
                );
                
                // تعليق رسائل JavaScript
                $content = str_replace(
                    "ShowStatus.show('<?php echo lang('Data editing disabled in demo')?>');",
                    "// ShowStatus.show('<?php echo lang('Data editing disabled in demo')?>'); // DEMO MODE DISABLED",
                    $content
                );
                
                $content = str_replace(
                    "ShowStatus.show('<?php echo str_replace(\"'\", \"\\'\", lang('Data editing disabled in demo'));?>');",
                    "// ShowStatus.show('<?php echo str_replace(\"'\", \"\\'\", lang('Data editing disabled in demo'));?>'); // DEMO MODE DISABLED",
                    $content
                );
                
                // تعليق رسائل الخطأ
                $content = str_replace(
                    "\$error .= lang_check('Data editing disabled in demo');",
                    "// \$error .= lang_check('Data editing disabled in demo'); // DEMO MODE DISABLED",
                    $content
                );
                
                // فقط اكتب الملف إذا حدث تغيير
                if ($content !== $original_content) {
                    if (file_put_contents($filePath, $content)) {
                        echo "<p style='color: green;'>✅ تم تعديل: " . basename($filePath) . "</p>";
                        $modified_files++;
                    } else {
                        echo "<p style='color: red;'>❌ فشل في تعديل: " . basename($filePath) . "</p>";
                    }
                }
            }
        }
    }
}

// تحديث قاعدة البيانات
echo "<h2>تحديث قاعدة البيانات:</h2>";

try {
    $mysqli = new mysqli('localhost', 'root', '771603365', 'real_estate_db');
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>❌ خطأ في الاتصال بقاعدة البيانات</p>";
    } else {
        echo "<p style='color: green;'>✅ الاتصال بقاعدة البيانات ناجح</p>";
        
        // إيقاف جميع إعدادات Demo
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
            'demo_restrictions_off' => '1',
            'demo_editing_enabled' => '1',
            'demo_allow_changes' => '1',
            'demo_editing_permitted' => '1'
        ];
        
        foreach ($demo_settings as $field => $value) {
            $check = $mysqli->prepare("SELECT id FROM settings WHERE field = ?");
            $check->bind_param("s", $field);
            $check->execute();
            $result = $check->get_result();
            
            if ($result->num_rows > 0) {
                $update = $mysqli->prepare("UPDATE settings SET value = ? WHERE field = ?");
                $update->bind_param("ss", $value, $field);
                if ($update->execute()) {
                    echo "<p style='color: green;'>✅ تم تحديث: $field = $value</p>";
                }
            } else {
                $insert = $mysqli->prepare("INSERT INTO settings (field, value) VALUES (?, ?)");
                $insert->bind_param("ss", $field, $value);
                if ($insert->execute()) {
                    echo "<p style='color: green;'>✅ تم إضافة: $field = $value</p>";
                }
            }
        }
        
        // تفعيل التعديل
        $editing_settings = [
            'frontend_editing' => '1',
            'show_edit_buttons' => '1',
            'allow_frontend_editing' => '1',
            'admin_editing_enabled' => '1',
            'user_editing_allowed' => '1',
            'editing_mode' => 'enabled',
            'disable_editing' => '0',
            'block_editing' => '0',
            'prevent_editing' => '0',
            'editing_allowed' => '1',
            'editing_enabled' => '1',
            'editing_permitted' => '1'
        ];
        
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
                }
            } else {
                $insert = $mysqli->prepare("INSERT INTO settings (field, value) VALUES (?, ?)");
                $insert->bind_param("ss", $field, $value);
                if ($insert->execute()) {
                    echo "<p style='color: green;'>✅ تم إضافة: $field = $value</p>";
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

echo "<hr>";
echo "<h2>ملخص العملية:</h2>";
echo "<ul>";
echo "<li>إجمالي الملفات المعالجة: <strong>$total_files</strong></li>";
echo "<li>الملفات المعدلة: <strong>$modified_files</strong></li>";
echo "<li>النسخ الاحتياطية محفوظة في: <strong>$backupDir</strong></li>";
echo "</ul>";

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

echo "<p><strong>ملاحظة مهمة:</strong> الآن تم تعليق جميع رسائل Demo نهائياً. يجب أن يعمل التعديل بحرية تامة!</p>";

echo "<p><small>تم الإصلاح في: " . date('Y-m-d H:i:s') . "</small></p>";
?>

