<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $object
     * @param $prefix
     * @return JsonResponse
     */
    public function jsonResponse($object, $prefix)
    {
        if ($object instanceof JsonResponse) {
            return $object;
        } else {
            return response()->json(['success' => true, $prefix => $object], 200);
        }
    }

    /**
     * @param array $array
     * @return JsonResponse
     */
    public function jsonResponses($array = [])
    {
        return response()->json(array_merge(['success' => true], $array), 200);
    }
}
