-- إنشاء جدول إعادة تعيين كلمات المرور للطلاب
-- يجب تشغيل هذا على الاستضافة إذا كان الجدول غير موجود

CREATE TABLE IF NOT EXISTS `student_password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`),
  KEY `student_password_reset_tokens_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- حذف الـ tokens القديمة (اختياري)
-- DELETE FROM `student_password_reset_tokens` WHERE TIMESTAMPDIFF(HOUR, created_at, NOW()) > 1;
