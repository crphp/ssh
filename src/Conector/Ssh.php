<?php

/**
 * Essa classe fornece uma interface de conexão SSH com máquinas Linux, Unix e 
 * qualquer outro sistema que suporte conexões SSH. Possibilitando dessa forma a 
 * execução de comandos remotos de forma rápida, eficiente e segura
 * 
 * @package     crphp
 * @subpackage  ssh
 * @author      Fábio J L Ferreira <contato@fabiojanio.com>
 * @license     MIT (consulte o arquivo "license" disponibilizado com este pacote)
 * @copyright   (c) 2016, Fábio J L Ferreira
 */

namespace Crphp\Ssh\Conector;

use \Exception;
use \RuntimeException;
use phpseclib\Net\SSH2;
use Crphp\Core\Sistema\Conector;

class Ssh extends Conector
{
    /**
     * Estabelece conexão via SSH
     * 
     * @param   string  $host
     * @param   string  $usuario
     * @param   string  $senha
     * @param   int     $porta
     * @param   int     $timeout
     * @return  null
     */
    public function conectar($host, $usuario = null, $senha = null, $porta = 22, $timeout = 10)
    {
        try {
            $this->conexao = new SSH2($host, $porta, $timeout);
            if (!$this->conexao->login($usuario, $senha)) {
                throw new Exception("O login falhou!");
            }
            
        } catch (Exception $e) {
            $this->conexao = false;
            $this->mensagemErro = $e->getMessage();
        }
    }

    /**
     * Executa a instrução remotamente
     * 
     * @param   string         $instrucao
     * @return  object|string  em caso de erro retorna uma string
     */
    public function executar($instrucao)
    {
        try {
            if (!$this->conexao) {
                throw new RuntimeException("Antes de executar uma instrução é necessário instanciar uma conexão!");
            }

            // @see http://php.net/manual/en/ref.com.php
            if (!$retorno = $this->conexao->exec($instrucao)) {
                throw new RuntimeException("O host remoto não retornou dados!");
            }

            return $retorno;
        } catch (RuntimeException $e) {
            return $e->getMessage();
        }
    }
}