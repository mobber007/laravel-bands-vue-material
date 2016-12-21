<?php

namespace App\Http\Controllers;

use App\Band;
use App\Http\Requests\BandRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class BandController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$user     = Auth::user();
		$per_page = Input::get( 'per_page' );
		$sort_by  = Input::get( 'sort' ) ? Input::get( 'sort' ) : 'name';
		$sort_dir = Input::get( 'sort_dir' ) ? Input::get( 'sort_dir' ) : 'asc';
		$query    = Input::get( 'query' );

		if ( $per_page == - 1 ) {
			//pass -1 to get all bands - ie non-paginated
			//this is used for getting the dropdown list
			if ( $user->is_admin ) {
				//user is admin
				//get all bands in the system
				$bands = Band::search( $query )
				             ->orderBy( $sort_by, $sort_dir )
				             ->get()
				             ->all();
			} else {
				//user is not an admin
				//only get this user's bands
				$bands = $user->bands()
				              ->search( $query )
				              ->orderBy( $sort_by, $sort_dir )
				              ->get()
				              ->all();
			}
		} else {
			//paginate everything else
			if ( $user->is_admin ) {
				//user is admin
				//get all bands in the system
				$bands = Band::search( $query )
				             ->orderBy( $sort_by, $sort_dir )
				             ->paginate( $per_page );
			} else {
				//user is not an admin
				//only get this user's bands
				$bands = $user->bands()
				              ->search( $query )
				              ->orderBy( $sort_by, $sort_dir )
				              ->paginate( $per_page );
			}
		}

		return response()->json( $bands );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param BandRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( BandRequest $request ) {
		$band = new Band;

		$band->fill( $request->all() );

		$band->user()->associate( Auth::user() );

		$band->save();

		return response()->json( [
			'message' => [
				'type'    => 'success',
				'content' => "You have successfully added the band: $band->name.",
			],
			'created' => $band,
		] );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( $id ) {
		$band = Band::findOrFail( $id );
		$user = Auth::user();

		if ( $user->can( 'view', $band ) ) {

			return response()->json( $band );

		} else {

			return response()->json( [
				'message' => [
					'type'    => 'error',
					'content' => 'You are not allowed to edit this band.',
				],
			], 401 );

		}


	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param BandRequest $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( BandRequest $request, $id ) {
		$band = Band::findOrFail( $id );
		$user = Auth::user();

		if ( $user->can( 'update', $band ) ) {
			$band->fill( $request->all() );

			$band->user()->associate( $user );

			$band->save();

			return response()->json( [
				'message' => [
					'type'    => 'success',
					'content' => "You have successfully updated the band: $band->name.",
				],
				'updated' => $band,
			] );
		} else {
			return response()->json( [
				'message' => [
					'type'    => 'error',
					'content' => 'You are not allowed to edit this band.',
				],
			], 401 );
		}


	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id ) {
		$band = Band::findOrFail( $id );
		$user = Auth::user();

		if ( $user->can( 'delete', $band ) ) {

			$band->delete();

			return response()->json( [
				'message' => [
					'type'    => 'success',
					'content' => 'Band deleted.',
				],
			] );

		} else {

			return response()->json( [
				'message' => [
					'type'    => 'error',
					'content' => 'You are not allowed to delete this band.',
				],
			], 401 );

		}
	}
}
