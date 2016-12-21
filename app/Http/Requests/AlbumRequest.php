<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlbumRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		$rules = [
			'name' => 'required|string|between:0,255',
		];

		//we do it this way bc if empty strings are sent instead of null,
		//the validator will fail so we check first
		if ( ! empty( $this->get( 'recorded_date' ) ) ) {
			$rules['recorded_date'] = 'date';
		}

		if ( ! empty( $this->get( 'release_date' ) ) ) {
			$rules['release_date'] = 'date';
		}

		if ( ! empty( $this->get( 'number_of_tracks' ) ) ) {
			$rules['number_of_tracks'] = 'integer';
		}

		if ( ! empty( $this->get( 'label' ) ) ) {
			$rules['label'] = 'string|between:0,255';
		}

		if ( ! empty( $this->get( 'producer' ) ) ) {
			$rules['producer'] = 'string|between:0,255';
		}

		if ( ! empty( $this->get( 'genre' ) ) ) {
			$rules['genre'] = 'string|between:0,255';
		}

		return $rules;
	}
}
