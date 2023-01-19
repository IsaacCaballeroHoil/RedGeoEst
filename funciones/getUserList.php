<?php 
// <------------------------------conexion a base de datos definiendo campos------------------------------------------>
error_reporting (0);
include ("conexion.php");

$datab = "bd_twt";
$db = mysqli_select_db($mysqli,$datab);
//////////////////////////////////////////////////////////////////////
    //borrar tabla
    //delete from tb_comp

    //reiniciar tabla id
    //ALTER TABLE tb_comp DROP id
///////////////////////////////////////////////////////////////////////////
// recibir la variable pro medio de axios
$request = json_decode(file_get_contents('php://input'),true);
// recibir la variable y preparar la consulta para verificar si existe o no
$userName = $request['USER'];
// $userName = "oskrsasi";
// $userName = "ToryMontalvo13";
// $userName = "plablo09";
// $userName = "accaballero";
// $userName = "kerrigan21";
// $userName = "gandhi1976";
// $userName = "elenabuylla";




$query = "SELECT * FROM tb_comp WHERE nombre_a = '$userName' ";

//////////////////
// $data = [];
if ($result = $mysqli->query($query)){
    $res = mysqli_num_rows($result);
    
}
///////////////////
if($res > 0) {
    // generar arreglo para js
   // $data5 = fulllist($userName);
   $data = sendPack($userName);
            $response = [
                'status'=>'OK',
                'message'=>'encontrado...',
                // 'data5' => $data5,
                'data' => $data
            ];
            //devolviendo la repuesta y lo pueda leer sin recargar la pagina
    echo json_encode($response);
       
} 
else {
    $consu = "SELECT * FROM tb_comp WHERE nombre_b = '$userName'";
        $protect = "";

        if ($conte2 = $mysqli->query($consu)){
            foreach ($conte2 as $key) {
                    $protect= $key["protected"];
                    } 
        }

        if ($protect != 1) {
            # code...
            // echo 'sed puede ver '.$protect;
           // en el caso que sea falso llamos a las funciones y vaciamos dentro de dataUsers
                        $dataUsers = getUser($userName);

                        //recorrido para insertar despues de acomodar la insersion
                        foreach ($dataUsers['seguidores'] as $seguidor) {
                            $query = prepare_insert('tb_comp',$seguidor);
                            $mysqli->query($query);
                        }
                        //recorrido para insertar despues de acomodar la insersion
                        foreach ($dataUsers['seguidos'] as $sigo) {
                            $query = prepare_insert('tb_comp',$sigo);
                            $mysqli->query($query);
                        }      

                        //una vez generada los registros mandamos los arreglos de BD
                        $data = sendPack($userName);
                        $response = [
                            'status'=>'OK',
                            'message'=>'Registros agregados...',
                            'list' => $dataUsers,
                            'data' => $data
                        ];
                        echo json_encode($response); //contieen la respuesta que recibe por axios
        } else {
            # code...
            // echo 'NO se puede ver '.$protect;
           $response = [
                'status'=>'Private',
                'message'=>'Cuenta privada.'
            ];
            //devolviendo la repuesta y lo pueda leer sin recargar la pagina
            echo json_encode($response);
        }
    
}


