<?php
namespace App\Modules\lens\controllers;

use App\Modules\lens\service\GoogleLensService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class GoogleLensController extends BaseController {

    public function __invoke(GoogleLensService $googleLensService, Request $request)
    {
        return $googleLensService->sendToGoogleLens($request);
    }

}
