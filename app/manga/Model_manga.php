<?php
/**
 * Model da pagina de perfil de anime
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 14/11/2017 12:42
 */

namespace Guiageeks\app\manga;

use \Guiageeks\lib\Db as Db;

class Model_manga extends \Guiageeks\lib\Model implements \Guiageeks\lib\Imodel
{
    private $idManga;

    public function __construct($idManga)
    {
        $this->idManga = (int)$idManga;
    }

    public function getVolumes(){
        $types = array( 'png', 'jpg', 'jpeg', 'gif' );
        $path = WEB . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR .'manga' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'posters-volumes' . DIRECTORY_SEPARATOR . $this->idManga;
        $dir = [];
        if (file_exists($path)){
            $dir = new \DirectoryIterator($path);
        }else {
            mkdir($path);
        }
        $aUrlVolumes = [];
        foreach ($dir as $fileInfo) {
            $ext = strtolower( $fileInfo->getExtension() );
            if( in_array( $ext, $types ) ){
                $aUrlVolumes[] = WEB_URL . '/media/manga/imagens/posters-volumes/' . $this->idManga . '/' .$fileInfo->getFilename();
            }
        }
        sort($aUrlVolumes);
        if (count($aUrlVolumes) >= 15){
            $aUrlVolumes = array_slice($aUrlVolumes,0,15);
        }
        return $aUrlVolumes;
    }

    public function getConteudo(){
        $db = new Db();
        $manga = $db->findByPk([$this->idManga], 'manga');
        $manga = $manga;
        $manga['status'] = self::$statusConteudo[$manga['status']];
        if ($manga['data_inicio']){
            $manga['data_inicio'] = date('d/m/Y',strtotime($manga['data_inicio']));
        }else{
            unset($manga['data_inicio']);
        }
        if ($manga['data_fim']){
            $manga['data_fim'] = date('d/m/Y',strtotime($manga['data_fim']));
        }
        if ($manga['indicacao']){
            $manga['indicacao'] = $manga['indicacao'] . ' anos';
        }else{
            unset($manga['indicacao']);
        }
        if (!$manga['titulo_japones']){
            unset($manga['titulo_japones']);
        }
        if (!$manga['qtd_capitulos']){
            unset($manga['qtd_capitulos']);
        }
        if (!$manga['qtd_volumes']){
            unset($manga['qtd_volumes']);
        }
        $manga['volumes'] = $this->getVolumes();
        return $manga;
    }

    /**
     * Contabiliza uma visualização para o conteudo que estiver sendo trabalhado na respectiva instancia da class
     * que implementa esta interface
     * @access public
     * @return void
     */
    public function contabilizaVisualizacao()
    {
        $contador = Db::findByPk([[$this->idManga], ['m']], 'visualizacao_conteudo');
        if (empty($contador)){
            Db::insert(['id_conteudo' => $this->idManga, 'tipo_conteudo' => 'm', 'visualizacao' => 1], 'visualizacao_conteudo');
        }else{
            Db::update(['visualizacao' => $contador['visualizacao'] + 1], 'visualizacao_conteudo', [[$this->idManga], ['m']]);
        }
    }
}