///////////////////////////////////////METODOS//////////////////////////////
function getUser($name){
   $datered = date('d M Y');
    //funcion para consultar y devolver el listado ya acmodado de solo que se necesita
    include("TwitterAPIExchange.php");
    include("llaves.php");
    $twitter = new TwitterAPIExchange($settings);
    $getfield = '?screen_name='.$name.'&count=200';
//  <------------------me siguen-------------------------->
    $url = 'https://api.twitter.com/1.1/followers/list.json';
    $seguidoresJSON = $twitter->setGetfield($getfield) ->buildOauth($url, 'GET') ->performRequest();
    $json = json_decode($seguidoresJSON); 
    $seguidores=[];
    $paginacion = $json->next_cursor; 
    // echo $paginacion ;
    $cont=0;
    do {
        if ($paginacion ==0) {
                foreach ($json->users as $item) {
                   // $item->protected === false ||
                    $validador = "";
                    $validador = is_null($item->status);
                    $seguidores[]=[
                        'id_sujeto_b'=> $item->id_str,
                        'nombre_b'=> $item->screen_name,
                        'nombre_a'=> $name,
                        'tipo_relacion'=> 0,
                        'location'=> $item->location,
                        'protected'=> $item->protected,
                        'followers_count'=> $item->followers_count,
                        'friends_count'=> $item->friends_count,        
                        'url_img'=> $item->profile_image_url_https,
                        // 'url_img'=> 'https://unavatar.io/twitter/'.$item->screen_name,
                        'created_at'=> $item->created_at,
                        'statuses_count'=> $item->statuses_count,
'endtweet'=> ($validador === false && $item->protected === false && $item->statuses_count > 0)?$item->status->text:'0',
'endtweetdate'=> ($validador === false && $item->protected === false && $item->statuses_count > 0)?$item->status->created_at:'000 000 00 00000000 00000 0000',
'retweet_count'=> ($validador === false && $item->protected === false && $item->statuses_count > 0)?$item->status->retweet_count:'0',
'favorite_count'=> ($validador === false && $item->protected === false && $item->statuses_count > 0 )?$item->status->favorite_count:'0',
                        'daterecord'=> $datered
                        ];
                        
        }
          break;
        } else {
            $var4 = '&cursor='.$paginacion;
            $getfield2=$getfield.$var4;
            $seguidoresJSON = $twitter->setGetfield($getfield2) ->buildOauth($url, 'GET') ->performRequest();
            $json3 = json_decode($seguidoresJSON);
            $paginacion = $json3->next_cursor; 
                foreach ($json3->users as $item) {
                $validador3 = "";
                $validador3 = is_null($item->status);
                    $seguidores[]=[
                        'id_sujeto_b'=> $item->id_str,
                        'nombre_b'=> $item->screen_name,
                        'nombre_a'=> $name,
                        'tipo_relacion'=> 0,
                        'location'=> $item->location,
                        'protected'=> $item->protected,
                        'followers_count'=> $item->followers_count,
                        'friends_count'=> $item->friends_count,        
                        'url_img'=> $item->profile_image_url_https,
                        // 'url_img'=> 'https://unavatar.io/twitter/'.$item->screen_name,
                        'created_at'=> $item->created_at,
                        'statuses_count'=> $item->statuses_count,
'endtweet'=> ($validador3 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->text:'0',
'endtweetdate'=> ($validador3 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->created_at:'000 000 00 00000000 00000 0000',
'retweet_count'=> ($validador3 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->retweet_count:'0',
'favorite_count'=> ($validador3 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->favorite_count:'0',
                        'daterecord'=> $datered 
                                ];
                                 
                        } 
            }   
  $cont++;
} while($cont <= 2);
//  <------------------los sigo-------------------------->
    $twitter2 = new TwitterAPIExchange($settings2);
    $url2 = 'https://api.twitter.com/1.1/friends/list.json';
    $seguidosJSON = $twitter2->setGetfield($getfield) ->buildOauth($url2, 'GET') ->performRequest(); 
    $json2 = json_decode($seguidosJSON); 
    $paginacion2 = $json2->next_cursor; 
    // convertir el json crudo a un json con solo que se necesitara
    $seguidos=[];
    $cont2 = 0;
    do {
            if ($paginacion2 ==0) {
                # code...
                    foreach ($json2->users as $item) {
                    $validador2 = "";
                    $validador2 = is_null($item->status);
                        $seguidos[]=[
                        'id_sujeto_b'=> $item->id_str,
                        'nombre_b'=> $item->screen_name,
                        'nombre_a'=> $name,
                        'tipo_relacion'=> 1,
                        'location'=> $item->location,
                        'protected'=> $item->protected,
                        'followers_count'=> $item->followers_count,
                        'friends_count'=> $item->friends_count,        
                        'url_img'=> $item->profile_image_url_https,
                        // 'url_img'=> 'https://unavatar.io/twitter/'.$item->screen_name,
                        'created_at'=> $item->created_at,
                        'statuses_count'=> $item->statuses_count,
    'endtweet'=> ($validador2 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->text:'0',
    'endtweetdate'=> ($validador2 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->created_at:'000 000 00 00000000 00000 0000',
    'retweet_count'=> ($validador2 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->retweet_count:'0',
    'favorite_count'=> ($validador2 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->favorite_count:'0',
    'daterecord'=> $datered 
                        ];
                        
                    }
                break;
            } else {
                # code...
        $var5 = '&cursor='.$paginacion2;
       $getfield3=$getfield.$var5;
        $seguidosJSON = $twitter2->setGetfield($getfield3) ->buildOauth($url2, 'GET') ->performRequest(); 
        $json4 = json_decode($seguidosJSON); 
        $paginacion2 = $json4->next_cursor;
            foreach ($json4->users as $item) {
                $validador5 = "";
                $validador5 = is_null($item->status);
                    $seguidos[]=[
                        'id_sujeto_b'=> $item->id_str,
                        'nombre_b'=> $item->screen_name,
                        'nombre_a'=> $name,
                        'tipo_relacion'=> 1,
                        'location'=> $item->location,
                        'protected'=> $item->protected,
                        'followers_count'=> $item->followers_count,
                        'friends_count'=> $item->friends_count,        
                        'url_img'=> $item->profile_image_url_https,
                        // 'url_img'=> 'https://unavatar.io/twitter/'.$item->screen_name,
                        'created_at'=> $item->created_at,
                        'statuses_count'=> $item->statuses_count,
    'endtweet'=> ($validador5 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->text:'0',
    'endtweetdate'=> ($validador5 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->created_at:'000 000 00 00000000 00000 0000',
    'retweet_count'=> ($validador5 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->retweet_count:'0',
    'favorite_count'=> ($validador5 === false && $item->protected === false && $item->statuses_count > 0)?$item->status->favorite_count:'0',
                        'daterecord'=> $datered 
                            ];
                        }
            }
         $cont2++;
    } while ($cont2 <= 2);

    //mensaje de respuesta mandando los arreglos
    $response = [
        'seguidores'=> $seguidores,
        'seguidos'=> $seguidos
    ];
    return $response;
}

function prepare_insert($table, $data) {
    if (!empty($data) && is_array($data)) {
        $colSet = '';
        $colVal = '';
        $i = 0;
        foreach ($data as $key => $val) {
            $pre = ($i > 0) ? ', ' : '';
            $colSet .= $pre . $key;
            $colVal .= $pre . "'{$val}'";
            $i++;
        }
        $query = "INSERT INTO {$table} ({$colSet}) VALUES ({$colVal})";
        return $query;
    } else {
        return false;
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function filter ($data1,$data2){
    $ambos=[];

    if(count($data1)>count($data2)){
        $origen = $data1;
        $list = $data2;
    }else{
        $origen = $data2;
        $list = $data1;
    }
    foreach($origen as $item){
        foreach($list as $row){
            if($item['name'] === $row['name']){
                $ambos[] = $item;
            }

        }
    }
    return $ambos;
    }
    // /////////////////////////////////////////////////////////////////////////
function array_diff_values($tab1, $tab2)
    {
    $result = array();
    foreach($tab1 as $values) if(! in_array($values, $tab2)) $result[] = $values;
    return $result;
    } 

function crearesfera($arraydata,$namearray,$arrayimg){
     $mandar=[];
     $valorarray = count($arraydata);
    if(count($arraydata) != 0){
        // echo'arreglo con cosas xd'; 
           
            $mandar[]=[
                'name'=> $namearray,
                // 'value'=> 100,
                // 'logXs'=> 3,
                'value'=> $valorarray,
                // 'logXs'=> strval(log($valorarray)),
                'logXs'=> strval(round(log($valorarray))),
                'image'=> $arrayimg,
                'children' =>$arraydata
            ]; 

            return $mandar;
    }else{
        // echo'arreglo vacio';
        return  $mandar;
    }
}; //fin del metodo

//followers los que lo SIGUEN 
//friend los que lo sigue 

// generar y manipular el archivo json
function sendPack($userName) {
    include ("conexion.php");
$contX= 0;
    $query = "SELECT * FROM tb_comp WHERE nombre_a = '$userName' AND tipo_relacion = '1'";
    if ($result = $mysqli->query($query)){
        $response2=[];
        $nvoArreglo=[];
        foreach ($result as $key) {
            $nuevo = 0; 
            $nuevo = $key["followers_count"];
            $valor1 = $key["friends_count"];
            $quitar2 = "_normal";
            $temp2 = str_replace($quitar2,"",$key["url_img"]);
            $parse =explode(' ', $key["created_at"]);  // returns Saturday, January 30 10 02:06:34
            $new_date = $parse[2].' '.$parse[1].' '.$parse[5];
            $parse2 =explode(' ', $key["endtweetdate"]);  // returns Saturday, January 30 10 02:06:34
            $new_date2 = $parse2[2].' '.$parse2[1].' '.$parse2[5];
            if ($valor1 > 0) {
                 // $nuevo = 1;
                 // $promedio = 10;
                 $promedio = $nuevo / $valor1;
            }else{
                $valor1 = 1;
                $promedio = $nuevo / $valor1;
            }
            
           $response2  = [
                [   ////
                    'category'=>'Me siguen', 
                    'value'=>$key["followers_count"]
                ],////
                 [   ///
                    'category'=>'Los que sigo', 
                    'value'=>$key["friends_count"]
                ]///
            ];
            $formodal  = [
                [   ////
                    'category'=>'Retwets', 
                    'value'=>$key["retweet_count"]
                ],////
                 [   ///
                    'category'=>'Likes', 
                    'value'=>$key["favorite_count"]
                ]///
            ];
            $nvoArreglo[]=[
                'name'=> $key["nombre_b"],
                'value' => $key["followers_count"],
                'logXs' =>  strval(round(log($nuevo))),
                'friends' => $key["friends_count"],
                'location'=> $key["location"],
                'protected'=> ($key["protected"] == 1)?'Protegido':'Publico',
                // 'created_at'=> $key["created_at"],
                'image'=> $temp2,
                'FECHA'=> $new_date,
                'statuses_count'=> $key["statuses_count"],
                'twt_created_at'=>  $new_date2,
                'endtweet'=>   $key["endtweet"],
                'rt' => $key["retweet_count"],
                'like' => $key["favorite_count"],
                'daterecord'=>   $key["daterecord"],
                'impacto'=> $promedio,
                'pie'=> $response2,
                'resmodal'=> $formodal
                
            ]; 
          
            $contX++;
        }
     
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////
$contY= 0;
    $query2 = "SELECT * FROM tb_comp WHERE nombre_a = '$userName' AND tipo_relacion = '0'";
    if ($result = $mysqli->query($query2)){
         $response=[];
        $nvoArreglo2=[];
        foreach ($result as $key) {
            $nuevo2 = 0;
            $nuevo2 = $key["followers_count"];
            $quitar2 = "_normal";
            $temp = str_replace($quitar2,"",$key["url_img"]);
            
            $parse4 =explode(' ', $key["created_at"]);  // returns Saturday, January 30 10 02:06:34
            $new_date3 = $parse4[2].' '.$parse4[1].' '.$parse4[5];

            $parse21 =explode(' ', $key["endtweetdate"]);  // returns Saturday, January 30 10 02:06:34
            $new_date4 = $parse21[2].' '.$parse21[1].' '.$parse21[5];
            $valor3 = $key["friends_count"];
            if ($valor3 > 0) {
                 $promedio2 = $nuevo2 / $valor3;
            }else{
                $valor3 = 1;
                $promedio2 = $nuevo2 / $valor3;
            }
            $response  = [
                [   ////
                    'category'=>'Me siguen', 
                    'value'=>$key["followers_count"]
                ],////
                 [   ///
                    'category'=>'Los que sigo', 
                    'value'=>$key["friends_count"]
                ]///
            ];
            $formodal2  = [
                [   ////
                    'category'=>'Retwets', 
                    'value'=>$key["retweet_count"]
                ],////
                 [   ///
                    'category'=>'Likes', 
                    'value'=>$key["favorite_count"]
                ]///
            ];
            $nvoArreglo2[]=[
                'name'=> $key["nombre_b"],
                'value' => $key["followers_count"],
                // 'logXs' =>  strval(log($nuevo2)),
                'logXs' =>   strval(round(log($nuevo2))),
                'friends' => $key["friends_count"],
                'location'=> $key["location"],
                'protected'=> ($key["protected"] == 1)?'Protegido':'Publico',
                // 'created_at'=> $key["created_at"],
                'image'=> $temp,
                'FECHA'=> $new_date3,
                'statuses_count'=> $key["statuses_count"],
                'twt_created_at'=>  $new_date4,
                'endtweet'=>   $key["endtweet"],
                'rt' => $key["retweet_count"],
                'like' => $key["favorite_count"],
                'daterecord'=>   $key["daterecord"],
                'impacto'=> $promedio2,
                // 'image'=> $key["url_img"],
                // 'image'=> $temp2,
                'pie'=> $response,
                'resmodal'=> $formodal2

            ]; 
              $contY++;
        }        
    }

$SEGUIDOS = $nvoArreglo;
$SEGUIDORES = $nvoArreglo2;
// 
$onlyseguidores = array_diff_values($SEGUIDORES,$SEGUIDOS);
$onlyseguidos = array_diff_values($SEGUIDOS,$SEGUIDORES);
$ambos = filter($SEGUIDOS,$SEGUIDORES);
// 
$valueambos =count($ambos);



foreach ($onlyseguidores as $key => $values):
       $onlyseguidores[$key]['relacion']="Te sigue";
endforeach;
foreach ($onlyseguidos as $key => $values):
      $onlyseguidos[$key]['relacion']="Lo sigues";
endforeach;
foreach ($ambos as $key => $values):
      $ambos[$key]['relacion']="Ambos";
endforeach;

$dif= array_merge($onlyseguidores, $onlyseguidos,$ambos);

$esfera0=[];
$esfera1=[];
$esfera2=[];
$esfera3=[];
$esfera4=[];
foreach ($dif as $key => $value) {
    if($dif[$key]['impacto'] > 0 && $dif[$key]['impacto'] <= 10 ){
        $esfera0[]=   $dif[$key]   ;
    }
    elseif($dif[$key]['impacto'] > 10 && $dif[$key]['impacto'] <= 100 ){
        $esfera1[]=   $dif[$key]   ;
    }
    elseif($dif[$key]['impacto'] > 100 && $dif[$key]['impacto'] <= 300){
        $esfera2[]=   $dif[$key]   ;
    }elseif($dif[$key]['impacto'] > 300){
        $esfera3[]=   $dif[$key]   ;
    }else{
        $esfera4[]=   $dif[$key]   ;
    }
}

$datafin0 = crearesfera($esfera0,"Relevancia nula","package/img/relev1.jpg");
$datafin1 = crearesfera($esfera1,"Relevancia menor","package/img/relev2.jpg");
$datafin2 = crearesfera($esfera2,"Relevancia media","package/img/relev3.jpg");
$datafin3 = crearesfera($esfera3,"Relevancia mayor","package/img/relev4.jpg");
$datafin4 = crearesfera($esfera4,"Relevancia cero","package/img/relev0.jpg");

$dataall = array_merge($datafin0,$datafin1,$datafin2,$datafin3,$datafin4);

$consuIMG = "SELECT * FROM tb_comp WHERE nombre_b = '$userName'";
$ulrimgX = "";
$cont_fol = "";
$cont_fri = "";

if ($contenedor = $mysqli->query($consuIMG)){
    $res = mysqli_num_rows($contenedor);

    if($res > 0) {
        foreach ($contenedor as $key) {
            $ulrimgXtemp= $key["url_img"];
            $quitar3 = "_normal";
            $ulrimgX = str_replace($quitar3,"",$ulrimgXtemp);
            $cont_fol = $key["followers_count"]; 
            $cont_fri = $key["friends_count"];

            } 
    }else{
        $ulrimgX ="https://unavatar.io/twitter/".$userName;
        $cont_fol = $contX;
        $cont_fri = $contY;
    }

}



$contXY = $cont_fol + $cont_fri;
// echo($ulrimgX);

// $fin = log($contXY);
$fin = round(log($contXY));

$json= [
        
            'name'=> $userName,
            "value"=> $contXY, ///como no se manda el valor, lo toma de los hijos
             "logXs"=> $fin,
            // "value"=> $contamayor,
            'image'=> $ulrimgX,
            // 'children' => $finalmandar
            'children' => $dataall

            
            // 'marcador1'=> $mandarc,
            // 'marcador2'=> $mandard
        ];
        // $json = array_merge($mandara, $mandarb);
        return $json;



}

function fulllist($userName) {
  include ("conexion.php");

$query = "SELECT * FROM tb_comp WHERE nombre_a = '$userName' AND tipo_relacion = '1'";
    if ($result = $mysqli->query($query)){
        $nvoArreglo=[];
        foreach ($result as $key) {

            $quitar2 = "_normal";
            $temp2 = str_replace($quitar2,"",$key["url_img"]);
            
            $parse3 =explode(' ', $key["created_at"]);  // returns Saturday, January 30 10 02:06:34
            $new_date8 = $parse3[2].' '.$parse3[1].' '.$parse3[5];

            $parse21 =explode(' ', $key["endtweetdate"]);  // returns Saturday, January 30 10 02:06:34
            $new_date21 = $parse21[2].' '.$parse21[1].' '.$parse21[5];

            $nvoArreglo=[
               'name'=> $key["nombre_b"],
               'follwers' => $key["followers_count"],
               'friends' => $key["friends_count"],
               'image'=> $temp2,
               'location'=> $key["location"],
                'protected'=> ($key["protected"] == 1)?'Protegido':'Publico',
                'created_at'=> $key["created_at"],
                'FECHA'=> $new_date8,
                'statuses_count'=> $key["statuses_count"],
                'twt_created_at'=>  $new_date21,
                'endtweet'=>   $key["endtweet"],
                'rt' => $key["retweet_count"],
                'like' => $key["favorite_count"],
                'daterecord'=>   $key["daterecord"]

            ];
        }
     
    }

$query = "SELECT * FROM tb_comp WHERE nombre_a = '$userName' AND tipo_relacion = '0'";
    if ($result = $mysqli->query($query)){
        $nvoArreglo2=[];
        foreach ($result as $key) {

            $quitar2 = "_normal";
            $temp2 = str_replace($quitar2,"",$key["url_img"]);
            
            $parse =explode(' ', $key["created_at"]);  // returns Saturday, January 30 10 02:06:34
            $new_date = $parse[2].' '.$parse[1].' '.$parse[5];

            $parse2 =explode(' ', $key["endtweetdate"]);  // returns Saturday, January 30 10 02:06:34
            $new_date2 = $parse2[2].' '.$parse2[1].' '.$parse2[5];

            $nvoArreglo2=[
               'name'=> $key["nombre_b"],
               'follwers' => $key["followers_count"],
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
                'daterecord'=>   $key["daterecord"]

            ];
        }
     
    }
$SEGUIDOS = $nvoArreglo;
$SEGUIDORES = $nvoArreglo2;
$onlyseguidores = array_diff_values($SEGUIDORES,$SEGUIDOS);
$onlyseguidos = array_diff_values($SEGUIDOS,$SEGUIDORES);
$ambos = filter($SEGUIDOS,$SEGUIDORES);
// $valueambos =count($ambos);



$asignados = [];
$asignados= [array_merge($onlyseguidores, $onlyseguidos,$ambos)];


return $asignados;



}//end fucction





?>