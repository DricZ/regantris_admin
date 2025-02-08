<?php

namespace App\Filament\Resources\TransactionsResource\Api\Handlers;

use App\Filament\Resources\SettingResource;
use App\Filament\Resources\TransactionsResource;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Filament\Resources\TransactionsResource\Api\Transformers\TransactionsTransformer;

class DetailHandler extends Handlers
{
    public static bool $public = true;
    public static string | null $uri = '/{id}';
    public static string | null $resource = TransactionsResource::class;


    /**
     * Show Transactions
     *
     * @param Request $request
     * @return TransactionsTransformer
     */
    public function handler(Request $request)
    {
        $id = $request->route('id');

        $query = static::getEloquentQuery();

        $query = QueryBuilder::for(
            $query->where(static::getKeyName(), $id)
        )
            ->first();

        if (!$query) return static::sendNotFoundResponse();

        return new TransactionsTransformer($query);
    }
}