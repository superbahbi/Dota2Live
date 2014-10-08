<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Dota2League</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="./assets/css/bootstrap.css" rel="stylesheet">
    <link href="./assets/css/dota2minimapheroes.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>

    <link href="./assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
<?php
		include './src/api.php';
		$api = new api();
		$steam_api_key = '';
		$dota_webapi_listing ='GetLiveLeagueGames';

		$api->url = 'http://api.steampowered.com/IDOTA2Match_570/'.$dota_webapi_listing.'/v1/?key='.$steam_api_key;

		try {
		$s = $api->get_api_data();
		} catch (Exception  $e ) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
		} 
		
		$dota_webapi_listing ='GetLeagueListing';
		$api->url = 'http://api.steampowered.com/IDOTA2Match_570/'.$dota_webapi_listing.'/v1/?key='.$steam_api_key;
		try {
		$league = $api->get_api_data();
		} catch (Exception  $e ) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
		} 
		
		$dota_webapi_listing = 'GetHeroes';
		$api->url = 'http://api.steampowered.com/IEconDOTA2_570/'.$dota_webapi_listing.'/v1/?key='.$steam_api_key;
		try {
		$heroes = $api->get_api_data();
		} catch (Exception  $e ) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
		} 

		$dota_webapi_listing = 'GetGameItems';
		$api->url = 'http://api.steampowered.com/IEconDOTA2_570/'.$dota_webapi_listing.'/v0001/?key='.$steam_api_key;
		try {
		$item = $api->get_api_data();
		} catch (Exception  $e ) {
				echo 'Caught exception: ',  $e->getMessage(), "\n";
		} 

		if($_GET["leagueid"]) {
			$leagueid = ($_GET["leagueid"]);
		}
