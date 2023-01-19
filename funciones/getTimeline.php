<?php 
include("TwitterAPIExchange.php");
include("llaves.php");
include ("conexion.php");
$datab = "bd_twt";
$db = mysqli_select_db($mysqli,$datab);
$request = json_decode(file_get_contents('php://input'),true);
$userName = $request['USER'];
$decision = $request['DIREC'];
$varrelacion = $request['RELA'];


// $userName = "Koalaa_mirna";
// $userName = "accaballero";
// $userName = "werevertumorro";

////////////////////3 ultimos twwets----- 4 mas populares
// $decision = 3;
// $varrelacion = 28;


function color_rand() {
 return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
 }

function getlastTweets($name,$range){
    include("llaves.php");
    $twitter = new TwitterAPIExchange($settings);
    // $getfield = '?q='.$name.'&result_type=mixed&count=10';
    $getfield = '?q='.$name.'&count='.$range;
    // $getfield = '?q='.$name;
    // $url = 'https://api.twitter.com/1.1/followers/list.json';
    // $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json'; // total de interacciones 
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
    //para extraer los ultimos va sin nada osea lo mas recientes... 
    //para extraer los mas polulares "&result_type=popular'" al name
    //mixed si quiero de ambos ...primuero devuelve los polulares y luego los recientes. 

    $totaltweets = $twitter->setGetfield($getfield) ->buildOauth($url, 'GET') ->performRequest();
    $jsontwets = json_decode($totaltweets); 
     $recents=[];
        foreach ($jsontwets->statuses as $item) 
        {                                            

            // $parse =explode(' ',$item->created_at);  
            // $new_date = $parse[2].' '.$parse[1].' '.$parse[5];
            $parse =strtotime($item->created_at);  
            $new_date = date("Y-m-d h:i", $parse);

            $date = new DateTime($new_date);
            $date->modify('+1 minute');
            // $date->modify('+1 day');
            $date_salida= $date->format('Y-m-d h:i');
            // $datesalida = new DateTime($new_date);
            // $datesalida ->modify("+10 miunute");
            $recents[]=[
                // 'name'=>$name,
                // 'id_tweet' =>  $item->id_str,
                'category' => $item->metadata->result_type,
                'start' => $new_date,
                'end' => $date_salida,
                'color' => color_rand(),
                'text' => $item->text,
                'textDisabled'=> false,
                'Cretweet'=> $item->retweet_count,
                'Cfavorite'=>$item->favorite_count
                // 'tipo'=> $item->metadata->result_type,
                // 'origen'=>$item->source //validar que uba con este
               
            ];
        }

        // echo $cont;
    // return $jsontwets;
    return $recents;
};

function getpopular($name,$range){
    include("llaves.php");
    $twitter = new TwitterAPIExchange($settings);
    $getfield = '?q='.$name.'&result_type=popular&count='.$range; 
    $url = 'https://api.twitter.com/1.1/search/tweets.json';
   
    $totaltweets = $twitter->setGetfield($getfield) ->buildOauth($url, 'GET') ->performRequest();
    $jsontwets = json_decode($totaltweets); 
     $populars=[];
        foreach ($jsontwets->statuses as $item) 
        {  
            // $parse =explode(' ',$item->created_at);  // returns Saturday, January 30 10 02:06:34
            // $new_date = $parse[2].' '.$parse[1].' '.$parse[5];
            $parse =strtotime($item->created_at);  
            $new_date = date("Y-m-d h:i", $parse);
            $date = new DateTime($new_date);
            $date->modify('+1 minute');
            // $date->modify('+1 day');
            $date_salida= $date->format('Y-m-d h:i');
            $populars[]=[
                'category' => $item->metadata->result_type,
                'start' => $new_date,
                'end' => $date_salida,
                'color' => color_rand(),
                'text' => $item->text,
                'textDisabled'=> false,
                'Cretweet'=> $item->retweet_count,
                'Cfavorite'=>$item->favorite_count
                // 'name'=>$name,
                // 'id_tweet' =>  $item->id_str,
                // 'tweet_text' => $item->text,
                // 'fecha_creacion' => $new_date, 
                // 'Cretweet'=> $item->retweet_count,
                // 'tipo'=> $item->metadata->result_type,
                // // 'origen'=>$item->source //validar que uba con este
                // 'Cfavorite'=>$item->favorite_count
            ];
        }

        // echo $cont;
    // return $jsontwets;
    return $populars;
};



