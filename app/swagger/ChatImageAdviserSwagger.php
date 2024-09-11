<?php
namespace App\swagger;

/**
/**
 * @OA\Info(
 *     title="My API",
 *     version="1.0.0",
 *     description="This is a sample API documentation.",
 *     @OA\Contact(
 *         email="support@example.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * @OA\Post(
 *     path="/api/get-gpt-advice-by-image",
 *     summary="Get GPT advice based on image",
 *     description="Upload an image file to get advice or a response from GPT.",
 *     tags={"OpenAI"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(
 *                     property="image",
 *                     description="The image file to send for analysis.",
 *                     type="string",
 *                     format="binary"
 *                 ),
 *                 required={"image"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful response with GPT advice",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="advice", type="string", description="Advice or response from GPT")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request, e.g., if the file is missing or invalid"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server Error, if something goes wrong on the server side"
 *     )
 * )
 */

class ChatImageAdviserSwagger {}
