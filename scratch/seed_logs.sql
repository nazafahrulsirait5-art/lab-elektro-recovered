USE lab_elektro;
ALTER TABLE users ADD COLUMN no_hp VARCHAR(20) DEFAULT NULL AFTER email;
UPDATE users SET no_hp = '6281234567890' WHERE role = 'mahasiswa';
