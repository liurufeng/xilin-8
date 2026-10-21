-- Run once on each database before (or right after) deploying password hashing (app/Libraries/Passwords.php).
-- Every statement can be run again safely.

-- 1. Room for password_hash() hashes (60 characters now, up to 255 later). Until a column is at least 60 wide the
--    site keeps the plain password there instead of a cut-off hash.
ALTER TABLE `parents` MODIFY `passwd` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `admin` MODIFY `pass` VARCHAR(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '';

-- 2. Plain-text copies nothing reads any more: admin.pwd (unless it holds a hash), teachers' passwords (teachers
--    sign in with Google), and the passwords typed in failed admin sign-ins.
UPDATE `admin` SET `pwd` = NULL WHERE `pwd` IS NOT NULL AND `pwd` NOT LIKE '$2_$%' AND `pwd` NOT LIKE '$argon2%';
UPDATE `teachers` SET `passwd` = '', `password` = NULL;
UPDATE `login_log` SET `password` = '';

-- 3. Links "Find password" emails to parents (Controllers/Password.php); only the token's sha256 is stored.
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_type` VARCHAR(10) NOT NULL,
  `user_id` INT NOT NULL,
  `token_hash` CHAR(64) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `used_at` DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_hash` (`token_hash`),
  KEY `user` (`user_type`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- 4. Optional, afterwards: php spark xilin:hash-passwords (hashes passwords still in plain text right away instead
--    of at each account's next sign-in).
