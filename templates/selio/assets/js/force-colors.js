// ========================================
// إجبار تطبيق الألوان الجديدة عبر JavaScript
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('بدء تطبيق الألوان الجديدة...');
    
    // تعريف الألوان الجديدة
    const newColors = {
        primary: '#ffffff',      // أبيض (اللون الرئيسي)
        secondary: '#a49b6b',    // بني ذهبي (للعناصر الثانوية)
        accent: '#65ac25'        // أخضر (للعناصر المميزة)
    };
    
    // دالة لتطبيق الألوان على العناصر
    function applyNewColors() {
        // تطبيق على الأزرار
        const buttons = document.querySelectorAll('.btn-primary, .btn-secondary, .btn-success');
        buttons.forEach(btn => {
            if (btn.classList.contains('btn-primary')) {
                btn.style.backgroundColor = newColors.secondary + ' !important';  // استخدام البني الذهبي
                btn.style.borderColor = newColors.secondary + ' !important';
                btn.style.color = 'white !important';
            } else if (btn.classList.contains('btn-secondary')) {
                btn.style.backgroundColor = newColors.accent + ' !important';     // استخدام الأخضر
                btn.style.borderColor = newColors.accent + ' !important';
                btn.style.color = 'white !important';
            } else if (btn.classList.contains('btn-success')) {
                btn.style.backgroundColor = newColors.accent + ' !important';
                btn.style.borderColor = newColors.accent + ' !important';
                btn.style.color = 'white !important';
            }
        });
        
        // تطبيق على الروابط
        const links = document.querySelectorAll('a');
        links.forEach(link => {
            link.style.color = newColors.secondary + ' !important';  // استخدام البني الذهبي
        });
        
        // تطبيق على العلامة التجارية
        const brand = document.querySelector('.navbar-brand');
        if (brand) {
            brand.style.color = newColors.secondary + ' !important';  // استخدام البني الذهبي
        }
        
        // تطبيق على الأيقونات
        const icons = document.querySelectorAll('.fa, .icon, .glyphicon');
        icons.forEach(icon => {
            icon.style.color = newColors.secondary + ' !important';  // استخدام البني الذهبي
        });
        
        // تطبيق على النماذج
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.borderColor = newColors.secondary + ' !important';  // استخدام البني الذهبي
                this.style.boxShadow = '0 0 0 0.2rem rgba(164, 155, 107, 0.25) !important';
            });
        });
        
        console.log('تم تطبيق الألوان الجديدة بنجاح!');
    }
    
    // تطبيق الألوان فوراً
    applyNewColors();
    
    // تطبيق الألوان بعد 1 ثانية (للتأكد من تحميل الصفحة)
    setTimeout(applyNewColors, 1000);
    
    // تطبيق الألوان كل 3 ثوان (للتأكد من عدم تغييرها)
    setInterval(applyNewColors, 3000);
    
    // مراقبة التغييرات في DOM
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                setTimeout(applyNewColors, 100);
            }
        });
    });
    
    // بدء المراقبة
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

// تطبيق الألوان على العناصر الجديدة
function forceApplyColors() {
    const style = document.createElement('style');
    style.textContent = `
        /* إجبار تطبيق الألوان */
        .btn-primary { background-color: #a49b6b !important; border-color: #a49b6b !important; color: white !important; }
        .btn-secondary { background-color: #65ac25 !important; border-color: #65ac25 !important; color: white !important; }
        .btn-success { background-color: #65ac25 !important; border-color: #65ac25 !important; color: white !important; }
        a { color: #a49b6b !important; }
        .navbar-brand { color: #a49b6b !important; }
        .fa, .icon, .glyphicon { color: #a49b6b !important; }
        .form-control:focus { border-color: #a49b6b !important; }
    `;
    document.head.appendChild(style);
}

// تطبيق فوري
forceApplyColors();
