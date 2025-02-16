<?php

namespace Codificar\CheckVersion\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

use Codificar\CheckVersion\Http\Requests\CheckVersionFormRequest;

use Exception;

class CheckVersionController extends Controller
{
    /**
     * @api {GET}/api/lib/checkversion
     *
     * fetch app version from playstore ou appstore
     *
     * @param CheckVersionFormRequest $request
     * @return json
     */
    public function getVersion(CheckVersionFormRequest $request)
    {
        $version = $this->fetchVersion($request->url, $request->type);

        if($version) {
            return response()->json([
                'success' => true,
                'version' => $version
            ]);
        }
        return response()->json([
            'success' => false,
            'error_code' => \ApiErrors::REQUEST_FAILED
        ], \ApiErrors::REQUEST_FAILED);

    }

    /**
     * Checks which store should fetch the app version
     *
     * @param String $url
     * @param String $type
     *
     * @return \Codificar\CheckVersion\Http\Controllers\view
     */
    private function fetchVersion($url, $type)
    {
        if ($type == 'android') {
            return $this->fetchAndroidVersion($url);
        }

        return $this->fetchIosVersion($url);
    }

    /**
     * Fetch Android version
     * @return view
     */
    private function fetchAndroidVersion($url)
    {
        try{
            $html = file_get_contents($url);
    
            $matches = [];
            preg_match('/\[\[\[\"\d+\.\d+\.\d+/', $html, $matches);
    
            if (empty($matches) || count($matches) > 1) {
                \Log::warning('Could not fetch Android app version info!');
                return false;
            }
    
            return substr(current($matches), 4);

        } catch(\Exception $e) {
            \Log::error($e->getMessage() . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Fetch Ios version
     * @return view
     */
    private function fetchIosVersion($url)
    {
        try {
            $response = Http::get($url);
    
            if (!$response && !$response['results']) {
                \Log::warning('Unknown error connecting to iTunes.');
                return false;
            }
    
            if (count($response['results']) == 0) {
                \Log::warning('App for this bundle ID not found.');
                return false;
            }

            return $response['results'][0]['version'];

        } catch(\Exception $e) {
            \Log::error($e->getMessage() . $e->getTraceAsString());
            return false;
        }

    }
}
