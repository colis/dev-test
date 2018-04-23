<?php
require_once '../vendor/autoload.php';

//Load Twig templating environment
$loader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($loader, ['debug' => true]);

//Get the episodes from the API
$client = new GuzzleHttp\Client();
$res = $client->request('GET', 'http://3ev.org/dev-test-api/');
$data = json_decode($res->getBody(), true);

$available_seasons = [];
$season_episodes = [];

// Parse the result data and build the seasons episode array.
foreach ( $data as $episode ) {
    $available_seasons[] = $episode['season'];

    if ( isset( $_GET['season'] ) && $_GET['season'] != 0 ) {
        if ( $episode['season'] == $_GET['season'] ) {
            $season_episodes[] = $episode;
        }
    } else {
        $season_episodes[] = $episode;
    }
}
$data = $season_episodes;

$available_seasons = array_unique( $available_seasons );
asort( $available_seasons );

//Sort the episodes
array_multisort(array_keys($data), SORT_ASC, SORT_STRING, $data);

//Render the template
echo $twig->render('page.html', ["episodes" => $data, "seasons" => $available_seasons]);
