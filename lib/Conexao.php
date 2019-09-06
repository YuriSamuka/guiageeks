<?php

/**
 * @author : Yuri Samuel
 * @since : 29/01/2017 01:25
 * @version: 1.0
 *
 */

namespace Guiageeks\lib;


class Conexao
{

    private function __construct() {
    }

    /**
     * @param $dbname : nome do banco
     * @param $user : usuário do banco
     * @param $password : senha do banco
     * @param $host : endereço do servidor ou host name
     * @param $charset : encoding
     * @return PDO Uma instancia pdo conectada ao banco
     * @throws Exception
     */
    public static function novaConexao($dbname, $user, $password, $host, $charset) {
        try {
            $opcoes = [\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8', \PDO::ATTR_PERSISTENT => true];
            $pdo = new \PDO('mysql:host=' . $host . '; dbname=' . $dbname . '; charset=' . $charset . ';', $user, $password, $opcoes);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }catch (\PDOException $e){
            throw new \Exception('Erro ao se conectar ao banco: ' . $e->getMessage());
        }
    }
}