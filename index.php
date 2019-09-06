<?php
/**
 * Namespace GLOBAL do sistema
 * Seus respectivos filhos serão
 *  Guiageeks\lib
 *  Guiageeks\app
 *  Guiageeks\...
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 06/11/2017 20:11
 */

namespace Guiageeks;

header("Content-Type: text/html; charset=utf-8");

require 'vendor/autoload.php';
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
            $pagina = new  \Guiageeks\View_home();
            $pagina->renderizar_pagina();
        }
    }

    public function response_json(){
        if (isset($_GET['acao']) && $_GET['acao']){
            $model = new \Guiageeks\Model_home();
            switch ($_GET['acao']){
                case 'carregar_front':
                    print json_encode($model->getConteudo());
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
