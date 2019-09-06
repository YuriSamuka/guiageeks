<?php
/**
 * Created by PhpStorm.
 * User: Yuri
 * Date: 14/03/2018
 * Time: 00:43
 */
require_once 'lib/ferramentas.php';
require_once 'lib/Conexao.php';
require_once 'lib/Db.php';

Guiageeks\lib\ferramentas::SetConfigPath();

$destino = 'public' . DIRECTORY_SEPARATOR .
    'media' . DIRECTORY_SEPARATOR .
    $_POST['tipo_conteudo'] . DIRECTORY_SEPARATOR .
    $_POST['id_conteudo'] . DIRECTORY_SEPARATOR;

if (!file_exists($destino)){
    mkdir($destino);
}
$destino .= DIRECTORY_SEPARATOR . 'imagem';

if(!file_exists($destino)){
    mkdir($destino);
}
$destino .= DIRECTORY_SEPARATOR . 'poster';
if(!file_exists($destino)){
    mkdir($destino);
    switch ($_POST['tipo_conteudo']){
        case 'anime':
            $destino .= DIRECTORY_SEPARATOR . 'medio.jpg';
            print file_put_contents($destino, $_POST['file_contents']);
            $urlImage = WEB_URL . '/media/' . $_POST['tipo_conteudo'] .'/' . $_POST['id_conteudo'] . '/imagem/poster/medio.jpg';
            \Guiageeks\lib\Db::update(['poster' => $urlImage], $_POST['tipo_conteudo'], [$_POST['id_conteudo']]);
            break;
        case 'hq_volume':
            $destino .= DIRECTORY_SEPARATOR . $_POST['infosImagen'] . '.jpg';
            print file_put_contents($destino, $_POST['file_contents']);
            $urlImage = WEB_URL . '/media/' . $_POST['tipo_conteudo'] . '/' . $_POST['id_conteudo'] . '/imagem/poster/'. $_POST['infosImagen'] . '.jpg';
            if (empty(\Guiageeks\lib\Db::listar(['id_volume' => $_POST['id_conteudo'], 'escala' => $_POST['infosImagen']], 'hq_volume_imagem'))){

            }else{

                \Guiageeks\lib\Db::update(['imagem' => $urlImage], 'hq_volume_imagem', [$_POST['id_conteudo'], ]);
            }

    }
}else{
    print false;
}
