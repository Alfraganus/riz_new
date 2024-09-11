<?php

namespace App\Modules\openAi\service;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class OpenAiRandomPickService extends OpenAiService
{

   public function sendRandomPickGPT(Request $request) {
        $apiKey = getenv('OPEN_AI_KEY');
        $client = new Client();

        try {
            $response = $client->post(self::GPT4_URL, [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'messages' => array_merge(
                        [
                            [
                                'role' => 'system',
                                'content' => self::getGPTCommands($request->input('response_level'), 'random_chat'),
                            ],
                        ],
                    ),
                ],
            ]);

            $apiResponse = json_decode($response->getBody(), true);
            $recognizedResponse = $apiResponse['choices'][0]['message']['content'] ?? 'Unknown response';

            return response()->json([
                'result' => $recognizedResponse
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to process chat',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
