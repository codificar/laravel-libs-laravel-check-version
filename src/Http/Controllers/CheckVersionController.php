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

		return response()->json([
			'version' => $version
		]);
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
		$html = file_get_contents($url);

		$matches = [];
		preg_match('/\[\[\[\"\d+\.\d+\.\d+/', $html, $matches);

		if (empty($matches) || count($matches) > 1) {
			throw new Exception('Could not fetch Android app version info!');
		}

		return substr(current($matches), 4);
	}

	/**
	 * Fetch Ios version
	 * @return view
	 */
	private function fetchIosVersion($url)
	{
		$response = Http::get($url);

		if (!$response && !response['results']) {
			throw new Exception("Unknown error connecting to iTunes.");
		}

		if (count(response['results']) == 0) {
			throw new Exception("App for this bundle ID not found.");
		}

		return $response['results'][0]['version'];
	}
}
