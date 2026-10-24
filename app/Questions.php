<?php
// Teks 13 pertanyaan. Disalin persis dari PRD bagian 4. JANGAN diubah, diperhalus,
// atau dikoreksi ejaannya. Semua halaman mengambil teks dari sini (satu sumber).

class Questions {

    // Isian tidak dibatasi untuk pengguna. Angka ini hanya pagar teknis supaya kiriman
    // abnormal (bukan tulisan manusia) tidak memenuhi database. Setara puluhan halaman.
    const MAKS_ISIAN = 50000;

    public static function sesi1() {
        return [
            1 => [
                'teks' => 'Bagaimana kondisi tidurmu belakangan ini?',
                'opsi' => [
                    'A' => 'Nyenyak dan cukup',
                    'B' => 'Cukup tapi sering terbangun',
                    'C' => 'Kurang tidur',
                    'D' => 'Susah tidur sama sekali',
                ],
            ],
            2 => [
                'teks' => 'Seberapa lelah dan lemah tubuh serta pikiranmu akhir-akhir ini?',
                'opsi' => [
                    'A' => 'Tidak lelah, masih bertenaga',
                    'B' => 'Agak lelah tapi masih kuat menjalani hari',
                    'C' => 'Sangat lelah dan terasa lemas',
                    'D' => 'Lelah dan lemah sampai sulit melakukan apa pun',
                ],
            ],
            3 => [
                'teks' => 'Kamu butuh apa dari surat ini sekarang?',
                'opsi' => [
                    'A' => 'Didengerin aja',
                    'B' => 'Diberi semangat',
                    'C' => 'Diberi saran',
                    'D' => 'Ditemani tanpa banyak bicara',
                ],
            ],
            4 => [
                'teks' => 'Seberapa sering kamu merasa sendirian akhir-akhir ini?',
                'opsi' => [
                    'A' => 'Hampir tidak pernah',
                    'B' => 'Kadang-kadang',
                    'C' => 'Sering',
                    'D' => 'Hampir setiap hari',
                ],
            ],
        ];
    }

    public static function sesi2() {
        return [
            1 => 'Ceritakan, hari ini kamu ngapain aja dari pagi sampai sekarang?',
            2 => 'Siapa orang yang paling sering kamu ajak ngobrol akhir-akhir ini?',
            3 => 'Apa hal yang lagi paling kamu tunggu-tunggu?',
            4 => 'Apa kejadian yang masih terngiang di pikiranmu? Apa itu?',
            5 => 'Kapan terakhir kali kamu merasa bangga sama dirimu sendiri, dan karena apa?',
            6 => 'Apa hal yang membuatmu merasa aneh ataupun bingung terhadap diri sendiri?',
        ];
    }

    public static function sesi3() {
        return [
            1 => 'Apa yang kamu ingin ubah tentang mindset kamu?',
            2 => 'Apa kamu senang akhir-akhir ini? Kalau iya, melakukan hal apa?',
            3 => 'Berapa lama sudah kamu memendam sesuatu dalam pikiranmu?',
        ];
    }

    public static function jumlah($sesi) {
        switch ((int)$sesi) {
            case 1: return count(self::sesi1());
            case 2: return count(self::sesi2());
            case 3: return count(self::sesi3());
        }
        return 0;
    }
}
