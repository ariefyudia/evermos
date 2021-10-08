<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    public function index(Request $request)
    {
        $response[] = auth()->user();
        // ApiLogStore::handle($request, $response, now()->diffInSeconds($request->request_time), $request->request_time, [$request->client, auth()->user()]);
        return response()->json([
            'success' => true,
            'message' => 'Profile '. auth()->user()->name,
            'data' => auth()->user()
        ]);
    }
}
