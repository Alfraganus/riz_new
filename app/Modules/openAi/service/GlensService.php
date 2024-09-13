<?php

namespace App\Modules\openAi\service;

use App\Modules\lens\service\GoogleLensService;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GlensService extends OpenAiService
{
    const GPT4_URL = 'https://api.openai.com/v1/chat/completions';


    public function recognizeImage(Request $request)
    {
        $languages = [
            'en' => 'English',
            'ru' => 'Russian',
            'fr' => 'French',
            'de' => 'German',
            'es' => 'Spanish',
        ];

        $file = $request->file('image');
        $lang = $request->input('lang');
        $filename = Str::random(40) . '.' . $file->getClientOriginalExtension(); // Generate a random filename
        $imagePath = $file->storeAs('images', $filename, 'public');
        $imageUrl = asset('storage/' . $imagePath);


        $response = [
            'tags' => [],
            'web_sources' => [],
            'similar_images' => [],
            'labels' => [],
            'did_you_know' => null,
            'price' => null,
            'description' => null,
            'facts' => [],
        ];
        $keyword_gpt = $this->imageMessage($imageUrl);

        /*google lens*/
        $client = new Client();
        $response = $client->request('GET', GoogleLensService::SERP_API, [
            'query' => [
                'engine' => 'google_lens',
                'url' => $imageUrl,
                'api_key' => GoogleLensService::LENS_API_KEY
            ]
        ]);

        $result = json_decode($response->getBody(), true);
        $keywords = [];
        foreach ($result['visual_matches'] as $search_result) {
            $response['web_sources'][] = [
                'title' => $search_result['title'],
                'url' => $search_result['link'],
                'site' => $search_result['source'],
                'favicon' => $search_result['source_icon'],
                'og_title' => $search_result['title'],
                'og_image' => $search_result['thumbnail'],
                'og_description' => $search_result['title'],
            ];

            $response['similar_images'][] = $search_result['thumbnail'];
            $words = explode(' ', $search_result['title']);
            if ($words) {
                foreach ($words as $word) {
                    if (mb_strlen($word) >= 3 && !in_array(mb_strtolower($word), ['the', 'and', 'aboard', 'about', 'above', 'abreast', 'absent', 'across', 'after', 'against', 'aloft', 'along', 'alongside', 'amid', 'amidst', 'mid', 'midst', 'among', 'amongst', 'anti', 'apropos', 'around', 'round', 'aslant', 'astride', 'ontop', 'barring', 'before', 'behind', 'below', 'beneath', 'neath', 'beside', 'besides', 'between', 'beyond', 'but', 'come', 'concerning', 'contra', 'counting', 'cum', 'despite', 'down', 'during', 'ere', 'except', 'excepting', 'excluding', 'failing', 'following', 'for', 'from', 'including', 'inside', 'into', 'less', 'like', 'minus', 'modulo', 'modВ ', 'near', 'nearerВ ', 'nearestВ ', 'next', 'of', 'off', 'offshore', 'in', 'at', 'a', 'on', 'onto', 'opposite', 'out', 'outside', 'over', 'pace', 'past', 'pending', 'per', 'plus', 'post', 'pre', 'pro', 'qua', 're', 'regarding', 'respecting', 'sans', 'save', 'saving', 'since', 'sub', 'than', 'through', 'thruВ ', 'throughout', 'thruoutВ ', 'till', 'times', 'to', 'toward', 'towards', 'under', 'underneath', 'unlike', 'until', 'untoВ ', 'up', 'upon', 'versus', 'vs.В ', 'via', 'wanting', 'with', 'within', 'without', 'w/oВ ', 'worth', 'abroad', 'adrift', 'aft', 'afterward', 'afterwards', 'ahead', 'apart', 'ashore', 'aside', 'away', 'back', 'backward', 'backwards', 'beforehand', 'downhill', 'downstage', 'downstairs', 'downstream', 'downwards', 'downwind', 'east', 'eastwards', 'forward', 'heavenward', 'hence', 'henceforth', 'here', 'hereby', 'herein', 'hereof', 'hereto', 'herewith', 'homeward', 'indoors', 'inward', 'leftward', 'north', 'northeast', 'northward', 'northwest', 'now', 'onward', 'outdoors', 'outward', 'overboard', 'overhead', 'overland', 'overseas', 'rightward', 'seaward', 'skyward', 'south', 'southeast', 'southward', 'southwest', 'then', 'thence', 'thenceforth', 'there', 'thereby', 'therein', 'thereof', 'thereto', 'therewith', 'together', 'underfoot', 'underground', 'uphill', 'upstage', 'upstairs', 'upstream', 'upward', 'upwind', 'west', 'westward', 'when', 'whence', 'where', 'whereby', 'wherein', 'whereto', 'wherewith', 'after', 'although', 'as', 'because', 'before', 'beside', 'besides', 'between', 'by', 'considering', 'despite', 'except', 'for', 'from', 'given', 'granted', 'ifВ ', 'into', 'lest', 'like', 'notwithstanding', 'now', 'of', 'on', 'once', 'provided', 'providing', 'save', 'seeing', 'since', 'so', 'supposing', 'than', 'though', 'till', 'to', 'unless', 'until', 'upon', 'when', 'whenever', 'where', 'whereas', 'wherever', 'while', 'whilst', 'with', 'without', 'ago', 'apart', 'aside', 'aslant', 'away', 'hence', 'notwithstanding', 'on', 'over', 'shortВ ', 'throughВ ', 'according', 'across', 'ahead', 'along', 'apart', 'instead', 'near', 'opposite'])) {
                        if (isset($keywords[$word])) {
                            $keywords[$word] += 1;
                        } else {
                            $keywords[$word] = 1;
                        }
                    }
                }
                arsort($keywords);
            }

        }

        if (count($keywords) >= 3) {
            $response['tags'] = array_slice(array_keys($keywords), 0, 3);
        } else {
            $response['tags'] = array_keys($keywords);
        }
        if ($keyword_gpt || count($response['tags']) >= 3) {

            $label = $keyword_gpt ?? key($keywords); //join(', ', array_slice($response['tags'],0,3));


            if ($keyword_gpt) {

                $label1 = $label;
                if ($lang != 'en') {
                    $label1 = $this->chatMessage('translate to ' . $languages[$lang] . ' language if its in ' . $languages[$lang] . ': ' . $label . '');
                }

                $response['labels'] = [$label1]; // $message;
                $response['tags'] = [$label1]; // $message;
            } else {
                $response['labels'][] = $label;
            }


            if (strlen($label) >= 3) {
                $response['did_you_know'] = $this->chatMessage('say interesting fact about "' . $label . '", answer no more that 30 words, or say what I may don\'t know about it. answer in ' . $languages[$lang] . ' language');
                $response['price'] = $this->chatMessage('say price of "' . $label . '" in usd without comment, without word price in the answer, just only price number or only price range, give very short answer. answer in ' . $languages[$lang] . ' language');
                $response['description'] = $this->chatMessage('what can you tell me about "' . $label . '", answer no more that 50 words.answer in ' . $languages[$lang] . ' language');

                $facts = trim($this->chatMessage('say several facts about "' . $label . '", answer in list of short technical characteristics or parameters of it (for example it weight, age, color, power, size e.t.c) with exact values, like characteristics  and exact value, very short dry list items. answer in ' . $languages[$lang] . ' language'));
                $facts = explode("\n", $facts);
                if (count($facts) >= 3) {
                    foreach ($facts as $fact) {
                        $response['facts'][] = trim(trim($fact), '- +,.');
                    }
                }
            }
        }
        return response()->json($response);
    }



    public function imageMessage($image)
    {
        $open_ai_key = env('OPENAI_API_KEY');

        $client = new Client();
        $response = $client->post(self::GPT4_URL, [
            'headers' => [
                'Authorization' => "Bearer {$open_ai_key}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    "role" => "user",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => "You are an advanced assistant for recognizing objects in photos. you recognize the main object in the photo and write what it is.
you can recognize absolutely any object. Below are detailed exceptions:
If you recognize the car, indicate its brand and model. If you cannot recognize the model, write down its brand.
If you recognize flowers, indicate the name of the flower.
if you recognize food, indicate its name, as accurate as possible (for example, the name of pizza, type of apples, berries, etc.)
If you recognize a toy (for children), indicate its name in as detailed a manner as possible.
if you recognize a book, indicate its author and title.
If you recognize the equipment, indicate its model and manufacturer.
If possible, always indicate the model of the item and its manufacturer if this product is popular (except for food)
answers should be short (one or two words, or the full name of the model).
There is no need to describe the color of the item or its detailed characteristics. Just need name/model.
Response format without words of clarification (without \"this, in the photo, here\" and so on) - just the name of the item or model\n"
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => "data:image/jpeg;base64," . base64_encode($image)
                            ]
                        ]
                    ]
                ],
            ],
        ]);

        $apiResponse = json_decode($response->getBody(), true);
        $recognizedObject = $apiResponse['choices'][0]['message']['content'] ?? 'Unknown object';

        return $recognizedObject;
    }

    public function chatMessage($message)
    {
        $apiKey = getenv('OPEN_AI_KEY');
        $client = new Client();
        $messages[] = [
            "role" => "user",
            "content" => $message
        ];

        try {
            $response = $client->post(self::GPT4_URL, [
                'headers' => [
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',
                    'messages' => $messages
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
