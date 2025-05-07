<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PromotionalResource;
use App\Models\Promotional;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PromotionalApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $limit = (int) $request->query('limit', 5);
        $page  = (int) $request->query('page', 1);

        $banners = Promotional::orderBy('order')
            ->paginate($limit, ['*'], 'page', $page);

        return response()->json([
            'status' => 'success',
            'data'   => PromotionalResource::collection($banners),
            'meta'   => [
                'current_page' => $banners->currentPage(),
                'per_page'     => $banners->perPage(),
                'last_page'    => $banners->lastPage(),
                'total'        => $banners->total(),
            ],
        ]);
    }
}