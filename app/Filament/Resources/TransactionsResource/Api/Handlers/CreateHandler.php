<?php
namespace App\Filament\Resources\TransactionsResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use App\Filament\Resources\TransactionsResource;
use App\Filament\Resources\TransactionsResource\Api\Requests\CreateTransactionsRequest;

class CreateHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = TransactionsResource::class;

    public static function getMethod()
    {
        return Handlers::POST;
    }

    public static function getModel() {
        return static::$resource::getModel();
    }

    /**
     * Create Transactions
     *
     * @param CreateTransactionsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handler(CreateTransactionsRequest $request)
    {
        $model = new (static::getModel());

        $model->fill($request->all());

        $model->save();

        return static::sendSuccessResponse($model, "Successfully Create Resource");
    }
}