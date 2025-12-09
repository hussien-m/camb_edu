-- تحديث اسم الموقع في جدول pages
-- من: Cambridge British International College
-- إلى: Cambridge International College in UK

UPDATE `pages` 
SET `title` = REPLACE(`title`, 'Cambridge British International College', 'Cambridge International College in UK'),
    `content` = REPLACE(`content`, 'Cambridge British International College', 'Cambridge International College in UK'),
    `meta_title` = REPLACE(`meta_title`, 'Cambridge British International College', 'Cambridge International College in UK'),
    `meta_description` = REPLACE(`meta_description`, 'Cambridge British International College', 'Cambridge International College in UK')
WHERE `title` LIKE '%Cambridge British International College%'
   OR `content` LIKE '%Cambridge British International College%'
   OR `meta_title` LIKE '%Cambridge British International College%'
   OR `meta_description` LIKE '%Cambridge British International College%';

-- تحديث أيضاً في حالة وجود النص بدون "British"
UPDATE `pages` 
SET `title` = REPLACE(`title`, 'Cambridge International College', 'Cambridge International College in UK'),
    `content` = REPLACE(`content`, 'Cambridge International College', 'Cambridge International College in UK'),
    `meta_title` = REPLACE(`meta_title`, 'Cambridge International College', 'Cambridge International College in UK'),
    `meta_description` = REPLACE(`meta_description`, 'Cambridge International College', 'Cambridge International College in UK')
WHERE (`title` LIKE '%Cambridge International College%' AND `title` NOT LIKE '%in UK%')
   OR (`content` LIKE '%Cambridge International College%' AND `content` NOT LIKE '%in UK%')
   OR (`meta_title` LIKE '%Cambridge International College%' AND `meta_title` NOT LIKE '%in UK%')
   OR (`meta_description` LIKE '%Cambridge International College%' AND `meta_description` NOT LIKE '%in UK%');

-- للتحقق من النتيجة
SELECT id, title, LEFT(content, 100) as content_preview 
FROM `pages` 
WHERE title LIKE '%Cambridge%' 
   OR content LIKE '%Cambridge%';
