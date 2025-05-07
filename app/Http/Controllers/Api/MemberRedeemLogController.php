<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\RedeemLogResource;
use App\Models\Members; // Pastikan model Member diimport
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;


class MemberRedeemLogController extends Controller
{
    /**
     * Display a listing of the member's redeem logs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        /** @var \App\Models\Members $member */
        $member = Auth::user(); // Mendapatkan member yang terotentikasi

        if (!$member) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Opsi 1: Menggunakan accessor dan melakukan paginasi manual pada collection
        // Ini mengambil semua log dulu, lalu dipaginasi. Kurang ideal untuk dataset sangat besar.
        // $allLogs = $member->all_redeem_logs; // Menggunakan accessor getAllRedeemLogsAttribute
        // $perPage = $request->input('per_page', 15); // Ambil per_page dari request atau default 15
        // $currentPage = Paginator::resolveCurrentPage('page');
        // $currentPageItems = $allLogs->slice(($currentPage - 1) * $perPage, $perPage)->values();
        // $paginatedItems = new LengthAwarePaginator($currentPageItems, $allLogs->count(), $perPage, $currentPage, [
        //     'path' => Paginator::resolveCurrentPath(),
        // ]);
        // return RedeemLogResource::collection($paginatedItems);

        // Opsi 2: Menggunakan method yang sudah dirancang untuk paginasi (lebih efisien)
        $perPage = $request->input('per_page', 15);
        $paginatedLogs = $member->getPaginatedRedeemLogs($perPage);

        return RedeemLogResource::collection($paginatedLogs);
    }
}
