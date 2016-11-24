<?php

/** 
 * Classe utilizada para recuperar informações referentes a memória física da
 * máquina
 * 
 * @package     crphp
 * @subpackage  ssh
 * @author      Fábio J L Ferreira <contato@fabiojanio.com>
 * @license     MIT (consulte o arquivo license disponibilizado com este pacote)
 * @copyright   (c) 2016, Fábio J L Ferreira
 */

namespace Crphp\Ssh\Sistema;

use Crphp\Core\Sistema\Conector;
use Crphp\Ssh\Auxiliares\Transformar;
use Crphp\Core\Interfaces\Sistema\MemoriaRamInterface;

class MemoriaRam implements MemoriaRamInterface
{
    /**
     * Valor bruto
     *
     * @var object 
     */
    private $memoria;
        
    /**
     * Consulta as informações referentes a memória física reconhecida(s) pelo host remoto
     * 
     * @param   \Crphp\Ssh\Conectores\Conector $conexao
     * @return  null
     */
    public function __construct(Conector $conexao)
    {
        $meminfo = $conexao->executar('cat /proc/meminfo');
        
        $arr = explode("\n", $meminfo);
        
        foreach($arr as $item) {
            if($item) {
                $itemValor = explode(':', $item);
                $this->memoria[$itemValor[0]] = trim($itemValor[1]);
            }
        }
    }
    
    /**
     * Retorna o total de memória física livre e o percentual que este total representa
     * 
     * @param boolean $emKilobyte
     * @return array
     */
    public function memoriaLivre($emKilobyte = false)
    {
        $memFree = $this->memoria['MemFree'];
        
        $livre = ($emKilobyte) ? $memFree : Transformar::converterKilobyte($memFree);
        
        return [
            'livre' => $livre,
            'percentualLivre' => sprintf("%0.2f%%", (100 * $livre / $this->memoriaTotal($emKilobyte)))
        ];
    }
    
    /**
     * Retorna o total de memória física utilizada e o percentual que este total representa
     * 
     * @return array
     */
    public function memoriaUtilizada()
    {
        $livre = $this->memoriaLivre(true);
        $total = $this->memoriaTotal(true);

        return [
                    'utilizado' => Transformar::converterKilobyte($total - $livre['livre']),
                    'percentualUtilizado' => sprintf("%0.2f%%", (($total - $livre['livre']) * 100 / $total))
               ];
    }

    /**
     * Retorna o total de memória física
     * 
     * @param boolean $emKilobyte
     * @return string
     */
    public function memoriaTotal($emKilobyte = false)
    {
        $menTotal = $this->memoria['MemTotal'];
                
        return ($emKilobyte) ? $menTotal : Transformar::converterKilobyte($menTotal);
    }
    
    /**
     * Retorna a quantidade de memória swap livre
     * 
     * @param boolean $emKilobyte
     * @return array
     */
    public function swapLivre($emKilobyte = false)
    {
        $memFree = $this->memoria['SwapFree'];
        
        $livre = ($emKilobyte) ? $memFree : Transformar::converterKilobyte($memFree);
        
        return [
            'livre' => $livre,
            'percentualLivre' => sprintf("%0.2f%%", (100 * $livre / $this->swapTotal($emKilobyte)))
        ];
    }
    
    /**
     * Retorna a quantidade de memória swap em uso
     * 
     * @return array
     */
    public function swapUtilizada()
    {
        $livre = $this->swapLivre(true);
        $total = $this->swapTotal(true);

        return [
                    'utilizada' => Transformar::converterKilobyte($total - $livre['livre']),
                    'percentualUtilizado' => sprintf("%0.2f%%", (($total - $livre['livre']) * 100 / $total))
               ];
    }
    
    /**
     * Retorna o total de memória swap
     * 
     * @param boolean $emKilobyte
     * @return string
     */
    public function swapTotal($emKilobyte = false)
    {
        $swapTotal = $this->memoria['SwapTotal'];
              
        return ($emKilobyte) ? $swapTotal : Transformar::converterKilobyte($swapTotal);
    }

    /**
     * Retorna uma visão geral referente a memória física
     * 
     * @return array
     */
    public function detalhes()
    {
        $livre = $this->memoriaLivre();
        $utilizada = $this->memoriaUtilizada();
        $swapLivre = $this->swapLivre();
        $swapUtilizada = $this->swapUtilizada();
        
        return [
                    'livre' => $livre['livre'],
                    'percentualLivre' => $livre['percentualLivre'],
                    'utilizado' => $utilizada['utilizado'],
                    'percentualUtilizado' => $utilizada['percentualUtilizado'],
                    'memoriaTotal' => $this->memoriaTotal(),
                    'swapLivre' => $swapLivre['livre'],
                    'swapUtilizada' => $swapUtilizada['utilizada'],
                    'percentualSwapLivre' => $swapLivre['percentualLivre'],
                    'percentualSwapUtilizada' => $swapUtilizada['percentualUtilizado'],
                    'swapTotal' => $this->swapTotal()
               ];
    }
    
    /**
     * Retorna o dado bruto. Uso recomendado principalmente quando os demais métodos 
     * desta classe não conseguem realizar a transformação dos dados retornados do 
     * servidor remoto
     * 
     * @return string
     */
    public function pre()
    {
        return "<pre>{$this->memoria}</pre>";
    }
}