<?php
/**
 * Controller da pagina de perfil de manga
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 31/01/2018 12:42
 */

namespace Guiageeks\app\manga;

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
            if ($_FILES['posters-volumes']){
                $i = 0;
                foreach ($_FILES['posters-volumes']['error'] as $key => $error) {

                    # Definir o diretório onde salvar os arquivos.
                    $destino = WEB . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR . 'manga' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'posters-volumes' . DIRECTORY_SEPARATOR . $_POST['id_anime'] . DIRECTORY_SEPARATOR . $_FILES['posters-volumes']['name'][$i];

                    #Move o arquivo para o diretório de destino
//                            \Guiageeks\lib\Db::insert(['' => $destino])
                    move_uploaded_file( $_FILES["posters-volumes"]["tmp_name"][$i], $destino );

                    #Próximo arquivo a ser analisado
                    $i++;
                }
            }
            $pagina = new  \Guiageeks\app\manga\View_manga();
            $pagina->renderizar_pagina();
        }
    }

    /*METODO PROVISORIO SO PRA SALVAR AS SINOPSES, DEPOIS AS FUNÇÕES DO BANCO VÃO FICAR SEPARADINHAS EM UM LUGAR CERTO*/
    public function update($sinopese, $id){
        $db = \Guiageeks\lib\Conexao::novaConexao('guiageeks', 'guiageeks', 'b5iNR432qnrx!#', 'guiageeks.mysql.dbaas.com.br', 'utf8');
//        $db = \Guiageeks\lib\Conexao::novaConexao('guiageeks', 'root', '', 'localhost', 'utf8');
        $stm = $db->prepare("UPDATE manga SET sinopse = '" . $sinopese . "' WHERE id_manga =" . $id);
//        echo "UPDATE manga SET sinopse = '" . $sinopese . "' WHERE id_manga =" . $id;
        $stm->execute();
    }

    public function response_json(){
        if (isset($_GET['acao']) && $_GET['acao']){
            $model = new \Guiageeks\app\manga\Model_manga($_GET['id']);
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