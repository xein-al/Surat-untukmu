<?php
// Simpan dan baca jawaban. Tidak ada skor, label, atau kolom benar/salah (AB-3).

class Jawaban {

    // Simpan satu sesi sekaligus dalam satu transaksi (AB-8, AB-9).
    // Mengembalikan false bila sesi itu sudah pernah tersimpan (klik ganda, tombol Back,
    // atau kirim ulang), sehingga tidak ada baris ganda dan tidak ada error.
    public static function simpan($sesi, $tipe, array $nilai) {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        $id = Auth::getRespondenId();

        $pdo->beginTransaction();
        try {
            $ada = $db->fetchOne(
                "SELECT COUNT(*) AS total FROM jawaban WHERE responden_id = ? AND sesi = ? FOR UPDATE",
                [$id, (int)$sesi]
            );
            if ((int)($ada['total'] ?? 0) > 0) {
                $pdo->rollBack();
                return false;
            }
            foreach ($nilai as $nomor => $isi) {
                $db->execute(
                    "INSERT INTO jawaban (responden_id, sesi, nomor_pertanyaan, tipe, nilai)
                     VALUES (?, ?, ?, ?, ?)",
                    [$id, (int)$sesi, (int)$nomor, $tipe, (string)$isi]
                );
            }
            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function jumlahTersimpan($sesi) {
        $id = Auth::getRespondenId();
        if ($id === null) {
            return 0;
        }
        $row = Database::getInstance()->fetchOne(
            "SELECT COUNT(*) AS total FROM jawaban WHERE responden_id = ? AND sesi = ?",
            [$id, (int)$sesi]
        );
        return (int)($row['total'] ?? 0);
    }

    // Jawaban pilihan Sesi 1 responden ini: [nomor => 'A'..'D']. Dipakai hanya untuk
    // menentukan kotak anjuran di Surat 2 (AB-4).
    public static function pilihanSesi1() {
        $id = Auth::getRespondenId();
        if ($id === null) {
            return [];
        }
        $rows = Database::getInstance()->fetchAll(
            "SELECT nomor_pertanyaan, nilai FROM jawaban WHERE responden_id = ? AND sesi = 1",
            [$id]
        );
        $hasil = [];
        foreach ($rows as $r) {
            $hasil[(int)$r['nomor_pertanyaan']] = $r['nilai'];
        }
        return $hasil;
    }
}
