<?php
/**
 * View da pagina de perfil de anime
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 14/11/2017 12:42
 */

namespace Guiageeks\app\manga;


class View_manga extends \Guiageeks\lib\View implements \Guiageeks\lib\Iview
{

    public function __construct()
    {
    }

    public function renderizar_pagina(){
        $this->render_header();
        $html = implode('',file(WEB . '/html/template-perfil-conteudo.html'));
        $html_carrossel_manga = implode('',file(WEB . '/html/carrossel-volumes-manga.html'));
        $search = [
            '[TIPO_CONTEUDO]',
            '[VIDEO_ANIME]',
            '[CARROSSEL-VOLUMES-MANGA]',
            '[WEB_URL]'
        ];
        $replace = [
            'manga',
            '',
            $html_carrossel_manga,
            WEB_URL
        ];
        print str_replace($search, $replace, $html);
        $this->render_footer();
    }
}