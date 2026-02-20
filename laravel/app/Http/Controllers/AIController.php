<?php

namespace App\Http\Controllers;

use App\Http\Requests\AIRequest;
use App\Services\ApiAIService;
use Illuminate\Http\Request;

class AIController extends Controller
{
    private ApiAIService $apiAIService;
    public function __construct(
        ApiAIService $apiAIService
    )
    {
        $this->apiAIService = $apiAIService;
    }

    public function index() {
        $models = $this->apiAIService->models(env('API_AI_MODELS'));
        return view('ai')->with('models', $models);
    }
    public function chat(AiRequest $request)
    {
        $aiDTO = $request->toDTO();
        $message = $this->apiAIService->chat($aiDTO, env('API_AI_CHAT'));
        return response()->json([
            'message' => $message
        ]);
    }
}
