# دليل تثبيت موقع وكالة العقارات

## المتطلبات الأساسية
- PHP 7.0 أو أحدث
- MySQL 5.6 أو أحدث
- Apache/Nginx
- mod_rewrite مفعل

## خطوات التثبيت

### 1. إعداد قاعدة البيانات
```sql
-- إنشاء قاعدة البيانات
CREATE DATABASE real_estate_db CHARACTER SET utf8 COLLATE utf8_general_ci;

-- استيراد البيانات الأساسية
mysql -u root -p real_estate_db < sql_scripts/db-example-1.sql
```

### 2. إعدادات قاعدة البيانات
- الملف: `application/config/database.php`
- إعدادات افتراضية:
  - Hostname: localhost
  - Username: root
  - Password: 771603365
  - Database: real_estate_db

### 3. إعدادات الموقع
- الملف: `application/config/config.php`
- Base URL: `http://localhost/real-estate-agency-portal-master/`

### 4. الصلاحيات المطلوبة
```bash
# جعل المجلدات قابلة للكتابة
chmod 755 files/
chmod 755 application/cache/
chmod 755 application/logs/
```

### 5. إزالة وضع التثبيت
- تم حذف ملف `install.txt` ✅

## الوصول للموقع
- الموقع الرئيسي: `http://localhost/real-estate-agency-portal-master/`
- لوحة الإدارة: `http://localhost/real-estate-agency-portal-master/admin`

## بيانات تسجيل الدخول الافتراضية
- Username: admin
- Password: admin123

## ملاحظات مهمة
1. تأكد من تشغيل Apache و MySQL
2. تأكد من تفعيل mod_rewrite
3. تأكد من إعدادات PHP (allow_url_fopen = On)
4. تأكد من صلاحيات الكتابة على المجلدات المطلوبة

## استكشاف الأخطاء
- تحقق من ملفات السجل في `application/logs/`
- تأكد من إعدادات قاعدة البيانات
- تحقق من إعدادات PHP 