<?php
/**
 * Interface padrão para todas as classes View.
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 06/11/2017 21:27
 */

namespace Guiageeks\lib;


abstract class View
{
    /**
     * Renderiza somente o cabeçalho do site incluindo o menu fixo do topo
     *
     * @access public
     * @return void
     */
    public final function render_header(){
        $html = implode('',file(WEB . '/html/header.html'));
        $search = [
            '[LINKS_CSS]',
            '[SERVER]'
        ];
        $replace = [
            $this->getCssLinks(),
            SERVER . '/' .ROOT_APP
        ];
        print str_replace($search, $replace, $html);
    }

    /**
     * Renderiza somente o rodapé do site.
     * OBS: no rodapé vão scripts js essenciais para o funciomanento da pagina
     *
     * @access public
     * @return void
     */
    public final function render_footer(){
        $html = implode('',file(WEB . '/html/footer.html'));
        print str_replace('[SCRIPTS_JS]', $this->getScriptsJS(), $html);
    }

    /**
     *
     * @return string
     */
    private function getCssLinks(){
        $sLinks = '';
        $sLinks .= $this->criaTagHTML('link', ['rel' => 'stylesheet', 'href' => WEB_URL . '/css/dropdown.css']);
        $sLinks .= $this->criaTagHTML('link', ['rel' => 'stylesheet', 'href' => WEB_URL . '/css/font-awesome.min.css']);
        $sLinks .= $this->criaTagHTML('link', ['rel' => 'stylesheet', 'href' => WEB_URL . '/css/flickity/flickity.css', 'media' => 'screen']);
        $sLinks .= $this->criaTagHTML('link', ['rel' => 'stylesheet', 'href' => WEB_URL . '/css/login-cadastro.css']);
        $sLinks .= $this->criaTagHTML('link', ['rel' => 'stylesheet', 'href' => WEB_URL . '/css/bootstrap/bootstrap.css', 'type' => 'text/css']);
        $sLinks .= $this->criaTagHTML('link', ['rel' => 'stylesheet', 'href' => WEB_URL . '/css/global.css', 'type' => 'text/css']);
        switch (get_called_class()){
            case 'Guiageeks\app\anime\View_anime':
            case 'Guiageeks\app\manga\View_manga':
            case 'Guiageeks\app\hq_volume\View_hq_volume':
            $sLinks .= $this->criaTagHTML('link', ['rel' => 'stylesheet', 'href' => WEB_URL . '/css/perfil.css', 'type' => 'text/css']);
            break;
            case 'Guiageeks\app\galeria\View_galeria':
                $sLinks .= $this->criaTagHTML('link', ['rel' => 'stylesheet', 'href' => WEB_URL . '/css/galeria-conteudo.css']);
                break;
        }
        return $sLinks;
    }

    private function getScriptsJS(){
        $sScripts = '';
        $sScripts .= $this->criaTagHTML('script', ['src' => WEB_URL . '/js/jquery-3.2.1.min.js']);
        $sScripts .= $this->criaTagHTML('script', ['src' => WEB_URL . '/js/popper.js']);
        switch (get_called_class()){
            case 'Guiageeks\app\anime\View_anime':
            case 'Guiageeks\app\manga\View_manga':
            case 'Guiageeks\app\hq_volume\View_hq_volume':
            $sScripts .= $this->criaTagHTML('script', ['src' => WEB_URL . '/js/carrega-conteudo.js']);
                break;
            case 'Guiageeks\View_home':
                $sScripts .= $this->criaTagHTML('script', ['src' => WEB_URL . '/js/carrega-home.js']);
                break;
            case 'Guiageeks\app\galeria\View_galeria':
                $sScripts .= $this->criaTagHTML('script', ['src' => WEB_URL . '/js/carrega-galeria.js']);
                break;
        }
        $sScripts .= $this->criaTagHTML('script', ['src' => WEB_URL . '/js/bootstrap.js']);
        $sScripts .= $this->criaTagHTML('script', ['src' => WEB_URL . '/js/eventos.js']);
        $sScripts .= $this->criaTagHTML('script', ['src' => WEB_URL . '/js/flickity.min.js']);
        return $sScripts;
    }

    /**
     * Cria e retorna uma tag html em formato de string, com o nome e os atributos especificados nos parametros. O parametro
     * atributos é um array associativo que leva os nomes dos atributos na $key e os valores dos respectivos atributos no $value
     * @param $nomeTag
     * @param array $atributos
     * @param null $subTag
     * @param null $texto
     * @return string
     */
    protected function criaTagHTML($nomeTag, $atributos = [], $texto = null, $formatoArray = false){
        $doc = new \DOMDocument();
        $explode = $formatoArray ? '[__EXPLODE__]' : '';
        $tag = new \DOMElement($nomeTag, $texto . $explode);
        $RefTag = $doc->appendChild($tag);
        foreach ($atributos as $nome => $valor){
            $RefTag->setAttribute($nome, $valor);
        }
        if ($formatoArray){
            return explode('[__EXPLODE__]', $doc->saveHTML());
        }
        return $doc->saveHTML();
    }
}