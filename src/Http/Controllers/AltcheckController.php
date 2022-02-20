<?php

namespace Fubbleskag\Seat\Altcheck\Http\Controllers;

use Seat\Web\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Character\CharacterAffiliation;
use Seat\Eveapi\Models\Corporation\CorporationInfo;

class AltcheckController extends Controller
{
	public function getAltcheckView()
	{
		$corps = CorporationInfo::all();
		$my_character_id = auth()->user()->main_character_id;
		$search = DB::select("
			SELECT corporation_id
			FROM character_affiliations
			WHERE character_id = $my_character_id
		");
		$my_corporation_id = $search[0]->corporation_id;
		return view('altcheck::altcheck', compact("corps","my_corporation_id"));
	}

	public function getAltcheckReport(Request $request)
	{
		$altsin = explode( "\n", $request->input('altlist') ); // make an array of the names submitted
		$sqlin = '"' . str_replace( "\n", '","', $request->input('altlist') ) . '"'; // make an SQL IN formatted list of the names submitted (TODO: find a way to do this using DB)
		$results = []; // results of the DB search indexed by name submitted
		$return = []; // merged results to send to the browser

		$search = DB::select("
			SELECT chars.name as 'character', chars.character_id as 'characterId', users.name as 'main', users.main_character_id as 'mainCharacterId', affiliations.corporation_id as 'mainCorpId'
			FROM users, refresh_tokens tokens, character_affiliations affiliations, character_infos chars
			WHERE users.main_character_id > 0
			AND users.id = tokens.user_id
			AND affiliations.character_id = users.main_character_id
			AND tokens.character_id = chars.character_id
			AND chars.name IN ( {$sqlin} )
			ORDER BY users.id ASC
		"); // get alt/main affiliations for names submitted

		foreach ( $search as $result ) {
			$results[ $result->character ] = $result; // index search reuslts by name submitted
		}

		foreach ( $altsin as $alt ) {
			if ( array_key_exists( $alt, $results ) ) {
				$return[ $alt ] = $results[ $alt ];
			} else {
				$return[ $alt ] = (object)[];
			}
		}

		return response()->json( $return );
	}
}
