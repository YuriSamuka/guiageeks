<?php
/**
 * Controller da pagina de perfil de anime
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 14/11/2017 12:42
 */

namespace Guiageeks\app;

header("Content-Type: text/html; charset=utf-8");

require '../../vendor/autoload.php';

use \Guiageeks\lib\ferramentas as ferramentas;

$dirtmp = sys_get_temp_dir();
session_save_path($dirtmp);
$dirtmp = null;
unset($dirtmp);
session_name("Guiageeks");
session_start();

class Index
{
    public function __construct(){
        ferramentas::SetConfigPath();

        if ($_GET){
            $this->response_json();
        } else{
            if (isset($_POST['sinopse']) && $_POST['sinopse']){
                $this->update($_POST['sinopse'], $_POST['id_anime']);
            }
            $pagina = new  \Guiageeks\app\anime\View_anime();
            $pagina->renderizar_pagina();
        }
    }

    /*METODO PROVISORIO SO PRA SALVAR AS SINOPSES, DEPOIS AS FUNÇÕES DO BANCO VÃO FICAR SEPARADINHAS EM UM LUGAR CERTO*/
    public function update($sinopese, $id){
        $db = \Guiageeks\lib\Conexao::novaConexao('guiageeks', 'guiageeks', 'b5iNR432qnrx!#', 'guiageeks.mysql.dbaas.com.br', 'utf8');
        $stm = $db->prepare("UPDATE anime SET sinopse = '" . $sinopese . "' WHERE id_anime =" . $id);
        $stm->execute();
    }
//
//    public function buscaSinopse($id){
//        $db = Conexao::instaciar('guiageeks', 'guiageeks', 'b5iNR432qnrx!#', 'guiageeks.mysql.dbaas.com.br');
//        $stm = $db->prepare('SELECT sinopse FROM anime WHERE id_anime = ' . $id);
//        $stm->execute();
//        return $stm->fetchAll();
//    }

    public function response_json(){
        if (isset($_GET['acao']) && $_GET['acao']){
            $model = new \Guiageeks\app\anime\Model_anime($_GET['id']);
            switch ($_GET['acao']){
                case 'carregar_front':
                    print json_encode($model->getConteudo());
                    $model->contabilizaVisualizacao();
                    break;
                default:
                    echo 'pagina erro de erro!';
            }
        } else {
            echo "pagina de erro!";
        }
    }
}

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set("America/Sao_Paulo");

new Index();

session_write_close();