?>
  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Dota2Live</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="#">Action</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>
            </ul>
            <form class="navbar-form pull-right" action="" method="get">
              <input class="span2" type="text" placeholder="Enter league id"  name="leagueid">
              <button type="submit" class="btn">Find league</button>
            </form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row">
					<?php 
						foreach ( $s->result->games as $livegame ) {
						if($livegame->league_id == $leagueid) {
						$live = 1;
						echo '<div class="well">';
				
							echo '<p> <a href="http://dotabuff.com/esports/teams/' .$livegame->radiant_team->team_id. '">' . $livegame->radiant_team->team_name . ' </a> vs <a href="http://dotabuff.com/esports/teams/' .$livegame->dire_team->team_id. '">' . $livegame->dire_team->team_name . '</a></p>';
							echo '<p> Match ID : '. $livegame->match_id .'</p>';
							echo '<p> Spectators : '. $livegame->spectators .'</p>';
							foreach ( $league->result->leagues as $l ) {
								if( $l->leagueid == $livegame->league_id ) {
									echo '<p> League : '. str_replace('_', ' ', substr($l->name, 11)) .'</p>';
								}
							}
							switch($livegame->league_tier) {
							case 1:
								echo '<p> League Tier: Amateur</p>';
								break;
							case 2:
								echo '<p> League Tier: Professional</p>';
								break;
							case 3:
								echo '<p> League Tier: Premium</p>';
								break;
							default:
								echo '<p> League Tier: Unknown</p>';
							}
							echo '<p> Series : ' . $livegame->series_type .'</p>';
							
							if( $livegame->scoreboard->duration > 0 ) {
							echo '<p> Duration : ' . date("H:i:s", mktime(0,0, round($livegame->scoreboard->duration) % (24*3600))) .'</p>';
							echo '<p> Score : ' . $livegame->scoreboard->radiant->score .' - ' . $livegame->scoreboard->dire->score . '</p>';
							echo '<p>RADIANT</p>';
								echo '<table class="table table-bordered radiant">';
									echo '<thead>';
										echo '<tr>';
											echo '<th>Hero</th>';
											echo '<th>Player</th>';
											echo '<th>Level</th>';
											echo '<th>K</th>';
											echo '<th>D</th>';
											echo '<th>A</th>';
											echo '<th>Gold (Net Worth)</th>';
											echo '<th>LH</th>';
											echo '<th>DN</th>';
											echo '<th>XPM</th>';
											echo '<th>GPM</th>';
											echo '<th>Ultimate</th>';
											echo '<th>Respawn</th>';
											echo '<th>Items</th>';
										echo '</tr>';
									echo '</thead>';
									echo '<tbody>';
									foreach($livegame->scoreboard->radiant->players as $d) {
										echo '<tr>';
											#hero
											foreach ( $heroes->result->heroes as $hero ) {
												if ( $d->hero_id == $hero->id ) {
													echo '<td><i class="d2mh '.$hero->name.'"></i>' . ucfirst(str_replace('_', ' ', substr($hero->name, 14))) .'</td>';
												}
						
											}
											#player
											foreach ( $livegame->players as $p ) {
												if ( $d->account_id == $p->account_id ) {
													echo '<td>'. $p->name .'</td>';
												}
											}
											
											echo '<td>'. $d->level .'</td>';
											echo '<td>'. $d->kills .'</td>';
											echo '<td>'. $d->death .'</td>';
											echo '<td>'. $d->assists .'</td>';
											echo '<td><img src="./assets/images/gold.png">'. $d->gold .' ('.$d->net_worth.')</td>';
											echo '<td>'. $d->last_hits .'</td>';
											echo '<td>'. $d->denies .'</td>';
											echo '<td>'. $d->xp_per_min .'</td>';
											echo '<td>'. $d->gold_per_min .'</td>';
											# state 3 = ready 2 = mana 1 = cooldown 0 = n/a
											if ( $d->ultimate_state == 3 ) {
												echo '<td><img src="./assets/images/ultimate3.png"> Ready</td>';
											} else if ( $d->ultimate_state == 2) {
												echo '<td><img src="./assets/images/ultimate2.png"> Mana</td>';
											} else if ( $d->ultimate_state == 1 ) {
												echo '<td><img src="./assets/images/ultimate1.png"> '.$d->ultimate_cooldown.'</td>';
											} else {
												echo '<td><img src="./assets/images/ultimate0.png"> N/A</td>';
											}
											
											echo '<td>'. $d->respawn_timer .'</td>';
											#item
											echo '<td>';
											foreach ( $item->result->items as $i ) {
												
												if ( $i->id == $d->item0 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												if ( $i->id == $d->item1 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												if ( $i->id == $d->item2 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												if ( $i->id == $d->item3 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												if ( $i->id == $d->item4 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												if ( $i->id == $d->item5 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												
											}
											echo '</td>';
										echo '</tr>';
									}
									echo '</tbody>';
								echo '</table>';
							echo '<p>DIRE</p>';
								echo '<table class="table table-bordered dire">';
									echo '<thead>';
										echo '<tr >';
											echo '<th>Hero</th>';
											echo '<th>Player</th>';
											echo '<th>Level</th>';
											echo '<th>K</th>';
											echo '<th>D</th>';
											echo '<th>A</th>';
											echo '<th>Gold (Net Worth)</th>';
											echo '<th>LH</th>';
											echo '<th>DN</th>';
											echo '<th>XPM</th>';
											echo '<th>GPM</th>';
											echo '<th>Ultimate</th>';
											echo '<th>Respawn</th>';
											echo '<th>Items</th>';
										echo '</tr>';
									echo '</thead>';
									echo '<tbody>';
									foreach($livegame->scoreboard->dire->players as $d) {
										echo '<tr>';
											#hero
											foreach ( $heroes->result->heroes as $hero ) {
												if ( $d->hero_id == $hero->id ) {
													echo '<td><i class="d2mh '.$hero->name.'"></i>' . ucfirst(str_replace('_', ' ', substr($hero->name, 14))) .'</td>';
												}
						
											}
											#player
											foreach ( $livegame->players as $p ) {
												if ( $d->account_id == $p->account_id ) {
													echo '<td>'. $p->name .'</td>';
												}
											}
											
											echo '<td>'. $d->level .'</td>';
											echo '<td>'. $d->kills .'</td>';
											echo '<td>'. $d->death .'</td>';
											echo '<td>'. $d->assists .'</td>';
											echo '<td><img src="./assets/images/gold.png">'. $d->gold .' ('.$d->net_worth.')</td>';
											echo '<td>'. $d->last_hits .'</td>';
											echo '<td>'. $d->denies .'</td>';
											echo '<td>'. $d->xp_per_min .'</td>';
											echo '<td>'. $d->gold_per_min .'</td>';
											# state 3 = ready 2 = mana 1 = cooldown 0 = n/a
											if ( $d->ultimate_state == 3 ) {
												echo '<td><img src="./assets/images/ultimate3.png"> Ready</td>';
											} else if ( $d->ultimate_state == 2) {
												echo '<td><img src="./assets/images/ultimate2.png"> Mana</td>';
											} else if ( $d->ultimate_state == 1 ) {
												echo '<td><img src="./assets/images/ultimate1.png"> '.$d->ultimate_cooldown.'</td>';
											} else {
												echo '<td><img src="./assets/images/ultimate0.png"> N/A</td>';
											}
											
											echo '<td>'. $d->respawn_timer .'</td>';
											#item
											echo '<td>';
											foreach ( $item->result->items as $i ) {
												
												if ( $i->id == $d->item0 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												if ( $i->id == $d->item1 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												if ( $i->id == $d->item2 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												if ( $i->id == $d->item3 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												if ( $i->id == $d->item4 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												if ( $i->id == $d->item5 ) {
													echo '<a href="#" rel="tooltip" title="" data-original-title="' .substr($i->name, 5) . '"><img style="height:24px" src="http://media.steampowered.com/apps/dota2/images/items/' .substr($i->name, 5) . '_eg.png"></a>';
												}
												
											}
											echo '</td>';
										echo '</tr>';
									}
									echo '</tbody>';
								echo '</table>';
							} else {
								echo '<p>THIS GAME WILL START SOON.</p>';
							}
							
						echo '</div>';
						}
						}
						if ( $live == 0 ) {
							if($livegame->league_id == $leagueid) { # && $livegame->league_id != 0
								foreach ( $s->result->games as $livegame ) {
									foreach ( $league->result->leagues as $l ) {
										if( $l->leagueid == $livegame->league_id ) {
											if ($temp == $livegame->league_id ) {
												continue;
											}
											echo '<p><a href="'.$l->tournament_url.'">'. str_replace('_', ' ', substr($l->name, 11)) .'</a><a href="?leagueid='.$l->leagueid.'">Watch</a></p>';
											echo '<br>';
										}
									}
									$temp = $livegame->league_id;
								}
							} else {
							echo '<h3>Error: No league games are live right now. Please try again later.</h3>';
							}
						}
					?>
      </div>

      <hr>
    </div> <!-- /container -->
	
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./assets/js/jquery.js"></script>
    <script src="./assets/js/bootstrap-transition.js"></script>
    <script src="./assets/js/bootstrap-alert.js"></script>
    <script src="./assets/js/bootstrap-modal.js"></script>
    <script src="./assets/js/bootstrap-dropdown.js"></script>
    <script src="./assets/js/bootstrap-scrollspy.js"></script>
    <script src="./assets/js/bootstrap-tab.js"></script>
    <script src="./assets/js/bootstrap-tooltip.js"></script>
    <script src="./assets/js/bootstrap-popover.js"></script>
    <script src="./assets/js/bootstrap-button.js"></script>
    <script src="./assets/js/bootstrap-collapse.js"></script>
    <script src="./assets/js/bootstrap-carousel.js"></script>
    <script src="./assets/js/bootstrap-typeahead.js"></script>
	

  </body>
</html>
