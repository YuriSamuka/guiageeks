<?php
/**
 * Controller da pagina de galeria
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 22/02/2018 00:27
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
    private $model;
    private $view;

    public function __construct(){
        ferramentas::SetConfigPath();
        if($_GET){
            $filtros = $_GET;
        }else{
            $filtros = $this->extraiFiltros($_SERVER['REQUEST_URI']);
        }
        $this->model = new \Guiageeks\app\galeria\Model_galeria($filtros);
        $infosPaginacao = ['qtd_resultados' => $this->model->quantidadeResultados(), 'pagina' => $filtros['pagina']];
        $this->view = new  \Guiageeks\app\galeria\View_galeria($infosPaginacao);

        if ($_GET){
            $this->response_json();
        } else{
            $this->view->renderizar_pagina();
        }
    }

    public function response_json(){
        if (isset($_GET['acao']) && $_GET['acao']){
            switch ($_GET['acao']){
                case 'carregar_front':
                    print json_encode($this->model->getConteudo());
//                    $model->contabilizaVisualizacao();
                    break;
                default:
                    echo 'pagina erro de erro!';
            }
        } else {
            echo "pagina de erro!";
        }
    }

    private function extraiFiltros($url){
        $aFiltrosURL = explode('/',$url);
        $aFiltro = [];
        $i = 0;
        while ($aFiltrosURL[$i] != 'galeria'){
            $i++;
        }
        $aFiltrosURL = array_filter($aFiltrosURL);
        $i++;
        $aFiltro['tipo_conteudo'] = $aFiltrosURL[$i];
        $i++;
        $aFiltro['tipo_filtro'] = $aFiltrosURL[$i];
        switch ($aFiltro['tipo_filtro']){
            case 'a-z':
                $i++;
                $aFiltro['letra'] = $aFiltrosURL[$i];
                break;
            default:
        }
        $i++;
        $aFiltro['pagina'] = isset($aFiltrosURL[$i])? $aFiltrosURL[$i] : 1;
        return $aFiltro;
    }
}

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set("America/Sao_Paulo");

new Index();

session_write_close();