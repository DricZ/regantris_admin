<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use App\Models\RedeemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class VoucherApiController extends Controller
{
    /**
     * 1. Get paginated vouchers (lazy loading)
     * Endpoint: GET /api/vouchers?page={page}&limit={limit}
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('limit', 10);

        $vouchers = Voucher::whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->where('type', $request->query('type'))
            ->orderBy('start_date', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $vouchers->items(),
            'meta' => [
                'current_page' => $vouchers->currentPage(),
                'per_page' => $vouchers->perPage(),
                'last_page' => $vouchers->lastPage(),
                'total' => $vouchers->total(),
            ],
        ]);
    }

    /**
     * 2. Get single voucher by ID
     * Endpoint: GET /api/vouchers/{id}
     */
    public function show(int $id): JsonResponse
    {
        $voucher = Voucher::with('hotel')->find($id);

        if (!$voucher) {
            return response()->json([
                'status' => 'error',
                'message' => 'Voucher tidak ditemukan'
            ], 404);
        }

        $member = Auth::guard('api')->user();

        if($member){
            $code = VoucherDetail::
            where('member_id', $member->id)
            ->where('voucher_id', $id)
            ->first('code');
        }
        return response()->json([
            'status' => 'success',
            'data' => $voucher,
            'code' => $code['code'] ?? null
        ]);
    }

    /**
     * 2. Redeem a voucher for authenticated member
     * Endpoint: POST /api/vouchers/redeem
     * Body: { "voucher_id": int }
     */
    public function redeem(Request $request): JsonResponse
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
        ]);

        $member = Auth::guard('api')->user();

        if (!$member) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $voucher = Voucher::findOrFail($request->voucher_id);

        // Cari voucher detail yang belum di-redeem (member_id null)
        $availableDetail = VoucherDetail::where('voucher_id', $voucher->id)
            ->whereNull('member_id')
            ->first();

        if (!$availableDetail) {
            return response()->json(['status' => 'error', 'message' => 'Voucher tidak tersedia'], 404);
        }

        DB::beginTransaction();
        try {
            // Update voucher_detail
            $availableDetail->update(['member_id' => $member->id, 'claimed_at' => DB::raw('NOW()')]);

            $member->update(['poin' => ($member->poin - $voucher->price)]);

            // Buat redeem_log
            // RedeemLog::create([
            //     'voucher_detail_id' => $availableDetail->id,
            //     'member_id' => $member->id,
            //     'redeemed_at' => now(),
            // ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'code' => $availableDetail->code
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal redeem voucher',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}