if ($decision == 2) {
    // code...
     $datered = date('d M Y');
    //funcion para consultar y devolver el listado ya acmodado de solo que se necesita
    // include("TwitterAPIExchange.php");
    // include("llaves.php");
    $twitter = new TwitterAPIExchange($settings);
    $getfield = '?screen_name='.$userName;
    $url = 'https://api.twitter.com/1.1/users/show.json';
    $profile = $twitter->setGetfield($getfield) ->buildOauth($url, 'GET') ->performRequest();
    $json = json_decode($profile); 
    // $id_str = $json->id_str;
    // $id_sujeto_b = $json->id_str;
    $nombre_b= $json->screen_name;
                        // 'tipo_relacion'=> 0,
    $location = $json->location;
    $protected = $json->protected;
    $followers_count = $json->followers_count;
    $friends_count = $json->friends_count;    
    $url_img = $json->profile_image_url_https;
    $created_at = $json->created_at;
    $statuses_count = $json->statuses_count;
    $endtweet = ($json->protected === false && $json->statuses_count > 0)?$json->status->text:'0';
    $endtweetdate = ($json->protected === false && $json->statuses_count > 0)?$json->status->created_at:'000 000 00 00000000 00000 0000';
    $retweet_count = ($json->protected === false && $json->statuses_count > 0)?$json->status->retweet_count:'0';
    $favorite_count = ($json->protected === false && $json->statuses_count > 0 )?$json->status->favorite_count:'0';
    $daterecord = $datered;
    $usuario[]=[
                    'id_sujeto_b'=> $json->id_str,
                    'nombre_b'=> $json->screen_name,
                        // 'tipo_relacion'=> 0,
                    'location'=> $json->location,
                    'protected'=> $json->protected,
                    'followers_count'=> $json->followers_count,
                    'friends_count'=> $json->friends_count,        
                    'url_img'=> $json->profile_image_url_https,
                    'created_at'=> $json->created_at,
                    'statuses_count'=> $json->statuses_count,
                    'endtweet'=> ($json->protected === false && $json->statuses_count > 0)?$json->status->text:'0',
                    'endtweetdate'=> ($json->protected === false && $json->statuses_count > 0)?$json->status->created_at:'000 000 00 00000000 00000 0000',
                    'retweet_count'=> ($json->protected === false && $json->statuses_count > 0)?$json->status->retweet_count:'0',
                    'favorite_count'=> ($json->protected === false && $json->statuses_count > 0 )?$json->status->favorite_count:'0',
                    'daterecord'=> $datered
                ];
    //////////////////////////////////
            $sqla = "UPDATE `tb_comp`
             SET  `location` ='$location',  `followers_count` = '$followers_count', `friends_count` ='$friends_count', `url_img` = '$url_img', `statuses_count` = '$statuses_count', `endtweet` = '$endtweet', `endtweetdate` = '$endtweetdate',  `retweet_count` = '$retweet_count', `favorite_count` = '$favorite_count', `daterecord` = '$daterecord'
             WHERE `nombre_b` = '$userName' ";

             // mysqli_query($mysqli,$sqla);

              // $sqla =  'SELECT * FROM `tb_comp` WHERE `nombre_b` = "$userName"';
// echo mysqli_query($mysqli,$sqla);

    if (mysqli_query($mysqli,$sqla)) 
     {
            $query = "SELECT * FROM tb_comp WHERE nombre_b = '$userName'";
            if ($result = $mysqli->query($query)){
                $arreglofin=[];
                foreach ($result as $key) {
                    $quitar2 = "_normal";
                    $temp2 = str_replace($quitar2,"",$key["url_img"]);
                    $parse =explode(' ', $key["created_at"]);  // returns Saturday, January 30 10 02:06:34
                    $new_date = $parse[2].' '.$parse[1].' '.$parse[5];
                    $parse2 =explode(' ', $key["endtweetdate"]);  // returns Saturday, January 30 10 02:06:34
                    $new_date2 = $parse2[2].' '.$parse2[1].' '.$parse2[5];
                    $formodal = [
                            [   ////
                                'category'=>'Retwets', 
                                'value'=>$key["retweet_count"]
                            ],////
                            [   ///
                                'category'=>'Likes', 
                                'value'=>$key["favorite_count"]
                            ]///
                    ];
                    $arreglofin=[
                       'name'=> $userName,
                       'value' => $key["followers_count"],
                       'friends' => $key["friends_count"],
                       'image'=> $temp2,
                       'location'=> $key["location"],
                        'protected'=> ($key["protected"] == 1)?'Protegido':'Publico',
                        'created_at'=> $key["created_at"],
                        'FECHA'=> $new_date,
                        'statuses_count'=> $key["statuses_count"],
                        'twt_created_at'=>  $new_date2,
                        'endtweet'=>   $key["endtweet"],
                        'rt' => $key["retweet_count"],
                        'like' => $key["favorite_count"],
                        'daterecord'=>   $key["daterecord"],
                        'relacion'=>  $varrelacion,
                        'resmodal'=> $formodal

                    ];
                }
            }

    $response = [
        'status'=>'OK',
        'message'=>'registro actualizado....',
        'update'=> $arreglofin
    ];  
     }else{  

                $response = [
                    'status'=>'error',
                    'message'=>'Ocurrio un error....'
                ];
     }
    echo json_encode($response); 
}elseif($decision == 3){
                $data = getlastTweets($userName,$varrelacion);
                // 1 para vacio
                // 0 par no vacio
                if (empty($data) == 1) {
                    $response = [
                                'status'=>'Empty',
                                'message'=>'sin contenido...'
                            ];
                            echo json_encode($response);
                }else{
                    $response = [
                                'status'=>'OK_last',
                                'message'=>'encontrado...',
                                'datalast' => $data
                            ];
                            echo json_encode($response);
                }
}elseif($decision == 4){
                $data = getpopular($userName,$varrelacion);
                // 1 para vacio
                // 0 par no vacio
                if (empty($data) == 1) {
                    $response = [
                                'status'=>'Empty',
                                'message'=>'sin contenido...'
                            ];
                            echo json_encode($response);
                }else{
                    $response = [
                                'status'=>'OK_populares',
                                'message'=>'encontrado...',
                                'datapopular' => $data
                            ];
                            echo json_encode($response);
                }
}else{
                $response = [
                                'status'=>'error',
                                'message'=>'Ocurrio algun error...',
                            ];
                            echo json_encode($response);
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////

// function update($table, $data, $conditions)
//     {
//         if (!empty($data) && is_array($data)) {
//             $colSet = '';
//             $where = '';
//             $i = 0;
//             foreach ($data as $key => $val) {
//                 $pre = ($i > 0) ? ', ' : '';
//                 $colSet .= $pre . $key . "='" . $val . "'";
//                 $i++;
//             }
//             if (!empty($conditions) && is_array($conditions)) {
//                 $where .= ' WHERE ';
//                 $i = 0;
//                 foreach ($conditions as $key => $value) {
//                     $pre = ($i > 0) ? ' AND ' : '';
//                     $where .= $pre . $key . " = '" . $value . "'";
//                     $i++;
//                 }
//             }
//             $query = "UPDATE " . $table . " SET " . $colSet . $where;
//             return $query;
//         } else {
//             return false;
//         }
//     }
////////////////////////////////////////////////////////////////////////////////////////////////////7


// {
//         "created_at": "Mon Oct 03 06:37:46 +0000 2022",
//         "id": 1576823807066910722,
//         "id_str": "1576823807066910722",
//         "text": "Cu\u00eddense mucho. Las cosas en el mundo est\u00e1n bien mal. Los quiero y que tengan un cierre de a\u00f1o incre\u00edble. Les mando\u2026 https:\/\/t.co\/2mdfVm40ry",
//         "truncated": true,
//         "entities": {
//             "hashtags": [],
//             "symbols": [],
//             "user_mentions": [],
//             "urls": [{
//                 "url": "https:\/\/t.co\/2mdfVm40ry",
//                 "expanded_url": "https:\/\/twitter.com\/i\/web\/status\/1576823807066910722",
//                 "display_url": "twitter.com\/i\/web\/status\/1\u2026",
//                 "indices": [117, 140]
//             }]
//         },
//         "metadata": {
//             "result_type": "popular",
//             "iso_language_code": "es"
//         },
//         "source": "Twitter for iPhone<\/a>",
//         "in_reply_to_status_id": null,
//         "in_reply_to_status_id_str": null,
//         "in_reply_to_user_id": null,
//         "in_reply_to_user_id_str": null,
//         "in_reply_to_screen_name": null,
//         "user": {
//             "id": 37718134,
//             "id_str": "37718134",
//             "name": "GABO MONTIEL",
//             "screen_name": "werevertumorro",
//             "location": "M\u00e9xico, DF",
//             "description": "PODCAST: https:\/\/t.co\/OZBB2zmssZ PICKS GRATIS: https:\/\/t.co\/MH5VQeHuB2 contactowerever@gmail.com",
//             "url": null,
//             "entities": {
//                 "description": {
//                     "urls": [{
//                         "url": "https:\/\/t.co\/OZBB2zmssZ",
//                         "expanded_url": "http:\/\/shorturl.at\/gorvL",
//                         "display_url": "shorturl.at\/gorvL",
//                         "indices": [9, 32]
//                     }, {
//                         "url": "https:\/\/t.co\/MH5VQeHuB2",
//                         "expanded_url": "http:\/\/t.me\/momiazos",
//                         "display_url": "t.me\/momiazos",
//                         "indices": [47, 70]
//                     }]
//                 }
//             },
//             "protected": false,
//             "followers_count": 8766934,
//             "friends_count": 4870,
//             "listed_count": 7388,
//             "created_at": "Mon May 04 18:16:48 +0000 2009",
//             "favourites_count": 56512,
//             "utc_offset": null,
//             "time_zone": null,
//             "geo_enabled": true,
//             "verified": true,
//             "statuses_count": 121872,
//             "lang": null,
//             "contributors_enabled": false,
//             "is_translator": false,
//             "is_translation_enabled": true,
//             "profile_background_color": "B2DFDA",
//             "profile_background_image_url": "http:\/\/abs.twimg.com\/images\/themes\/theme13\/bg.gif",
//             "profile_background_image_url_https": "https:\/\/abs.twimg.com\/images\/themes\/theme13\/bg.gif",
//             "profile_background_tile": false,
//             "profile_image_url": "http:\/\/pbs.twimg.com\/profile_images\/1571301933826064385\/ePDEUb-d_normal.jpg",
//             "profile_image_url_https": "https:\/\/pbs.twimg.com\/profile_images\/1571301933826064385\/ePDEUb-d_normal.jpg",
//             "profile_banner_url": "https:\/\/pbs.twimg.com\/profile_banners\/37718134\/1646178532",
//             "profile_link_color": "93A644",
//             "profile_sidebar_border_color": "EEEEEE",
//             "profile_sidebar_fill_color": "FFFFFF",
//             "profile_text_color": "333333",
//             "profile_use_background_image": true,
//             "has_extended_profile": true,
//             "default_profile": false,
//             "default_profile_image": false,
//             "following": false,
//             "follow_request_sent": false,
//             "notifications": false,
//             "translator_type": "regular",
//             "withheld_in_countries": []
//         },
//         "geo": null,
//         "coordinates": null,
//         "place": null,
//         "contributors": null,
//         "is_quote_status": false,
//         "retweet_count": 214,
//         "favorite_count": 6753,
//         "favorited": false,
//         "retweeted": false,
//         "lang": "es"
//     },
?>