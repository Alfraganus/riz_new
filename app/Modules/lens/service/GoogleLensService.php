<?php

namespace App\Modules\lens\service;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GoogleLensService
{
    const LENS_API_KEY = '364d4e0519488e261248886c8c4555463407e3185b219d91b4a1df873d34ff0d';
    const SERP_API = 'https://serpapi.com/search.json';

    public function sendToGoogleLens(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            $imagePath = $file->storeAs('images', $filename, 'public');
            $imageFullPath = storage_path('app/public/' . $imagePath);

            if (!File::exists($imageFullPath)) {
                return response()->json(['error' => 'Failed to save the uploaded image'], 500);
            }
            $imageUrl = asset('storage/' . $imagePath);
        } else {
            return response()->json(['error' => 'No image uploaded'], 400);
        }
        $client = new Client();

        try {
            $response = $client->request('GET', self::SERP_API, [
                'query' => [
                    'engine' => 'google_lens',
                    'url' => $imageUrl,
                    'api_key' => self::LENS_API_KEY
                ]
            ]);

            $result = json_decode($response->getBody(), true);
            Storage::disk('public')->delete($imagePath);

            return response()->json($result['visual_matches']);

        } catch (\Exception $e) {
            Storage::disk('public')->delete($imagePath);
            return response()->json(['error' => 'Failed to process image', 'message' => $e->getMessage()], 500);
        }
    }
}
