<?php
/**
 * Created by PhpStorm.
 * User: Yuri
 * Date: 03/02/2018
 * Time: 07:23
 */

namespace Guiageeks\lib;


abstract class Model
{
    const CLASSIFICACAO_INDICATIVA_LIVRE = 0;
    const CLASSIFICACAO_INDICATIVA_14 = 14;
    const CLASSIFICACAO_INDICATIVA_18 = 18;

    const CONTEUDO_TERMINADO = 0;
    const CONTEUDO_ATUALMENTE_NO_AR = 1;

    public static $statusConteudo = [
        self::CONTEUDO_TERMINADO => 'Finalizado',
        self::CONTEUDO_ATUALMENTE_NO_AR => 'Atualmente no ar'
    ];

    public function __construct($idConteudo)
    {
    }
}