<?php
/**
 * Created by PhpStorm.
 * User: Yuri
 * Date: 31/01/2018
 * Time: 00:47
 */

namespace Guiageeks\lib;

//define('HOST', 'guiageeks.mysql.dbaas.com.br');
//define('DBNAME', 'guiageeks');
//define('CHARSET', 'utf8');
//define('USER', 'guiageeks');
//define('PASSWORD', 'b5iNR432qnrx!#');

define('HOST', 'localhost');
define('DBNAME', 'guiageeks');
define('CHARSET', 'utf8');
define('USER', 'root');
define('PASSWORD', '');

use \Guiageeks\lib\Conexao as Conexao;

class Db
{
    /**
     * Retorna uma instancia PDO conectada ao banco
     * @return PDO
     */
    public static function getConexao(){
        return Conexao::novaConexao(DBNAME, USER, PASSWORD, HOST, CHARSET);
    }
    /**
     * @param $aCamposValores[] - array com os valores para inserção e os nomes de seus respectivos campos no Banco de dados
     * @param $nomeTabela - string com nome da tabela
     */
    public static function insert($aCamposValores, $nomeTabela){
        try{
            $aNomeCampos = array_keys($aCamposValores);
            $conn = Conexao::novaConexao(DBNAME, USER, PASSWORD, HOST, CHARSET);
            $stm = $conn->prepare('INSERT INTO '. $nomeTabela . ' (' . implode(', ' , $aNomeCampos). ') VALUES ( :'. implode(', :' , $aNomeCampos) . ')');
            $aAutoBind = [];
            foreach ($aCamposValores as $campos => $valores){
                $aAutoBind[':' . $campos] = $valores;
            }
            $stm->execute($aAutoBind);
            $stm = null;
            $conn = null;
        }catch (\PDOException $e){
            throw  new \Exception('Erro ao tentar realizar insert no banco: ' . $e->getMessage());
        }
    }

    /**
     * @param $aCamposValores - Array associativo com nome dos campos nas keys e o seus respectivos valores em value Ex: array([campo] => valor)
     * @param $nomeTabela
     * @param $aIds - array de ids
     */
    public static function update($aCamposValores, $nomeTabela, $aPks){
        if (is_array($aCamposValores) && $nomeTabela && $aPks){
            try{
                $conn = Conexao::novaConexao(DBNAME, USER, PASSWORD, HOST, CHARSET);
                $aNomeCampos = array_keys($aCamposValores);
                $aValores = $aCamposValores;
                $campos = implode('=?, ', $aNomeCampos) . '=?';
                $bRegistroExiste = !empty(self::findByPk($aPks, $nomeTabela));
                if ($bRegistroExiste){
                    $sql = '';
                    $sql .= 'UPDATE ' . $nomeTabela . ' SET ' . $campos . ' WHERE ';
                    $nomePk = self::getNomePrimaryKey($nomeTabela);
                    $placeholders = array_fill(0, count($aPks), '?');
                    if (count($nomePk) > 1){
                        if (count($nomePk) == count($aPks)){
                            for ($i = 0; $i < count($nomePk); $i++) {
                                $placeholders = array_fill(0, count($aPks[$i]), '?');
                                $sql .= $nomePk[$i] . ' in(' . implode(', ', $placeholders) . ') ';
                                $sql .= ($i + 2) > count($nomePk) ? '' : ' and ';
                            }
                        }else{
                            throw  new \Exception('Parametro $aPks não possui a mesma quaintidade primary key da tabela solicitada.');
                        }
                    }else{
                        $sql .=     implode('', $nomePk) . ' in(?) ';
                    }
                    $stm = $conn->prepare($sql);
                    $i = 1;
                    foreach ($aValores as $valor){
                        $stm->bindValue($i, $valor);
                        $i++;
                    }
                    foreach ($aPks as $key => $pk){
                        if (is_array($pk)){
                            foreach ($pk as $item){
                                $aPks[] = $item;
                            }
                            unset($aPks[$key]);
                        }
                    }
                    $aPks = array_values($aPks);
                    foreach ($aPks as $Pk){
                        if (is_int($Pk)){
                            $stm->bindValue($i, $Pk, \PDO::PARAM_INT);
                        }else{
                            $stm->bindValue($i, $Pk);
                        }
                        $i++;
                    }
                    $stm->execute();
                    $stm = null;
                    $conn = null;
                }
            }catch (\PDOException $e){
                throw  new \Exception('Erro ao tetanr realizar update no banco: ' . $e->getMessage());
            }
        } else {
            throw new \Exception('Parametros passados para o metodo Db::update() invalidos');
        }
    }
    /**
     * @param $aPks - array de Pks
     * @param $nomeTabela
     */
    public static function delete($aPks, $nomeTabela){
        if ($aPks){
            try{
                $conn = Conexao::novaConexao(DBNAME, USER, PASSWORD, HOST, CHARSET);
                $placeholders = array_fill(0, count($aPks), '?');
                $stm = $conn->prepare('DELETE FROM ' . $nomeTabela . ' WHERE ' . self::getNomePrimaryKey($nomeTabela) . ' in (' . implode(', ', $placeholders) . ') ');
                $i = 1;
                foreach ($aPks as $Pk){
                    $stm->bindValue($i, $Pk, PDO::PARAM_INT);
                    $i++;
                }
                $stm->execute();
                $stm = null;
                $conn = null;
            }catch (PDOException $e){
                throw  new \Exception('Erro ao tetanr realizar delete no banco: ' . $e->getMessage());
            }
        } else {
            throw  new \Exception('Prametros passados para o metodo Db::delete() invalidos');
        }
    }

