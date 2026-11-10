-- PMB Jalur Mandiri tanpa pembayaran: tabel antrean validasi hasil.
-- Jalankan satu kali pada database lama serenity_pmb.
-- Tidak menghapus data pendaftar, ujian, pembayaran, atau tabel lainnya.
USE serenity_pmb;

CREATE TABLE IF NOT EXISTS exam_result_validations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attempt_id INT NOT NULL UNIQUE,
    status ENUM('menunggu','disetujui') NOT NULL DEFAULT 'menunggu',
    validated_by INT NULL,
    validated_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_result_validation_attempt
        FOREIGN KEY (attempt_id) REFERENCES exam_attempts(id) ON DELETE CASCADE,
    CONSTRAINT fk_result_validation_admin
        FOREIGN KEY (validated_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Hasil ujian yang sudah ada dimasukkan ke antrean validasi admin.
INSERT IGNORE INTO exam_result_validations (attempt_id, status)
SELECT id, 'menunggu' FROM exam_attempts;

-- Pendaftar lama yang belum mengikuti ujian tidak perlu lagi membayar.
UPDATE applicants
SET status = 'Siap mengikuti ujian'
WHERE status IN (
    'Menunggu pembayaran pendaftaran',
    'Pembayaran pendaftaran ditolak',
    'Menunggu verifikasi pembayaran pendaftaran'
)
AND id NOT IN (SELECT applicant_id FROM exam_attempts);
