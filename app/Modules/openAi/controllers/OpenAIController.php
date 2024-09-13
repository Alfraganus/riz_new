<?php

namespace App\Modules\openAi\controllers;

use App\Modules\openAi\service\GlensService;
use App\Modules\openAi\service\OpenAiImageChatService;
use App\Modules\openAi\service\OpenAiRandomPickService;
use App\Modules\openAi\service\OpenAiTextChatService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class OpenAIController extends BaseController
{

    public function __construct(
        OpenAiImageChatService  $openAiService,
        OpenAiTextChatService   $aiTextChatService,
        OpenAiRandomPickService $openAiRandomPickService,
        GlensService            $glensService
    )
    {
        $this->openAiService = $openAiService;
        $this->aiTextChatService = $aiTextChatService;
        $this->openAiRandomPickService = $openAiRandomPickService;
        $this->glensService = $glensService;
    }

    public function gptAdviceFromImage(Request $request)
    {
        return $this->openAiService->recognizeObject($request);
    }

    public function gptAdviceFromText(Request $request)
    {
        return $this->aiTextChatService->sendTextChatToGPT($request);
    }

    public function gptRandomPick(Request $request)
    {
        return $this->openAiRandomPickService->sendRandomPickGPT($request);
    }

    public function gptGlens(Request $request)
    {
        return $this->glensService->recognizeImage($request);
    }
}
