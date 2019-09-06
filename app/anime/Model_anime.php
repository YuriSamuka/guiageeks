<?php
/**
 * Model da pagina de perfil de anime
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 14/11/2017 12:42
 */

namespace Guiageeks\app\anime;

use \Guiageeks\lib\Db as Db;

class Model_anime extends \Guiageeks\lib\Model implements \Guiageeks\lib\Imodel
{
    private $idAnime;

    public function __construct($idAnime)
    {
        $this->idAnime = (int)$idAnime;
    }

    /**
     * Retorna um array contendo as informações de algum conteudo: anime/manga/hq, para serem mandas para view
     * @access public
     * @return mixed
     */
    public function getConteudo(){
        $anime = Db::findByPk([$this->idAnime], 'anime');
        $relacionamentoAnimeAnime = Db::listar(['id_anime_1' => $this->idAnime, 'id_anime_2'], 'relacionamento_anime_anime', null, 4);
        $conteudosRelacionados = [];
        for ($i = 0; $i < 4; $i++){
            if (isset($relacionamentoAnimeAnime[$i])){
                $idAnimeRelacionado =  $relacionamentoAnimeAnime[$i]['id_anime_2'];
                $conteudosRelacionados[] =  Db::findByPk([$idAnimeRelacionado], 'anime', ['poster', 'titulo_ingles', 'id_anime']);
            }
        }
        if (!empty($conteudosRelacionados)){
            $anime['conteudos_relacionados'] = $conteudosRelacionados;
        }
        $RelacionamentoGenerosAnime = Db::listar(['id_anime' => $this->idAnime, 'id_genero'], 'relacionamento_genero_anime_manga');
        $generosAnime = [];
        foreach ($RelacionamentoGenerosAnime as $item){
            $generosAnime[] = Db::findByPk([$item['id_genero']], 'generos_anime_manga')['nome'];
        }
        if (!empty($generosAnime)){
            $anime['generos'] = implode(', ', $generosAnime);
        }
        $anime['status'] = self::$statusConteudo[$anime['status']];
        if ($anime['data_inicio']){
            $anime['data_inicio'] = date('d/m/Y',strtotime($anime['data_inicio']));
        }else{
            unset($anime['data_inicio']);
        }
        if ($anime['data_fim']){
            $anime['data_fim'] = date('d/m/Y',strtotime($anime['data_fim']));
        }
        if ($anime['indicacao']){
            $anime['indicacao'] = $anime['indicacao'] . ' anos';
        }else{
            unset($anime['indicacao']);
        }
        if (!$anime['titulo_japones']){
            unset($anime['titulo_japones']);
        }
        if (!$anime['video_youtube']){
            unset($anime['video_youtube']);
        }
        return $anime;
    }

    /**
     * Contabiliza uma visualização para o conteudo que estiver sendo trabalhado na respectiva instancia da class
     * que implementa esta interface
     * @access public
     * @return void
     */
    public function contabilizaVisualizacao()
    {
        $contador = Db::findByPk([[$this->idAnime], ['a']], 'visualizacao_conteudo');
        if (empty($contador)){
            Db::insert(['id_conteudo' => $this->idAnime, 'tipo_conteudo' => 'a', 'visualizacao' => 1], 'visualizacao_conteudo');
        }else{
            Db::update(['visualizacao' => $contador['visualizacao'] + 1], 'visualizacao_conteudo', [[$this->idAnime], ['a']]);
        }
    }
}