<?php

namespace App\Http\Controllers;

use App\Album;
use App\Band;
use App\Http\Requests\AlbumRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AlbumController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$user     = Auth::user();

		//Query vars for sorting/pagination/search
		$per_page = Input::get( 'per_page' );
		$sort_by  = Input::get( 'sort' );
		$sort_dir = Input::get( 'sort_dir' );
		$query    = Input::get( 'query' );

		if ( $user->is_admin ) {
			//user is admin
			//get all albums in the system
			$bands = Album::search( $query, $sort_by, $sort_dir )
			              ->paginate( $per_page );
		} else {
			//user is not an admin
			//only get this user's albums
			$bands = $user->albums()
			              ->search( $query, $sort_by, $sort_dir )
			              ->paginate( $per_page );
		}

		return response()->json( $bands );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param AlbumRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( AlbumRequest $request ) {
		$band = Band::findOrFail( $request->get( 'band_id' ) );
		$user = Auth::user();

		if ( $user->can( 'update', $band ) ) {
			//User is allowed to update this band
			$album = new Album;

			$album->fill( $request->all() );

			$album->band()->associate( $band );

			$album->save();

			//Save successful, send message back to frontend
			return response()->json( [
				'message' => [
					'type'    => 'success',
					'content' => "You have successfully added the album: $album->name.",
				],
				'created' => $album,
			] );
		} else {

			//Save FAILED, send error message
			return response()->json( [
				'message' => [
					'type'    => 'error',
					'content' => 'You are not allowed to create an album for this band.',
				],
			], 401 );
		}


	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( $id ) {
		$album = Album::findOrFail( $id );
		$user  = Auth::user();

		if ( $user->can( 'view', $album ) ) {

			return response()->json( $album );

		} else {
			//we are in the show method but use the term edit
			//because the user will receive this upon opening
			//the edit form
			return response()->json( [
				'message' => [
					'type'    => 'error',
					'content' => 'You are not allowed to edit this album.',
				],
			], 401 );

		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param AlbumRequest $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( AlbumRequest $request, $id ) {
		$album = Album::findOrFail( $id );
		$band  = Band::findOrFail( $request->get( 'band_id' ) );
		$user  = Auth::user();

		if ( $user->can( 'update', $album ) && $user->can( 'update', $band ) ) {

			$album->fill( $request->all() );

			$album->band()->associate( $band );

			$album->save();

			return response()->json( [
				'message' => [
					'type'    => 'success',
					'content' => "You have successfully updated the album: $album->name.",
				],
				'updated' => $album,
			] );

		} else {

			return response()->json( [
				'message' => [
					'type'    => 'error',
					'content' => 'You are not allowed to update this album.',
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
		$user = Auth::user();

		$album = Album::findOrFail( $id );

		if ( $user->can( 'delete', $album ) ) {

			$album->delete();

			return response()->json( [
				'message' => [
					'type'    => 'success',
					'content' => 'Album deleted.',
				],
			] );

		} else {

			return response()->json( [
				'message' => [
					'type'    => 'error',
					'content' => 'You are not allowed to delete this album.',
				],
			], 401 );

		}
	}
}
