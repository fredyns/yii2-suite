/**
 * konten migration yg terlewati ketika menggunakan sql import
 *
 */

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1480693283),
('m140209_132017_init', 1480693289),
('m140403_174025_create_account_table', 1480693290),
('m140504_113157_update_tables', 1480693290),
('m140504_130429_create_token_table', 1480693290),
('m140830_171933_fix_ip_field', 1480693290),
('m140830_172703_change_account_table_name', 1480693291),
('m141222_110026_update_ip_field', 1480693291),
('m141222_135246_alter_username_length', 1480693291),
('m150614_103145_update_social_account_table', 1480693291),
('m150623_212711_fix_username_notnull', 1480693291),
('m151218_234654_add_timezone_to_profile', 1480693291);
