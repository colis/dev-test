<?php
require_once '../vendor/autoload.php';

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ServerException;

//Load Twig templating environment
$loader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($loader, ['debug' => true]);

//Declare a cache file.
$api_cache = 'api_cache.json';

//Declare an empty array to store Guzzle exceptions.
$errors = [];
//Get the episodes from the API
try {
    //Call the API if the file cache doesn't exist or if the file exists but is older than 2 hours.
    if ( ! is_file( $api_cache ) || ( is_file( $api_cache ) && time() - filemtime( $api_cache ) > 2 * 3600 ) ) {
        $client = new GuzzleHttp\Client();
        $res = $client->request('GET', 'http://3ev.org/dev-test-api/');
        $res_body = $res->getBody();

        //Cache the API result in a file.
        file_put_contents( $api_cache, $res_body );
    } else {
        $res_body = file_get_contents( $api_cache );
    }

    $data = json_decode($res_body, true);

    //Sort the episodes
    array_multisort(array_keys($data), SORT_ASC, SORT_STRING, $data);
} catch ( ServerException $e ) {
    $errors[] = $e->getMessage();
}
//Render the template
echo $twig->render('page.html', ["episodes" => $data, "errors" => $errors]);
