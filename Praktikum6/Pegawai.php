<?php
declare(strict_types=1);

abstract class Pegawai
{
    public function __construct(
        private string $id,
        private string $nama
    ) {
        self::validate($id, $nama);
        $this->id = trim($id);
        $this->nama = trim($nama);
    }

    private static function validate(string $id, string $nama): void
    {
        if (trim($id) === '' || trim($nama) === '') {
            throw new InvalidArgumentException('ID dan Nama pegawai wajib diisi.');
        }
    }

    public function getId(): string { return $this->id; }
    public function getNama(): string { return $this->nama; }

    abstract public function hitungGaji(): float;
    abstract public function getJabatan(): string;
}

final class PegawaiTetap extends Pegawai
{
    public function __construct(string $id, string $nama, private float $gajiPokok)
    {
        if (!is_finite($gajiPokok) || $gajiPokok < 0) {
            throw new InvalidArgumentException('Gaji pokok tidak boleh negatif.');
        }
        parent::__construct($id, $nama);
    }

    public function hitungGaji(): float { return $this->gajiPokok; }
    public function getJabatan(): string { return 'Pegawai Tetap'; }
}

final class PegawaiKontrak extends Pegawai
{
    public function __construct(string $id, string $nama, private float $upahPerJam, private int $jumlahJam)
    {
        if (!is_finite($upahPerJam) || $upahPerJam < 0 || $jumlahJam < 0) {
            throw new InvalidArgumentException('Upah per jam dan jumlah jam tidak boleh negatif.');
        }
        parent::__construct($id, $nama);
    }

    public function hitungGaji(): float { return $this->upahPerJam * $this->jumlahJam; }
    public function getJabatan(): string { return 'Pegawai Kontrak'; }
}

try {
    $p1 = new PegawaiTetap('P001', 'Budi Santoso', 4500000);
    $p2 = new PegawaiKontrak('P002', 'Siti Rahma', 50000, 40);

    echo "{$p1->getNama()} ({$p1->getJabatan()}) - Gaji: Rp " . number_format($p1->hitungGaji(), 0, ',', '.') . "<br>";
    echo "{$p2->getNama()} ({$p2->getJabatan()}) - Gaji: Rp " . number_format($p2->hitungGaji(), 0, ',', '.');
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage();
}