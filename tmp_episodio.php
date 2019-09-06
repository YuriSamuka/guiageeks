<?php
/**
 * Created by PhpStorm.
 * User: Yuri
 * Date: 03/02/2018
 * Time: 06:33
 */
set_time_limit(86400);
function puxarEpisodiosAnime($idAnime){
    $episodios = json_decode(file_get_contents('https://kitsu.io/api/edge/anime/' . $idAnime . '/relationships/episodes'));
    $episodios = $episodios->data;
    $i = 0;
    foreach ($episodios as $episodio){
        $episodio = json_decode(file_get_contents('https://kitsu.io/api/edge/episodes/' . $episodio->id));
        if ($episodio->data){
            $i++;
        }
    }
    $anime =  json_decode(file_get_contents("https://kitsu.io/api/edge/anime/" . $idAnime));
    echo $anime->data->attributes->episodeCount . " ==  " . $i . "<br>";
    if (isset($anime->data->attributes) && $anime->data->attributes->episodeCount != $i){
//        die();
        echo "aquiiii!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>";
    }
}

for ($i = 1; $i<100; $i++){
    puxarEpisodiosAnime($i);
}