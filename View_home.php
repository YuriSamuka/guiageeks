<?php
/**
 * Interface padrão para todas as classes View.
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 06/11/2017 21:27
 */

namespace Guiageeks;

class View_home extends \Guiageeks\lib\View implements \Guiageeks\lib\Iview
{
    public function __construct()
    {
    }

    public function renderizar_pagina(){
        $this->render_header();
        $search = [
            '[WEB_URL]',
            '[SERVER]'
        ];
        $replace = [
            WEB_URL,
            SERVER . (ROOT_APP ? '/' .ROOT_APP : '')
        ];
        $html = implode('',file(WEB . '/html/template-home.html'));
        print str_replace($search, $replace, $html);
        $this->render_footer();
    }
}