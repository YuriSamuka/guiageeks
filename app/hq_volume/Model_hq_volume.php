<?php
/**
 * Model da pagina de perfil de anime
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 14/11/2017 12:42
 */

namespace Guiageeks\app\hq_volume;

use \Guiageeks\lib\Db as Db;

class Model_hq_volume extends \Guiageeks\lib\Model implements \Guiageeks\lib\Imodel
{
    private $idHqVolume;

    public function __construct($idHqVolume)
    {
        $this->idHqVolume = (int)$idHqVolume;
    }

    public function getVolumes(){
        $types = array( 'png', 'jpg', 'jpeg', 'gif' );
        $path = WEB . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR .'manga' . DIRECTORY_SEPARATOR . 'imagens' . DIRECTORY_SEPARATOR . 'posters-volumes' . DIRECTORY_SEPARATOR . $this->idHqVolume;
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
                $aUrlVolumes[] = WEB_URL . '/media/manga/imagens/posters-volumes/' . $this->idHqVolume . '/' .$fileInfo->getFilename();
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
        $hqVolume = $db->findByPk([$this->idHqVolume], 'hq_volume');
        $imagensPoster = $db->listar(['id_volume' => $this->idHqVolume, 'escala' => 'medium_url', 'imagem'], 'hq_volume_imagem');
        $imagensCover = $db->listar(['id_volume' => $this->idHqVolume, 'escala' => 'original_url', 'imagem'], 'hq_volume_imagem');
        $hqVolume['poster'] = $imagensPoster[0]['imagem'];
        $hqVolume['cover'] = $imagensCover[0]['imagem'];
        $editora = $db->findByPk([$hqVolume['id_editora']], 'editora_hq');
        $hqVolume['editora'] = $editora['nome'];
//        $hqVolume['status'] = self::$statusConteudo[$hqVolume['status']];
//        if ($hqVolume['data_inicio']){
//            $hqVolume['data_inicio'] = date('d/m/Y',strtotime($hqVolume['data_inicio']));
//        }else{
//            unset($hqVolume['data_inicio']);
//        }
//        if ($hqVolume['data_fim']){
//            $hqVolume['data_fim'] = date('d/m/Y',strtotime($hqVolume['data_fim']));
//        }
//        if ($hqVolume['indicacao']){
//            $hqVolume['indicacao'] = $hqVolume['indicacao'] . ' anos';
//        }else{
//            unset($hqVolume['indicacao']);
//        }
//        if (!$hqVolume['titulo_japones']){
//            unset($hqVolume['titulo_japones']);
//        }
//        if (!$hqVolume['qtd_capitulos']){
//            unset($hqVolume['qtd_capitulos']);
//        }
//        if (!$hqVolume['qtd_volumes']){
//            unset($hqVolume['qtd_volumes']);
//        }
//        $hqVolume['volumes'] = $this->getVolumes();
        return $hqVolume;
    }

    /**
     * Contabiliza uma visualização para o conteudo que estiver sendo trabalhado na respectiva instancia da class
     * que implementa esta interface
     * @access public
     * @return void
     */
    public function contabilizaVisualizacao()
    {
        $contador = Db::findByPk([[$this->idHqVolume], ['m']], 'visualizacao_conteudo');
        if (empty($contador)){
            Db::insert(['id_conteudo' => $this->idHqVolume, 'tipo_conteudo' => 'm', 'visualizacao' => 1], 'visualizacao_conteudo');
        }else{
            Db::update(['visualizacao' => $contador['visualizacao'] + 1], 'visualizacao_conteudo', [[$this->idHqVolume], ['m']]);
        }
    }
}