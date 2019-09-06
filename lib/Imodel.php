<?php
/**
 * Interface padrão para todas as classes model.
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 06/11/2017 20:57
 */

namespace Guiageeks\lib;


interface Imodel
{
    /**
     * Construtor da model
     *
     * @access public
     */
    public function __construct($params);

    /**
     * Retorna um array contendo as informações de algum conteudo: anime/manga/hq, para serem mandas para view
     * @access public
     * @return array
     */
    public function getConteudo();

    /**
     * Contabiliza uma visualização para o conteudo que estiver sendo trabalhado na respectiva instancia da class
     * que implementa esta interface
     * @access public
     * @return void
     */
    public function contabilizaVisualizacao();
}