<?php
namespace App\swagger;

/**
 * @OA\Post(
 *     path="/api/get-gpt-random-pick",
 *     summary="Get GPT Random Pick",
 *     description="This endpoint sends a request to OpenAI GPT to pick a random response based on the input level.",
 *     operationId="gptRandomPick",
 *     tags={"OpenAI"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="response_level",
 *                 type="string",
 *                   example="medium",
 *                 description="The level of response expected from GPT (e.g., 'easy', 'medium', 'hard')"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Random response selected"
 *             ),
 *             @OA\Property(
 *                 property="response_data",
 *                 type="object",
 *                 description="Details of the random pick"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request"
 *     )
 * )
 */

class ChatRandomPickSwagger {}
