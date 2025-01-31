<?php
// app/Exports/TransactionExport.php
namespace App\Exports;

use App\Models\Transactions;

class TransactionExport extends BaseExport
{
    public function __construct()
    {
        $this->model = Transactions::class;
        $this->columns = [
            'ID Transaksi',
            'Tanggal Transaksi',
            'Nama Hotel',
            'Nama Member',
            'Total'
        ];
    }

    public function query()
    {
        return Transactions::with(['hotel', 'member'])
            ->select('id', 'hotel_id', 'member_id', 'total', 'created_at');
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->created_at->format('d-m-Y H:i'),
            $transaction->hotel->name,
            $transaction->member->name,
            $transaction->type,
            $transaction->nominal,
        ];
    }
}