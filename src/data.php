

<?php
class data {
	var $players;
	var $radiant_team;
	var $dire_team;
	var $lobby_id;
	var $match_id;
	var $spectator;
	var $tower_state;
	var $league_id;
	var $stream_delay_s;
	var $radiant_series_wins;
	var $dire_series_wins;
	var $series_type;
	var $league_tier;
	var $scoreboard;
	
	public function get_lobby_id() {
		return $lobby_id;
    }

};
?>