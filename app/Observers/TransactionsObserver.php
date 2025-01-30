<?php

namespace App\Observers;

use App\Models\Members;
use App\Models\Transactions;
use Illuminate\Support\Facades\DB;

class TransactionsObserver
{
    /**
     * Handle the Transactions "created" event.
     */
    public function created(Transactions $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $this->updateMemberNominal($transaction, 'increment');
        });
    }

    /**
     * Handle the Transactions "updated" event.
     */
    public function updated(Transactions $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            // Jika ada perubahan type atau nominal
            if ($transaction->isDirty(['type', 'nominal'])) {
                $originalType = $transaction->getOriginal('type');
                $originalNominal = $this->parseNominal($transaction->getOriginal('nominal'));
                $newNominal = $this->parseNominal($transaction->nominal);

                // Kurangi nominal lama
                $this->adjustMemberNominal(
                    $transaction->member,
                    $originalType,
                    -$originalNominal
                );

                // Tambahkan nominal baru
                $this->adjustMemberNominal(
                    $transaction->member,
                    $transaction->type,
                    $newNominal
                );
            }
        });
    }

    /**
     * Handle the Transactions "deleted" event.
     */
    public function deleted(Transactions $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $this->updateMemberNominal($transaction, 'decrement');
        });
    }

    /**
     * Handle the Transactions "restored" event.
     */
    public function restored(Transactions $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $this->updateMemberNominal($transaction, 'increment');
        });
    }

    /**
     * Update nominal member berdasarkan operasi (increment/decrement)
     */
    private function updateMemberNominal(Transactions $transaction, string $operation): void
    {
        $member = $transaction->member;

        // Pastikan member tersedia
        if (!$member) {
            logger("Transaction restored but member not found");
            return;
        }

        $column = $this->mapTypeToColumn($transaction->type);
        $nominal = $this->parseNominal($transaction->nominal);

        // Handle nilai NULL
        if (is_null($member->$column)) {
            $member->$column = 0;
            $member->save();
        }

        // Update nilai
        $member->$operation($column, $nominal);
        $this->updateMemberTotals($member);
    }


    /**
     * Penyesuaian nominal member
     */
    private function adjustMemberNominal(Members $member, string $type, float $nominal): void
    {
        $column = $this->mapTypeToColumn($type);
        $member->increment($column, $nominal);
        $this->updateMemberTotals($member);
    }

    /**
     * Konversi nilai nominal ke float
     */
    private function parseNominal($value): float
    {
        // Jika nilai berupa string dengan format angka (e.g., "1,000.50")
        if (is_string($value)) {
            return floatval(str_replace(',', '', $value));
        }

        return floatval($value);
    }

    /**
     * Mapping type transaction ke kolom member
     */
    private function mapTypeToColumn(string $type): string
    {
        return match ($type) {
            'room'      => 'nominal_room',
            'fnb'       => 'nominal_resto',
            'laundry'   => 'nominal_laundry',
            'transport' => 'nominal_transport',
            'spa'       => 'nominal_spa',
            'other'     => 'nominal_other',
            default     => throw new \Exception("Invalid transaction type: $type"),
        };
    }

    /**
     * Update total nominal, poin, reward, dan tier member
     */
    private function updateMemberTotals(Members $member): void
    {
        // Ambil semua nilai nominal (handle null sebagai 0)
        $nominalRoom = $this->parseNominal($member->nominal_room ?? 0);
        $nominalResto = $this->parseNominal($member->nominal_resto ?? 0);
        $nominalLaundry = $this->parseNominal($member->nominal_laundry ?? 0);
        $nominalTransport = $this->parseNominal($member->nominal_transport ?? 0);
        $nominalSpa = $this->parseNominal($member->nominal_spa ?? 0);
        $nominalOther = $this->parseNominal($member->nominal_other ?? 0);

        $total = $nominalRoom + $nominalResto + $nominalLaundry + $nominalTransport + $nominalSpa + $nominalOther;

        // Hitung poin dan reward
        $point = $total / 100000;
        $poin = floor($point);
        $reward = ceil($point * 100);

        // Ambil tier dari method di model
        $tier = Members::getTierOptions($total);

        $member->update([
            'total_nominal' => $total,
            'poin'         => $poin,
            'reward'       => $reward,
            'tier'         => $tier['name'], // Sesuaikan dengan struktur return getTierOptions
        ]);
    }
}
