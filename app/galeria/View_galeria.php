<?php
/**
 * Created by PhpStorm.
 * User: Yuri
 * Date: 22/02/2018
 * Time: 00:26
 */

namespace Guiageeks\app\galeria;


class View_galeria extends \Guiageeks\lib\View implements \Guiageeks\lib\Iview
{
    private $infosPaginacao;
    /**
     * Construtor da View
     *
     * @access public
     */
    public function __construct($infosPaginacao = [])
    {
        $this->infosPaginacao = $infosPaginacao;

    }

    /**
     * Renderiza tada a pagina por completo
     *
     * @access public
     * @return void
     */
    public function renderizar_pagina()
    {
        $this->render_header();
        $html = implode('',file(WEB . '/html/template-galeria-conteudo.html'));
        $search = [
            '[CARDS_RESULTADO_BUSCA]',
            '[PAGINACAO]'
        ];
        $replace = [
            $this->getHtmlCardsResultado(),
            $this->getHtmlPaginacao(),
            WEB_URL
        ];
        print str_replace($search, $replace, $html);
        $this->render_footer();
    }

    private function getHtmlCardsResultado(){
        $htmlCards = implode('',file(WEB . '/html/template-card-galeria-conteudo.html'));
        $search = [
            '[WEB_URL]',
            '[SERVER]'
        ];
        $replace = [
            WEB_URL,
            SERVER . (ROOT_APP ? '/' .ROOT_APP : '')
        ];
        $htmlCards = str_replace($search, $replace, $htmlCards);
        $htmlRetorno = '';
        $ultimaPagina = ceil($this->infosPaginacao['qtd_resultados']/12);
//        var_dump($this->infosPaginacao['qtd_resultados']);die();
        if ($this->infosPaginacao['qtd_resultados'] > 12 && $ultimaPagina != $this->infosPaginacao['pagina']){
            $qtd_cards = 12;
        }else if ($this->infosPaginacao['qtd_resultados'] <= 12){
            $qtd_cards = $this->infosPaginacao['qtd_resultados'];
        } else{
            $qtd_cards = $this->infosPaginacao['qtd_resultados'] - (12 * ($ultimaPagina - 1));
        }
        for ($i = 0; $i < $qtd_cards; $i++){
            $htmlRetorno .= $htmlCards;
        }
        return $htmlRetorno;
    }

    private function getHtmlPaginacao(){
        $tagLiPageItem = $this->criaTagHTML('li', ['class' => 'page-item'], null, true);
        $tagLiActive = $this->criaTagHTML('li', ['class' => 'page-item active'], null, true);
        $baseUrl = SERVER . (ROOT_APP ? '/' .ROOT_APP : '');
        $html = [];
        $html[] = $tagLiPageItem[0];
        $html[] =   $this->criaTagHTML('a', ['class' => 'btn btn-cor-padrao link-paginacao-anterior border-0', 'href' => ''],'Anterior');
        $html[] = $tagLiPageItem[1];
        if ($this->infosPaginacao['pagina'] == 1){
            $html[] = $tagLiActive[0];
            $html[] =   $this->criaTagHTML('a', ['class' => 'page-link', 'value' => 1, 'href' => $baseUrl], 1);
            $html[] = $tagLiActive[1];
        }else{
            $html[] = $tagLiPageItem[0];
            $html[] =   $this->criaTagHTML('a', ['class' => 'page-link', 'href' => $baseUrl], 1);
            $html[] = $tagLiPageItem[1];
        }
        if ($this->infosPaginacao['qtd_resultados'] > 60){
            $inicioFor = 1;
            $fimFor = 4;
            if ($this->infosPaginacao['pagina'] > 3){
                $inicioFor = $this->infosPaginacao['pagina'] - 3;
                $fimFor = $this->infosPaginacao['pagina'];
            }
            $temReticenciasInicial = false;
            for ($i = $inicioFor; $i <= $fimFor; $i++){
                if ($this->infosPaginacao['pagina'] != 1 && !$temReticenciasInicial){
                    $html[] =   $this->criaTagHTML('a', ['class' => 'page-link disabled btn', 'role' =>'button','aria-disabled' => 'true', 'href' => ''], '...');
                    $temReticenciasInicial = true;
                }
                if ($this->infosPaginacao['pagina'] == $i + 1){
                    $html[] = $tagLiActive[0];
                    $html[] =   $this->criaTagHTML('a', ['class' => 'page-link', 'href' => $baseUrl], $i + 1);
                    $html[] = $tagLiActive[1];
                }else{
                    $html[] = $tagLiPageItem[0];
                    $html[] =   $this->criaTagHTML('a', ['class' => 'page-link', 'href' => $baseUrl], $i + 1);
                    $html[] = $tagLiPageItem[1];
                }
            }
            $totalPaginas = ceil($this->infosPaginacao['qtd_resultados']/12);
            if ($totalPaginas + 3 < $this->infosPaginacao['pagina'] || 1 == $this->infosPaginacao['pagina']){
            $html[] =   $this->criaTagHTML('a', ['class' => 'page-link disabled btn', 'role' =>'button','aria-disabled' => 'true', 'href' => ''], '...');
            }
            $html[] = $tagLiPageItem[1];
            $html[] = $tagLiPageItem[0];
            $html[] =   $this->criaTagHTML('a', ['class' => 'page-link', 'href' => ''], ceil($this->infosPaginacao['qtd_resultados']/12));
            $html[] = $tagLiPageItem[1];
        } else if ($this->infosPaginacao['qtd_resultados'] > 12){
            $qtdPaginas = ceil($this->infosPaginacao['qtd_resultados']/12);
            for ($i = $this->infosPaginacao['pagina']; $i < $qtdPaginas; $i++){
                if ($this->infosPaginacao['pagina'] == $i + $this->infosPaginacao['qtd_resultados'] + 1){
                    $html[] = $tagLiActive[0];
                    $html[] =   $this->criaTagHTML('a', ['class' => 'page-link', 'href' => $baseUrl], $i + 1);
                    $html[] = $tagLiActive[1];
                }else{
                    $html[] = $tagLiPageItem[0];
                    $html[] =   $this->criaTagHTML('a', ['class' => 'page-link', 'href' => $baseUrl], $i + 1);
                    $html[] = $tagLiPageItem[1];
                }
            }
        }
        $html[] = $tagLiPageItem[0];
        $html[] =   $this->criaTagHTML('a', ['class' => 'btn btn-cor-padrao link-paginacao-anterior border-0', 'href' => $baseUrl],'proximo');
        $html[] = $tagLiPageItem[1];
        return ($this->infosPaginacao['qtd_resultados'] > 12) ? implode('', $html) : '';
    }
}