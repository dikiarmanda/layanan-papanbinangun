<?php

namespace App\Models;

use CodeIgniter\Model;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;

class JadwalPaketWisataModel extends Model
{
    protected $table = 'jadwal_paket_wisata';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'paket_wisata_id',
        'tanggal',
        'kuota',
        'kuota_terpakai',
    ];
    protected $useTimestamps = false;

    public function availableForPaket(int $paketId): array
    {
        return $this->where('paket_wisata_id', $paketId)
            ->where('tanggal >=', date('Y-m-d'))
            ->orderBy('tanggal', 'ASC')
            ->findAll();
    }

    /**
     * Tanggal malam yang dihitung: check_in inclusive, check_out exclusive.
     *
     * @return list<string> Y-m-d
     */
    public function nightsBetween(string $checkIn, string $checkOut): array
    {
        $start = new DateTimeImmutable($checkIn);
        $end = new DateTimeImmutable($checkOut);

        if ($end <= $start) {
            return [];
        }

        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        $nights = [];
        foreach ($period as $day) {
            $nights[] = $day->format('Y-m-d');
        }

        return $nights;
    }

    /**
     * Cek semua malam tersedia (ada jadwal & sisa kuota >= 1).
     *
     * @return array{ok:bool, missing:list<string>, full:list<string>, nights:list<string>, rows:list<array>}
     */
    public function availableNights(int $paketId, string $checkIn, string $checkOut): array
    {
        $nights = $this->nightsBetween($checkIn, $checkOut);
        if ($nights === []) {
            return ['ok' => false, 'missing' => [], 'full' => [], 'nights' => [], 'rows' => []];
        }

        $rows = $this->where('paket_wisata_id', $paketId)
            ->whereIn('tanggal', $nights)
            ->findAll();

        $byDate = [];
        foreach ($rows as $row) {
            $byDate[$row['tanggal']] = $row;
        }

        $missing = [];
        $full = [];
        foreach ($nights as $tgl) {
            if (!isset($byDate[$tgl])) {
                $missing[] = $tgl;
                continue;
            }
            $sisa = (int) $byDate[$tgl]['kuota'] - (int) $byDate[$tgl]['kuota_terpakai'];
            if ($sisa < 1) {
                $full[] = $tgl;
            }
        }

        return [
            'ok' => $missing === [] && $full === [],
            'missing' => $missing,
            'full' => $full,
            'nights' => $nights,
            'rows' => array_values($byDate),
        ];
    }

    /**
     * Lock 1 kuota untuk setiap malam di rentang.
     */
    public function lockRange(int $paketId, string $checkIn, string $checkOut): bool
    {
        $nights = $this->nightsBetween($checkIn, $checkOut);
        if ($nights === []) {
            return false;
        }

        $db = $this->db;
        $db->transStart();

        $rows = $db->query(
            'SELECT * FROM jadwal_paket_wisata WHERE paket_wisata_id = ? AND tanggal IN ('
            . implode(',', array_fill(0, count($nights), '?'))
            . ') FOR UPDATE',
            array_merge([$paketId], $nights)
        )->getResultArray();

        $byDate = [];
        foreach ($rows as $row) {
            $byDate[$row['tanggal']] = $row;
        }

        foreach ($nights as $tgl) {
            if (!isset($byDate[$tgl])) {
                $db->transRollback();

                return false;
            }
            $row = $byDate[$tgl];
            $sisa = (int) $row['kuota'] - (int) $row['kuota_terpakai'];
            if ($sisa < 1) {
                $db->transRollback();

                return false;
            }
        }

        foreach ($nights as $tgl) {
            $db->table('jadwal_paket_wisata')
                ->where('id', (int) $byDate[$tgl]['id'])
                ->set('kuota_terpakai', 'kuota_terpakai + 1', false)
                ->update();
        }

        $db->transComplete();

        return $db->transStatus();
    }

    public function releaseRange(int $paketId, string $checkIn, string $checkOut): bool
    {
        $nights = $this->nightsBetween($checkIn, $checkOut);
        if ($nights === []) {
            return false;
        }

        $ok = true;
        foreach ($nights as $tgl) {
            $updated = $this->db->table('jadwal_paket_wisata')
                ->where('paket_wisata_id', $paketId)
                ->where('tanggal', $tgl)
                ->where('kuota_terpakai >=', 1)
                ->set('kuota_terpakai', 'kuota_terpakai - 1', false)
                ->update();
            if (!$updated) {
                $ok = false;
            }
        }

        return $ok;
    }

    /**
     * Lock kuota dalam transaksi DB. Return false jika penuh.
     */
    public function lockKuota(int $jadwalId, int $jumlah): bool
    {
        $db = $this->db;
        $db->transStart();

        $jadwal = $db->query(
            'SELECT * FROM jadwal_paket_wisata WHERE id = ? FOR UPDATE',
            [$jadwalId]
        )->getRowArray();

        if (!$jadwal) {
            $db->transRollback();

            return false;
        }

        if ((int) $jadwal['kuota_terpakai'] + $jumlah > (int) $jadwal['kuota']) {
            $db->transRollback();

            return false;
        }

        $db->table('jadwal_paket_wisata')
            ->where('id', $jadwalId)
            ->set('kuota_terpakai', 'kuota_terpakai + ' . (int) $jumlah, false)
            ->update();

        $db->transComplete();

        return $db->transStatus();
    }

    public function releaseKuota(int $jadwalId, int $jumlah): bool
    {
        return (bool) $this->db->table('jadwal_paket_wisata')
            ->where('id', $jadwalId)
            ->where('kuota_terpakai >=', $jumlah)
            ->set('kuota_terpakai', 'kuota_terpakai - ' . (int) $jumlah, false)
            ->update();
    }
}
