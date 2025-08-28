<?php
/**
 * استخراج بيانات التثبيت من قاعدة البيانات
 */

echo "<h1>بيانات التثبيت المطلوبة</h1>";

try {
    $mysqli = new mysqli('localhost', 'root', '771603365', 'real_estate_db');
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>❌ خطأ في الاتصال: " . $mysqli->connect_error . "</p>";
        exit;
    }
    
    echo "<h2>1. هيكل جدول settings:</h2>";
    $result = $mysqli->query("SHOW COLUMNS FROM settings");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<h2>2. البيانات الموجودة في جدول settings:</h2>";
    $result = $mysqli->query("SELECT * FROM settings LIMIT 10");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        $first = true;
        while ($row = $result->fetch_assoc()) {
            if ($first) {
                echo "<tr>";
                foreach ($row as $key => $value) {
                    echo "<th>$key</th>";
                }
                echo "</tr>";
                $first = false;
            }
            echo "<tr>";
            foreach ($row as $value) {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<h2>3. البحث عن بيانات CodeCanyon:</h2>";
    $result = $mysqli->query("SELECT * FROM settings WHERE `key` LIKE '%codecanyon%' OR `key` LIKE '%purchase%' OR `key` LIKE '%envato%'");
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Key</th><th>Value</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['value']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>لم يتم العثور على بيانات CodeCanyon في جدول settings</p>";
    }
    
    echo "<h2>4. البحث في جميع الجداول:</h2>";
    $result = $mysqli->query("SHOW TABLES");
    $tables = [];
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    
    foreach ($tables as $table) {
        $result = $mysqli->query("SHOW COLUMNS FROM `$table`");
        $has_codecanyon = false;
        while ($row = $result->fetch_assoc()) {
            if (stripos($row['Field'], 'codecanyon') !== false || 
                stripos($row['Field'], 'purchase') !== false || 
                stripos($row['Field'], 'envato') !== false) {
                $has_codecanyon = true;
                break;
            }
        }
        
        if ($has_codecanyon) {
            echo "<h3>جدول $table:</h3>";
            $result = $mysqli->query("SELECT * FROM `$table` LIMIT 5");
            if ($result) {
                echo "<table border='1' style='border-collapse: collapse;'>";
                $first = true;
                while ($row = $result->fetch_assoc()) {
                    if ($first) {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            echo "<th>$key</th>";
                        }
                        echo "</tr>";
                        $first = false;
                    }
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            }
        }
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ خطأ: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>البيانات المطلوبة للتثبيت:</h2>";
echo "<p><strong>CodeCanyon Username:</strong> (يجب البحث في البيانات أعلاه)</p>";
echo "<p><strong>Purchase Code:</strong> (يجب البحث في البيانات أعلاه)</p>";
echo "<p><strong>Database Username:</strong> root</p>";
echo "<p><strong>Database Password:</strong> 771603365</p>";
?> 