<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;

class PaymentController extends Controller
{/**

 * @return AnonymousResourceCollection
 *
 * @OA\Get(
 *       path="/api/v1/payments",
 *       summary="Get a list of payments",
 *       description="get a list of payments",
 *       tags={"Payments"},
 *
 *       @OA\Response(response=200, description="Successful operation"),
 *       @OA\Response(response=400, description="Invalid request")
 *   )
 *
 */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return PaymentResource::collection(Payment::with(["user"])->latest()->get());
    }

}
