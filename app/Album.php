<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

class Album extends Model {
	use SoftDeletes;

	/******************************************/
	//               Attributes
	/******************************************/
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'recorded_date',
		'release_date',
		'number_of_tracks',
		'label',
		'producer',
		'genre',
	];

	/**
	 * The attributes that are appended to it's JSON form.
	 *
	 * @var array
	 */
	protected $appends = [
		'band_name',
	];

	/**
	 * The attributes that are cast to native types
	 *
	 * @var array
	 */
	protected $casts = [
		'number_of_tracks' => 'integer',
	];


	/******************************************/
	//              Relationships
	/******************************************/
	/**
	 * Get the user that created this album
	 *
	 * @return User
	 */
	public function user() {
		return $this->band->user;
	}

	/**
	 * Get the band this album belongs to
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function band() {
		return $this->belongsTo( 'App\Band' );
	}


	/******************************************/
	//                Accessors
	/******************************************/
	/**
	 * Get recorded_date attribute
	 *
	 * @return false|string
	 */
	public function getRecordedDateAttribute() {
		if ( ! empty( $this->attributes['recorded_date'] ) ) {
			return date( 'm/d/Y', strtotime( $this->attributes['recorded_date'] ) );
		}
	}

	/**
	 * Get release_date attribute
	 *
	 * @return false|string
	 */
	public function getReleaseDateAttribute() {
		if ( ! empty( $this->attributes['release_date'] ) ) {
			return date( 'm/d/Y', strtotime( $this->attributes['release_date'] ) );
		}
	}

	/**
	 * Get the band's name attribute for this album
	 *
	 * @return string
	 */
	public function getBandNameAttribute() {
		return $this->band['name'];
	}



	/*******************************************/
	//                Mutators
	/*******************************************/
	public function setRecordedDateAttribute( $date ) {
		if ( ! empty( $date ) ) {
			$this->attributes['recorded_date'] = Carbon::createFromFormat( 'm/d/Y', $date );
		}
	}

	public function setReleaseDateAttribute( $date ) {
		if ( ! empty( $date ) ) {
			$this->attributes['release_date'] = Carbon::createFromFormat( 'm/d/Y', $date );
		}
	}


	/******************************************/
	//                 Scopes
	/******************************************/
	/**
	 * Search the album table for albums that match the search criteria
	 *
	 * @param Builder $query
	 * @param string $search
	 * @param string $sort
	 * @param string $sort_dir
	 *
	 * @return Builder
	 */
	public function scopeSearch( $query, $search, $sort = 'name', $sort_dir = 'asc' ) {

		if ( ! empty( $search ) ) {
			//if we have a search value,
			//we want to find albums with matches against the
			//band name, album name, producer, label, or genre

			$query->join( 'bands', 'albums.band_id', '=', 'bands.id' )
			      ->select( 'bands.id', 'bands.name as band_name', 'albums.*' )
			      ->where( 'bands.name', 'like', "%$search%" )
			      ->orwhere( 'albums.name', 'like', "%$search%" )
			      ->orwhere( 'albums.label', 'like', "%$search%" )
			      ->orwhere( 'albums.genre', 'like', "%$search%" )
			      ->orwhere( 'albums.producer', 'like', "%$search%" );

		} elseif ( $sort === 'band_name' ) {

			//since we are sorting by band_name we still need to join tables
			$query->join( 'bands', 'albums.band_id', '=', 'bands.id' )
			      ->select( 'bands.id', 'bands.name as band_name', 'albums.*' );

		}

		//finally, sort by the sort column and direction and return the query
		return $query->orderBy( $sort, $sort_dir );
	}

}