    /**
     * Função de busca por primary key (pk). Quando passado um array com os Pks que deseja buscar
     * e o nome da tabela, a função retorna um array com os registro encontrados
     *
     * @param $aPks
     * @param $nomeTabela
     * @param string $aRetornoCampos
     * @return mixed
     */
    public static function findByPk($aPks, $nomeTabela, $aRetornoCampos = '*'){
        if ($aPks && $nomeTabela){
            if (is_array($aRetornoCampos)){
                $aRetornoCampos = implode(', ', $aRetornoCampos);
            }
            if ($aPks && $nomeTabela){
                try{
                    $conn = Conexao::novaConexao(DBNAME, USER, PASSWORD, HOST, CHARSET);
                    $sql =  'SELECT ';
                    $sql .=     $aRetornoCampos;
                    $sql .= ' FROM ';
                    $sql .=     $nomeTabela;
                    $sql .= ' WHERE ';
                    $nomePk = self::getNomePrimaryKey($nomeTabela);
                    if (count($nomePk) > 1){
                        if (count($nomePk) == count($aPks)){
                            for ($i = 0; $i < count($nomePk); $i++) {
                                if (is_string($aPks[$i][0])) {
                                    $sql .= $nomePk[$i] . ' in(\'' . implode('\', ', $aPks[$i]) . '\') ';
                                } else {
                                    $sql .= $nomePk[$i] . ' in(' . implode(', ', $aPks[$i]) . ') ';
                                }
                                $sql .= ($i + 2) > count($nomePk) ? '' : ' and ';
                            }
                        }else{
                            throw  new \Exception('Parametro $aPks não possui a mesma quaintidade de primary key da tabela solicitada.');
                        }
                    }else{
                        $sql .=     implode('', $nomePk) . ' in(' . implode(', ', $aPks) . ') ';
                    }
                    $stm = $conn->prepare($sql);
                    $stm->execute();
                    $conn = null;
                    $resultset = $stm->fetchAll(\PDO::FETCH_ASSOC);
//                    var_dump($resultset);
                    if (count($resultset) > 1){
                        return $resultset;
                    }
                    $resultset = isset($resultset[0]) ? $resultset[0] : null;
                    return $resultset;
                }catch (\PDOException $e){
                    throw  new \Exception('Erro ao tetanr realizar busca no banco: ' . $e->getMessage());
                }
            }
        } else{
            throw  new \Exception('Prametros passados para o metodo Db::findByPk() invalidos');
        }
    }

