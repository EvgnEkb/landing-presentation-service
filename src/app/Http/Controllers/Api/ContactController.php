<?php

namespace App\Http\Controllers\Api;

use App\Mail\OwnerMail;
use App\Mail\UserCopyMail;
use OpenApi\Attributes as OA;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use App\Services\AIAnalysisInterface;

class ContactController extends Controller
{
    public function __construct(
        protected AIAnalysisInterface $aiService
    ) {
    }

    #[OA\Post(
        path: '/api/contact',
        summary: 'Отправить сообщение через форму обратной связи',
        tags: ['Contact']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'phone', 'email', 'comment'],
            properties: [
                new OA\Property(property: 'name', type: 'string', example: 'Иван Иванов'),
                new OA\Property(property: 'phone', type: 'string', example: '+7 999 123-45-67'),
                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                new OA\Property(property: 'comment', type: 'string', example: 'Здравствуйте! У меня вопрос по заказу.'),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Успешно отправлено',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'object'),
                new OA\Property(property: 'ai_analysis', type: 'object', nullable: true),
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Ошибка валидации',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'errors', type: 'object'),
            ]
        )
    )]
    #[OA\Response(response: 500, description: 'Ошибка сервера')]
    public function store(ContactRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $aiResult = null;

        try {
            $aiResult = $this->aiService->analyze($validated['comment']);
        } catch (\Throwable $e) {
            Log::warning('AI analysis failed: ' . $e->getMessage());
        }

        try {
            Mail::to(config('mail.owner_email'))->queue(new OwnerMail($validated));
            Mail::to($validated['email'])->queue(new UserCopyMail($validated));
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Не удалось отправить письмо, попробуйте позже',
                'errors'  => ['email' => ['Ошибка отправки']],
            ], 500);
        }

        Log::info('Contact form submitted successfully', [
            'email'    => $validated['email'],
            'ai'       => $aiResult,
        ]);

        return response()->json([
            'message' => 'Ваше сообщение отправлено',
            'data'    => [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ],
            'ai_analysis' => $aiResult,
        ], 201);
    }
}
