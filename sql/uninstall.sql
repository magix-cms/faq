TRUNCATE TABLE `mc_qa_content`;
DROP TABLE `mc_qa_content`;
TRUNCATE TABLE `mc_qa`;
DROP TABLE `mc_qa`;
TRUNCATE TABLE `mc_faq_content`;
DROP TABLE `mc_faq_content`;
TRUNCATE TABLE `mc_faq`;
DROP TABLE `mc_faq`;
TRUNCATE TABLE `mc_faq_config`;
DROP TABLE `mc_faq_config`;

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'faq'
);