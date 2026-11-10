-- Jalankan sekali pada database lama serenity_pmb melalui phpMyAdmin.
-- Cadangkan database terlebih dahulu. Migrasi ini tidak menghapus data.
USE serenity_pmb;
ALTER TABLE users MODIFY nama VARCHAR(120) NULL, MODIFY nik CHAR(16) NULL, MODIFY telepon VARCHAR(30) NULL;
ALTER TABLE applicants MODIFY nomor_tes VARCHAR(30) NULL, MODIFY status VARCHAR(80) NOT NULL DEFAULT 'Biodata belum lengkap';
CREATE TABLE IF NOT EXISTS settings (
 setting_key VARCHAR(80) PRIMARY KEY,
 setting_value VARCHAR(255) NOT NULL,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
INSERT INTO settings(setting_key,setting_value) VALUES ('biaya_pendaftaran','0'),('biaya_daftar_ulang','0')
ON DUPLICATE KEY UPDATE setting_key=VALUES(setting_key);
CREATE TABLE IF NOT EXISTS payments (
 id INT AUTO_INCREMENT PRIMARY KEY,
 applicant_id INT NOT NULL,
 jenis ENUM('pendaftaran','daftar_ulang') NOT NULL,
 nominal BIGINT UNSIGNED NOT NULL,
 bukti_file VARCHAR(255) NULL,
 status ENUM('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
 catatan_admin TEXT NULL,
 submitted_at DATETIME NULL,
 verified_at DATETIME NULL,
 verified_by INT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_payment_applicant FOREIGN KEY(applicant_id) REFERENCES applicants(id) ON DELETE CASCADE,
 CONSTRAINT fk_payment_verifier FOREIGN KEY(verified_by) REFERENCES users(id) ON DELETE SET NULL,
 INDEX idx_payment_applicant_type (applicant_id,jenis), INDEX idx_payment_status(status)
) ENGINE=InnoDB;
