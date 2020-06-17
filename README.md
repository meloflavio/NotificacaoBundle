NotificacaoBundle
================

Bundle de integração com symfony flex e mercure bundle para notificações.


Installation
-------------
####1. Composer Require 
 
        composer require meloflavio/notificacao-bundle  

####2. Adicionar variaveis de ambiente 
  
  ```env
  MERCURE_PUBLISH_URL=https://localhost/mercure/.well-known/mercure
  MERCURE_JWT_TOKEN=Token
  MERCURE_SECRET_KEY=CHANGEMEKey
  ```
  Token pode ser criado em https://jwt.io/
  
####3. Adicionar em notificacao.yaml caso deseje mudar a classe de usuario
Padrão esta App/UFT/UserBundle/Entity/Usuario

```yaml
meloflavio_notificacao:
  user:
    class: App/UFT/UserBundle/Entity/Usuario
 ```
####4. Adicionar em mercure.yaml 

```yaml
parameters:
   mercure_secret_key: '%env(MERCURE_SECRET_KEY)%'
   mercure_url: '%env(MERCURE_PUBLISH_URL)%'
   mercure_token: '%env(MERCURE_JWT_TOKEN)%'
mercure:
   enable_profiler: '%kernel.debug%'
   hubs:
       default:
           url: '%env(MERCURE_PUBLISH_URL)%'
           jwt: '%env(MERCURE_JWT_TOKEN)%'
```

  
####5.  Criar entidade de notificação 
```php
<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use MeloFlavio\NotificacaoBundle\Entity\NotificacaoBase;

/**
 * Class Base
 * @ORM\MappedSuperclass()
 */
abstract class Notificacao extends NotificacaoBase
{
    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    private $texto;

    
    ...
}
```
####6.  Exemplo de como utilizar
Adicione ao templete substituindo {{ settings.data_alvo.id }} pelo id da mensagem

```js
<script>
        document.addEventListener('DOMContentLoaded',function () {

            
            function addMessage(data){
               
                 let html = "<div " +
                     "                <div " +
                     "                    <span><strong class=\"autor\">"+data.createdBy.username+"</strong> fez um comentário</span>\n" +
                     "                </div>\n" +
                     "                <div class=\"message-content\">\n"+data.texto+
                     "                </div>\n" +
                "                </div>\n" +
                "            </div>";
                document.querySelector("#message-board").firstElementChild.insertAdjacentHTML("afterend",html);
            }
            fetch("{{ path('discover') }}").then(result => {
                const hubUrl = result.headers.get('Link').match(/<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/)[1];

                const url  = new URL(hubUrl);
                // let url = new URL("http://localhost:9090/.well-known/mercure");
                url.searchParams.append('topic', '/message_historia/{{ settings.data_alvo.id }}')
                const eventSource = new EventSource(url,{
                    withCredentials: true
                });
                eventSource.onmessage = (event) => {
                    var data = JSON.parse(event.data);
                    addMessage(data);
                };
            });
        })

    </script>
```