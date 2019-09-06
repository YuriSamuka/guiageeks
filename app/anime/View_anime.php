<?php
/**
 * View da pagina de perfil de anime
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 14/11/2017 12:42
 */

namespace Guiageeks\app\anime;


class View_anime extends \Guiageeks\lib\View implements \Guiageeks\lib\Iview
{
    public function __construct()
    {
    }

    public function renderizar_pagina(){
//        var_dump($_POST);
        $this->render_header();
        $html = implode('',file(WEB . '/html/template-perfil-conteudo.html'));
        $htmlVideoAnime = implode('',file(WEB . '/html/template-trailer-anime.html'));
        $search = [
            '[TIPO_CONTEUDO]',
            '[VIDEO_ANIME]',
            '[CARROSSEL-VOLUMES-MANGA]',
            '[WEB_URL]'
        ];
        $replace = [
            'anime',
            $htmlVideoAnime,
            '',
            WEB_URL
        ];
        print str_replace($search, $replace, $html);
        $this->render_footer();
    }
}