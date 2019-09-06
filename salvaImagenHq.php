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
$destino .= DIRECTORY_SEPARATOR . $_POST['tipoImagem'];
if(!file_exists($destino)) {
    mkdir($destino);
}

switch ($_POST['tipo_conteudo']){
    case 'manga':
    case 'anime':
//        \Guiageeks\lib\Db::update([$_POST['tipoImagem'] => $urlImage], $_POST['tipo_conteudo'], [$_POST['id_conteudo']]);
        break;
    case 'hq_volume':
        $destino .= DIRECTORY_SEPARATOR . $_POST['tamanho'] . '.jpg';
        if(!file_exists($destino)) {
            print file_put_contents($destino, $_POST['file_contents']);
            $urlImage = WEB_URL . '/media/' . $_POST['tipo_conteudo'] . '/' . $_POST['id_conteudo'] . '/imagem/' . $_POST['tipoImagem'] . '/' . $_POST['tamanho'] . '.jpg';
            if (empty(\Guiageeks\lib\Db::listar(['imagem', 'id_volume' => $_POST['id_conteudo'], 'escala' => $_POST['tamanho']], 'hq_volume_imagem'))){
                \Guiageeks\lib\Db::insert(['id_volume' => $_POST['id_conteudo'], 'imagem' => $urlImage, 'escala' => $_POST['tamanho']], 'hq_volume_imagem');
            }else{
                $registroImagen = \Guiageeks\lib\Db::listar(['imagem', 'id_volume' => $_POST['id_conteudo'], 'escala' => $_POST['tamanho']], 'hq_volume_imagem')[0];
                \Guiageeks\lib\Db::update(['imagem' => $urlImage], 'hq_volume_imagem', [[(int)$_POST['id_conteudo']], [$registroImagen['imagem']]]);
            }
        }
        break;
}