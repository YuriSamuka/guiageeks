<?php
/**
 * Interface padrão para todas as classes Model.
 *
 * @author Yuri Samuel M de Paula <yurisamuka@hotmail.com>
 * @since 06/11/2017 20:58
 */

namespace Guiageeks;

use \Guiageeks\lib\Db as Db;
use \Guiageeks\lib\ferramentas as ferramentas;

class Model_home extends \Guiageeks\lib\Model
{

    /**
     * Construtor da model
     *
     * @access public
     */
    public function __construct()
    {
    }

    /**
     * Retorna um array contendo as informações de algum conteudo: anime/manga/hq, para serem mandas para view.
     * Neste caso em especial, por se tratar da home, este metodo retorna todas as infos necessarias para apresentação da home
     * como por exemplo: Ultimos lançamentos, Mais vistos e etc...
     * @access public
     * @return mixed
     */
    public function getConteudo()
    {
        $db = new Db();
        $campos = ['id_anime', 'titulo_ingles', 'data_criacao', 'poster'];
        $aUltimosAnimes = $db->listar($campos, 'anime', 'data_criacao desc', 4);
        $aUltimosAnimes = $this->prepararInfosConteudo($aUltimosAnimes, 'anime');
        $campos = ['id_manga', 'titulo_ingles', 'data_criacao', 'poster'];
        $aUltimosManga = $db->listar($campos, 'manga', 'data_criacao desc', 8);
        $aUltimosManga = $this->prepararInfosConteudo($aUltimosManga, 'manga');
        $campos = ['id_volume', 'nome', 'data_criacao'];
        $aUltimosHq_volumes = $db->listar($campos, 'hq_volume', 'data_criacao desc', 4);
        $aUltimosHq_volumes = $this->prepararInfosConteudo($aUltimosHq_volumes, 'hq_volume');
        $campos = ['tipo_conteudo' => 'a', 'visualizacao', 'id_conteudo'];
        $infosRanking = $db->listar($campos, 'visualizacao_conteudo', 'visualizacao desc', 5);
        $rankingAnime = [];
        foreach ($infosRanking as $item){
            $infosAnime = Db::findByPk([$item['id_conteudo']], 'anime', ['poster', 'titulo_canonico']);
            $rankingAnime[] = [
                'id_anime' => $item['id_conteudo'],
                'poster' => $infosAnime['poster'],
                'titulo' => $infosAnime['titulo_canonico'],
                'sobre' => $item['visualizacao'] . ' views,' . '0 eps, 0 assinaturas',
            ];
        }
        return [
            'ultimos_adicionandos' => [
                'anime' => $aUltimosAnimes,
                'manga' => $aUltimosManga,
                'hq_volume' => $aUltimosHq_volumes
            ],
            'ranking' => [
                'anime' => $rankingAnime,
                'manga' => 0
            ]
        ];
    }

    private function prepararInfosConteudo(array $aConteudos, $tipoConteudo){
        $aConteudosPreparados = [];
        for ($i = 0; $i < count($aConteudos); $i++){
            $aConteudosPreparados[$i] = [];
            switch ($tipoConteudo){
                case 'anime':
                case 'manga':
                    if (strlen($aConteudos[$i]['titulo_ingles']) > 20){
                        $aConteudosPreparados[$i]['titulo'] = substr($aConteudos[$i]['titulo_ingles'], 0, 17) . '...';
                    }else{
                        $aConteudosPreparados[$i]['titulo'] = $aConteudos[$i]['titulo_ingles'];
                    }
                    $aConteudosPreparados[$i]['adicionado'] = $this->formataDataCriacao($aConteudos[$i]['data_criacao']);
                    $resultSet = 0;
                    if (isset($aConteudos[$i]['id_anime'])){
                        $aConteudosPreparados[$i]['id_conteudo'] = $aConteudos[$i]['id_anime'];
                        $resultSet = Db::findByPk([[$aConteudos[$i]['id_anime']], ['a']], 'visualizacao_conteudo');
                    }else if ($aConteudos[$i]['id_manga']){
                        $aConteudosPreparados[$i]['id_conteudo'] = $aConteudos[$i]['id_manga'];
                        $resultSet = Db::findByPk([[$aConteudos[$i]['id_manga']], ['m']], 'visualizacao_conteudo');
                    }
                    $aConteudosPreparados[$i]['views'] = isset($resultSet['visualizacao']) ? $resultSet['visualizacao'] : 0;
                    $aConteudosPreparados[$i]['poster'] = $aConteudos[$i]['poster'];
                    break;
                case 'hq_volume':
                    if (strlen($aConteudos[$i]['nome']) > 20){
                        $aConteudosPreparados[$i]['titulo'] = substr($aConteudos[$i]['nome'], 0, 17) . '...';
                    }else{
                        $aConteudosPreparados[$i]['titulo'] = $aConteudos[$i]['nome'];
                    }
                    $aConteudosPreparados[$i]['adicionado'] = $this->formataDataCriacao($aConteudos[$i]['data_criacao']);
                    $aConteudosPreparados[$i]['id_conteudo'] = $aConteudos[$i]['id_volume'];
                    $resultSet = Db::findByPk([[$aConteudos[$i]['id_volume']], ['h']], 'visualizacao_conteudo');
                    $aConteudosPreparados[$i]['views'] = isset($resultSet['visualizacao']) ? $resultSet['visualizacao'] : 0;
                    $registro = Db::listar(['imagem', 'id_volume' => $aConteudos[$i]['id_volume'], 'escala' => 'medium_url'], 'hq_volume_imagem');
                    $aConteudosPreparados[$i]['poster'] = $registro[0]['imagem'];
            }
        }
        return $aConteudosPreparados;
    }

    private function formataDataCriacao($dataCriacao){
        $data1 = new \DateTime(date('Y-m-d H:i:s'));
        $data2 = new \DateTime($dataCriacao);
        $diff = $data2->diff($data1);
        if ($diff->m){
            return 'em ' . date('d/m/Y', strtotime($dataCriacao));
        }else if ($diff->d){
            return 'há ' . $diff->d . ' dias';
        }else if ($diff->h){
            return 'há ' . $diff->h . ' horas';
        }else if ($diff->i){
            return 'há ' . $diff->i . ' minutos';
        }else if ($diff->s){
            return 'há ' . $diff->s . ' segundos';
        }else {
            return ' agora mesmo';
        }
    }
}