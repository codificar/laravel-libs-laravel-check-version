<?php

namespace Codificar\CheckVersion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckVersionFormRequest extends FormRequest
{
	private $GooglePlayStoreLink = 'https://play.google.com/store/apps/details?id=%s&hl=en';
	private $AppStoreLink = 'http://itunes.apple.com/lookup?lang=pt&bundleId=%s&country=br';

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$return = [
			'type'		=> 'required|in:android,ios',
			'bundle_id'	=> 'required',
			'url'		=> 'required'
		];

		return $return;
	}

	/**
	 * Retorna um json caso a validação falhe.
	 * @throws HttpResponseException
	 */
	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(
			response()->json([
				'success' => false,
				'errors' => $validator->errors()->all(),
				'error_code' => \ApiErrors::REQUEST_FAILED
			], \ApiErrors::REQUEST_FAILED)
		);
	}

	/**
	 * Prepare the data for validation.
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$url = $this->type == 'android' ? sprintf($this->GooglePlayStoreLink, $this->bundle_id) : sprintf($this->AppStoreLink, $this->bundle_id);

		$this->merge([
			'url'	=> $url,
		]);
	}
}
