<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Transactions; // Import jika diperlukan
use App\Models\VoucherDetail; // Import jika diperlukan

class RedeemLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $relatedModelInfo = null;
        if ($this->modelable instanceof Transactions) {
            $relatedModelInfo = [
                'type' => 'Transaction',
                'id' => $this->modelable->id,
                // Tambahkan detail transaksi lain jika perlu
                // 'invoice_number' => $this->modelable->invoice_number,
            ];
        } elseif ($this->modelable instanceof VoucherDetail) {
            $relatedModelInfo = [
                'type' => 'VoucherDetail',
                'id' => $this->modelable->id,
                // Tambahkan detail voucher lain jika perlu
                // 'voucher_code' => $this->modelable->voucher->code, // Contoh
            ];
        }

        return [
            'id' => $this->id,
            'redeemed_item_type' => class_basename($this->model_type), // 'Transaction' atau 'VoucherDetail'
            'redeemed_item_id' => $this->model_id,
            'redeemed_item_details' => $relatedModelInfo, // Informasi tambahan dari model terkait
            // Tambahkan field lain dari RedeemLog yang ingin ditampilkan
            // 'reason' => $this->reason,
            'redeemed_at' => $this->created_at->toIso8601String(), // Format tanggal standar
        ];
    }
}
