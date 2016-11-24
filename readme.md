# crphp/ssh
Está é uma biblioteca que faz uso do protocolo **SSH (Secure Shell)** para gerenciamento de máquinas remotas.

Está biblioteca segue os padrões descritos na [PSR-2](http://www.php-fig.org/psr/psr-2/), logo, 
isso implica que a mesma está em conformidade com a [PSR-1](http://www.php-fig.org/psr/psr-1/).

As palavras-chave "DEVE", "NÃO DEVE", "REQUER", "DEVERIA", "NÃO DEVERIA", "PODERIA", "NÃO PODERIA", 
"RECOMENDÁVEL", "PODE", e "OPCIONAL" neste documento devem ser interpretadas como descritas no 
[RFC 2119](http://tools.ietf.org/html/rfc2119). Tradução livre [RFC 2119 pt-br](http://rfc.pt.webiwg.org/rfc2119).

1. [Referências](#referencia)
1. [Funcionalidades](#funcionalidades)
1. [Requisitos (recomendados)](#requisitos)
1. [Baixando o pacote crphp/ssh para o servidor](#ssh)
1. [Exemplos de uso](#exemplos)
1. [Licença (MIT)](#licenca)

## 1 - <a id="referencias"></a>Referências
 - [PSR-1](http://www.php-fig.org/psr/psr-1/)
 - [PSR-2](http://www.php-fig.org/psr/psr-2/)
 - [RFC 2119](http://tools.ietf.org/html/rfc2119). Tradução livre [RFC 2119 pt-br](http://rfc.pt.webiwg.org/rfc2119)

## 2 - <a id="funcionalidades"></a>Funcionalidades
- [x] Consultar RAM
- [ ] Consultar CPU (em análise)
- [ ] Consultar Disco Rígido (em análise)
- [ ] Listar Serviços (em análise)
- [ ] Stop / Start de serviço (em análise)
- [ ] Listar processos (em análise)
- [ ] Matar / Finalizar processos (em análise)
- [ ] Lançar processos (em análise)
- [ ] Listar e matar sessões (em análise)

## 3 - <a id="preparando-o-servidor"></a>Requisitos
> :exclamation: Os requisitos sugeridos logo abaixo representam as versões utilizadas em nosso ambiente 
de desenvolvimento e produção, logo não garantimos que a solução aqui apresentada irá rodar integralmente 
caso as versões dos elementos abaixo sejam outras.

### 3.1 - <a id="requisitos"></a>Requisitos (recomendados)
Servidor
- REQUER Apache >= 2.4.10
- REQUER PHP >= 5.5.12

Cliente
- REQUER servidor SSH
- É RECOMENDÁVEL ativar as bibliotecas **mcrypt**, **gmp** ou **bcmath** 

## 4 - <a id="ssh"></a>Baixando o pacote crphp/wmi para o servidor

Para a etapa abaixo estou pressupondo que você tenha o composer instalado e saiba utilizá-lo:
```
composer require crphp/wmi
```

Ou se preferir criar um projeto:
```
composer create-project --prefer-dist crphp/wmi nome_projeto
```

Caso ainda não tenha o composer instalado, obtenha este em: https://getcomposer.org/download/

## 5 - <a id="exemplos"></a>Exemplos de uso

**Consultar Memória Ram**:
```php
use Crphp\Ssh\Conector\Ssh;
use Crphp\Ssh\Sistema\MemoriaRam;

$ssh = new Ssh;
$ssh->conectar('endereco_do_servidor', 'usuario', 'senha');

if($ssh->status()) {
    $ram = new MemoriaRam($ssh);
    echo "<pre>";
    print_r($ram->detalhes());
    echo "</pre>";
    
} else {
    echo $ssh->mensagemErro();
}
```

> Você DEVE sempre instânciar o conector Ssh e a classe referente ao elemento que deseja manipular.

**Também é possível executar suas próprias consultas customizadas**
```php
use Crphp\Ssh\Conector\Ssh;

$ssh = new Ssh;
$ssh->conectar('endereco_do_servidor', 'usuario', 'senha');
echo $ssh->executar('pwd');
```

## 6 - <a id="licenca">Licença (MIT)
Para maiores informações, leia o arquivo de licença disponibilizado junto desta biblioteca.