<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use OpenApi\Annotations as OA;

class HomeController extends Controller
{
    /**
     * @OA\PathItem(path="/api/v1")
     *
     * @OA\Info(
     *      version="1.0.0",
     *      title="Anophel API Documentation"
     *  )
     */
    public function index(): string
    {
        return "V1";
    }
}