    /**
     * Metodo lista registros do banco de dados de acordo com filtros passados em $aFiltros, $aFiltros é um array
     * associativo onde a key é o campo que deseja filtrar e o value são os filtros. Se caso um filtro passado para
     * um determinado campo for uma strig vazia ou null, o metodo retorna os registros onde este determinando campo
     * é nulo. Se em a $aFiltros for passado somente os nomes dos campos nos valores sem especificar os filtros, o
     * metodo retorna esse campo na consulta mas não filtra pelo mesmo.
     *
     * @param array $aFiltros
     * @param string $aRetornoCampos
     * @param int $limit
     * @return array mixed
     */
    public static function listar($aFiltros, $nomeTabela, $orderby = null, $limit = null, $offset = null){
        if (is_array($aFiltros) && $nomeTabela){
            try{
                $conn = Conexao::novaConexao(DBNAME, USER, PASSWORD, HOST, CHARSET);
                $aNomeCampos = array_keys($aFiltros);
                foreach ($aFiltros as $campo => $filtro){
                    if (is_int($campo)){
                        unset($aFiltros[$campo]);
                        $aNomeCampos[$campo] = $filtro;
                    }else{
                        $aNomeCampos[] = $campo;
                    }
                }
                foreach ($aNomeCampos as $key => $nomeCampo){
                    if (is_int($nomeCampo)){
                        unset($aNomeCampos[$key]);
                    }
                }
                $sql =  ' SELECT ';
                $sql .=     implode(', ' ,$aNomeCampos);
                $sql .= ' FROM ';
                $sql .=     $nomeTabela . ' ';
                $where = '';
                foreach ($aFiltros as $campo => $filtro) {
                    $where .= ($where) ? ' AND ' : '';
                    $where .= $campo . ($filtro ? ' LIKE :' . $campo . ' ' : 'is null');
                }
                $sql .= ($where) ? ' WHERE ' . $where: '';
                if ($orderby){
                    $sql .= ' order by ' . (is_array($orderby) ? implode(', ', $orderby) : $orderby) . ' ';
                }
                if ($limit){
                    $sql .= ' limit ' . $limit;
                }
                if ($offset){
                    $sql .= ' offset ' . $offset;
                }
                $stm = $conn->prepare($sql);
                $stm->execute($aFiltros);
                $conn = null;
                return $stm->fetchAll(\PDO::FETCH_ASSOC);
            }catch (\PDOException $e){
                throw  new \Exception('Erro ao tetanr realizar busca no banco: ' . $e->getMessage());
            }
        } else{
            throw  new \Exception('Prametros passados para o metodo Db::listar() invalidos');
        }
    }

    /**
     * Função obtem informações da tabela indicada em $nomeTabela
     *
     * @param null $nomeTabela
     * @return mixed
     */
    public static function getInfoTabela($nomeTabela){
        if ($nomeTabela){
            try{
                $conn = Conexao::novaConexao(DBNAME, USER, PASSWORD, HOST, CHARSET);
                $stm = $conn->prepare('SHOW COLUMNS FROM ' . $nomeTabela);
                $stm->execute();
                return $stm->fetchAll();
            }catch (PDOException $e){
                throw  new \Exception('Erro ao tetanr realizar obter informações da tabela ' . $nomeTabela . ' no banco: ' . $e->getMessage());
            }
        }else{
            throw  new \Exception('Prametros passados para o metodo Db::getInfoTabela() invalidos');
        }
    }

    /**
     * Função obtem o nome da PRIMARY KEY da tabela indicada em $nomeTabela
     *
     * @param null $nomeTabela
     * @return mixed
     */
    public static function getNomePrimaryKey($nomeTabela = null){
        if ($nomeTabela){
            try{
                $conn = Conexao::novaConexao(DBNAME, USER, PASSWORD, HOST, CHARSET);
                $stm = $conn->prepare('SHOW KEYS FROM ' . $nomeTabela . ' WHERE Key_name = \'PRIMARY\'');
                $stm->execute();
                $infosPks = $stm->fetchAll(\PDO::FETCH_ASSOC);
                $pks = [];
                foreach ($infosPks as $item){
                    if (!is_array($item)){
                        return $infosPks['Column_name'];
                    }else{
                        $pks[] = $item['Column_name'];
                    }
                }
                sort($pks);
                return $pks;
            }catch (\PDOException $e){
                throw  new \Exception('Erro ao tetanr realizar obter o nome da primary key da tabela ' . $nomeTabela . ' no banco: ' . $e->getMessage());
            }
        } else{
            throw  new \Exception('Prametros passados para o metodo Db::getNomePrimaryKey() invalidos');
        }
    }
}