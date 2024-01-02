<?php

namespace Src\Presentation\Controllers;

use App\Http\Controllers\Controller;
use Src\Application\Controller\TransactionApplicationController;
use Src\Application\DTO\TransactionDTO;
use Src\Domain\Exception\TransactionException;
use Src\Domain\Exception\UserException;
use Src\Presentation\Requests\TransactionPostRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * @throws TransactionException
     * @throws UserException
     */
    public function transaction(
        TransactionPostRequest $request,
        TransactionApplicationController $appController
    ): JsonResponse
    {
        $transactionDTO = TransactionDTO::makeDto(
            array_merge(
                $request->validated(),
                ['payer_id' => $request->user()->id]
            )
        );

        $transaction = $appController->transaction($transactionDTO);

        return response()->json($transaction, Response::HTTP_CREATED);
    }
}
