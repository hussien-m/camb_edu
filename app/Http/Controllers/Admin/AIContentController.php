<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AIGenerateRequest;
use App\Http\Requests\Admin\AIImproveRequest;
use App\Services\Admin\AIContentService;

class AIContentController extends Controller
{
    protected $service;

    public function __construct(AIContentService $service)
    {
        $this->service = $service;
    }

    /**
     * Generate content based on title
     */
    public function generateContent(AIGenerateRequest $request)
    {
        $result = $this->service->generateContent($request->input('title'));

        if (!$result['success']) {
            $code = $result['code'] ?? 500;
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], $code);
        }

        return response()->json([
            'success' => true,
            'content' => $result['content'],
            'provider' => $result['provider']
        ]);
    }

    /**
     * Improve existing content
     */
    public function improveContent(AIImproveRequest $request)
    {
        $result = $this->service->improveContent($request->input('content'));

        if (!$result['success']) {
            $code = $result['code'] ?? 500;
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], $code);
        }

        return response()->json([
            'success' => true,
            'content' => $result['content'],
            'provider' => $result['provider']
        ]);
    }
}
