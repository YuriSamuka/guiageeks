<?php
/**
 * Interface padrão para todas as classes View.
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 06/11/2017 20:58
 */

namespace Guiageeks\lib;


interface Iview
{
    /**
     * Construtor da View
     *
     * @access public
     */
    public function __construct();

    /**
    * Renderiza tada a pagina por completo
    *
    * @access public
    * @return void
    */
    public function renderizar_pagina();

}