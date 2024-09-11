<?php
namespace App\swagger;

/**
 * @OA\Post(
 *     path="/api/get-gpt-advice-by-text",
 *     summary="Get GPT advice based on text messages",
 *     description="Send a series of text messages to GPT-4 along with a response level and receive advice or responses.",
 *     tags={"OpenAI"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="messages",
 *                 type="array",
 *                 description="Array of text messages to send to GPT",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="role", type="string", example="my friend"),
 *                     @OA\Property(property="text", type="string", example="I wish I had barbercue for a lunch")
 *                 ),
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="role", type="string", example="assistant"),
 *                     @OA\Property(property="text", type="string", example="The meaning of life is subjective.")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="response_level",
 *                 type="string",
 *                 description="The level of response detail you want from GPT",
 *                 example="medium"
 *             ),
 *             required={"messages", "response_level"}
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful response with GPT advice",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="result", type="string", description="Advice or response from GPT")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request, e.g., if messages or response level are missing or invalid"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server Error, if something goes wrong on the server side"
 *     )
 * )
 */

class ChatTextAdviserSwagger {}
