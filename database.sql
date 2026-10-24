-- Skema database "Surat untukmu" (PRD bagian 8).
-- Tidak ada kolom skor atau benar/salah (AB-3).
-- Impor ke database kosong. Aman dijalankan ulang (IF NOT EXISTS).

CREATE TABLE IF NOT EXISTS responden (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    waktu_mulai DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status_persetujuan ENUM('belum', 'setuju') NOT NULL DEFAULT 'belum',
    waktu_persetujuan DATETIME NULL,
    INDEX idx_waktu_mulai (waktu_mulai)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS jawaban (
    id INT AUTO_INCREMENT PRIMARY KEY,
    responden_id INT NOT NULL,
    sesi TINYINT NOT NULL,
    nomor_pertanyaan TINYINT NOT NULL,
    tipe ENUM('pilihan', 'isian') NOT NULL,
    -- NOT NULL: jawaban kosong disimpan sebagai string kosong, bukan NULL (AB-8).
    nilai MEDIUMTEXT NOT NULL,
    waktu_simpan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_jawaban (responden_id, sesi, nomor_pertanyaan),
    -- ON DELETE CASCADE: menghapus responden ikut menghapus semua jawabannya (hak hapus, PRD bagian 9).
    CONSTRAINT fk_jawaban_responden FOREIGN KEY (responden_id) REFERENCES responden(id) ON DELETE CASCADE,
    -- Pilihan hanya boleh A-D. Pengecekan juga dilakukan di aplikasi.
    CONSTRAINT chk_jawaban_nilai CHECK ((tipe = 'pilihan' AND nilai IN ('A', 'B', 'C', 'D')) OR tipe = 'isian'),
    CONSTRAINT chk_jawaban_sesi CHECK (sesi BETWEEN 1 AND 3)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS kode_akses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    hash_kode VARCHAR(255) NOT NULL,
    dibuat_pada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS percobaan_kode (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- hash SHA-256 (64 karakter heksadesimal), bukan IP asli
    pengenal_klien CHAR(64) CHARACTER SET ascii NOT NULL,
    waktu_percobaan DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    berhasil TINYINT(1) NOT NULL DEFAULT 0,
    INDEX idx_pengenal_waktu (pengenal_klien, waktu_percobaan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jika tabel jawaban sudah dibuat dengan TEXT, jalankan sekali:
--   ALTER TABLE jawaban MODIFY nilai MEDIUMTEXT NOT NULL;

-- Mengisi kode bersama: jalankan `php tools/buat-hash.php` di komputermu sendiri,
-- lalu jalankan perintah INSERT yang dicetaknya di phpMyAdmin.
-- Jangan menulis kode aslinya di berkas mana pun.
