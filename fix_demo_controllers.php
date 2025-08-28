<?php
/**
 * إصلاح رسائل Demo في ملفات Controllers
 */

echo "<h1>إصلاح رسائل Demo في ملفات Controllers</h1>";

$directory = __DIR__ . '/application/controllers'; // مجلد controllers
$backupDir = __DIR__ . '/backup_controllers_' . date('Ymd_His'); // مجلد النسخ الاحتياطية

echo "<h2>إنشاء نسخ احتياطية:</h2>";

if (!is_dir($backupDir)) {
    if (mkdir($backupDir, 0777, true)) {
        echo "<p style='color: green;'>✅ تم إنشاء مجلد النسخ الاحتياطية: $backupDir</p>";
    } else {
        echo "<p style='color: red;'>❌ فشل في إنشاء مجلد النسخ الاحتياطية</p>";
        exit;
    }
} else {
    echo "<p style='color: green;'>✅ مجلد النسخ الاحتياطية موجود</p>";
}

echo "<h2>بدء معالجة الملفات:</h2>";

$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$modified_files = 0;
$total_files = 0;

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $total_files++;
        $filePath = $file->getRealPath();
        $relativePath = str_replace($directory, '', $filePath);
        $backupPath = $backupDir . $relativePath;

        // إنشاء المجلدات في النسخة الاحتياطية
        if (!is_dir(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0777, true);
        }

        // حفظ نسخة احتياطية
        if (copy($filePath, $backupPath)) {
            echo "<p style='color: blue;'>📁 تم حفظ نسخة احتياطية: " . basename($filePath) . "</p>";
        } else {
            echo "<p style='color: red;'>❌ فشل في حفظ نسخة احتياطية: " . basename($filePath) . "</p>";
            continue;
        }

        $content = file_get_contents($filePath);
        $original_content = $content;

        // البحث عن رسائل Demo المختلفة
        $demo_patterns = [
            "/\\\$this->session->set_flashdata\\('error',\\s*lang\\('Data editing disabled in demo'\\)\\);\\s*redirect\\(.*?\\);\\s*$/m",
            "/\\\$this->session->set_flashdata\\('error',\\s*lang\\('Data editing disabled in demo'\\)\\);\\s*return false;\\s*$/m",
            "/\\\$this->session->set_flashdata\\('error',\\s*lang\\('Data editing disabled in demo'\\)\\);\\s*exit;\\s*$/m",
            "/if\\s*\\(\\s*config_item\\('demo_mode'\\)\\s*==\\s*TRUE\\s*\\)\\s*\\{[^}]*\\}/s",
            "/if\\s*\\(\\s*\\\$this->config->item\\('demo_mode'\\)\\s*==\\s*TRUE\\s*\\)\\s*\\{[^}]*\\}/s"
        ];

        $modified = false;
        foreach ($demo_patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                // تعليق الكود بدلاً من حذفه
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

        // البحث عن رسائل أخرى متعلقة بـ Demo
        $content = str_replace(
            "lang('Data editing disabled in demo')",
            "// lang('Data editing disabled in demo') // DEMO MODE DISABLED",
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

        // فقط اكتب الملف إذا حدث تغيير
        if ($content !== $original_content) {
            if (file_put_contents($filePath, $content)) {
                echo "<p style='color: green;'>✅ تم تعديل: " . basename($filePath) . "</p>";
                $modified_files++;
            } else {
                echo "<p style='color: red;'>❌ فشل في تعديل: " . basename($filePath) . "</p>";
            }
        } else {
            echo "<p style='color: gray;'>➖ لا توجد تغييرات مطلوبة: " . basename($filePath) . "</p>";
        }
    }
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

echo "<p><strong>ملاحظة:</strong> تم تعليق جميع رسائل Demo بدلاً من حذفها. إذا أردت إعادة تفعيلها لاحقاً، يمكنك إزالة التعليقات.</p>";

echo "<p><small>تم الإصلاح في: " . date('Y-m-d H:i:s') . "</small></p>";
?>

