<?php

namespace App\Http\Controllers\Api\v1\Transaction;

use App\Enum\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class TransactionController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/v1/payment/request",
     *     tags={"Transaction"},
     *     summary="Create a new transaction",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount"},
     *             @OA\Property(property="amount", type="number", format="float", example=100000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transaction created successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function create(Request $request): JsonResponse
    {
        $transaction = Transaction::create([
            'transaction_id' => uniqid('', true),
            'user_id' => $request->user()->id,
            'amount' => $request->amount,
            'status' => TransactionStatus::Pending,
        ]);

        return response()->json([
            'code' => 100,
            'message' => 'Transaction created successfully',
            'authority' => $transaction->transaction_id,
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/payment/verify",
     *     tags={"Transaction"},
     *     summary="Verify a transaction",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"authority"},
     *             @OA\Property(property="authority", type="string", example="5f8f8c44d6e36")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Transaction verified successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Transaction already verified or failed"
     *     )
     * )
     */
    public function verify(Request $request): JsonResponse
    {
        $transaction = Transaction::where('transaction_id', $request->authority)->firstOrFail();

        if ($transaction->status === TransactionStatus::Pending) {
            $transaction->update(['status' => TransactionStatus::Verified]);
            return response()->json([
                'code' => 100,
                'message' => 'Transaction verified successfully',
            ]);
        }

        return response()->json([
            'code' => 101,
            'message' => 'Transaction already verified or failed',
        ]);
    }
}
