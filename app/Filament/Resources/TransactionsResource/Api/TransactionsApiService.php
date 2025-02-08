<?php
namespace App\Filament\Resources\TransactionsResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\TransactionsResource;
use Illuminate\Routing\Router;


class TransactionsApiService extends ApiService
{
    protected static string | null $resource = TransactionsResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
