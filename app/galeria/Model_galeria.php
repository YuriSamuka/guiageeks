<?php
/**
 * Created by PhpStorm.
 * User: Yuri
 * Date: 22/02/2018
 * Time: 00:26
 */

namespace Guiageeks\app\galeria;

use \Guiageeks\lib\Db as Db;
use \Guiageeks\lib\ferramentas as ferramentas;

class Model_galeria extends \Guiageeks\lib\Model implements \Guiageeks\lib\Imodel
{
    private $filtros;
    /**
     * Construtor da model
     *
     * @access public
     */
    public function __construct($filtros)
    {
        $this->filtros = $filtros;
    }

    public function getConteudo(){
        switch ($this->filtros['tipo_filtro']){
            case 'a-z':
                $offsetPagina = ($this->filtros['pagina'] - 1) * 12;
                $resutset = Db::listar(
                    ['id_anime', 'poster', 'titulo_ingles' => $this->filtros['letra'] . '%'],
                    $this->filtros['tipo_conteudo'],
                    'titulo_ingles',
                    12,
                    $offsetPagina
                );
                foreach ($resutset as $campo => $valor){
                    $resutset[$campo]['id_conteudo'] = $valor['id_anime'];
                    unset($resutset[$campo]['id_anime']);
                }
                return $resutset;
                break;
            default:
        }
    }

    public function quantidadeResultados(){
        $conn = Db::getConexao();
        $sql = '';
        $sql .= 'select ';
        $sql .= '   count(*) qtd_resultado ';
        $sql .= 'from ';
        $sql .=     $this->filtros['tipo_conteudo'] . ' ';
        $sql .= 'where ';
        switch ($this->filtros['tipo_filtro']){
            case 'a-z':
                $sql .= '   titulo_ingles like \'' . $this->filtros['letra'] . '%\' ';
                break;
            default:
        }
        $stm = $conn->prepare($sql);
        $stm->execute();
        $conn = null;
        return $stm->fetch(\PDO::FETCH_ASSOC)['qtd_resultado'];
    }

    /**
     * Contabiliza uma visualização para o conteudo que estiver sendo trabalhado na respectiva instancia da class
     * que implementa esta interface
     * @access public
     * @return void
     */
    public function contabilizaVisualizacao()
    {
        // TODO: Implement contabilizaVisualizacao() method.
    }
}