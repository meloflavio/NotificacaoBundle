<?php


namespace MeloFlavio\NotificacaoBundle\Twig;

use Psr\Container\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class CheckNotificacaoExtension extends  AbstractExtension
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('notificacao_render', [$this, 'renderNotificacao'], ['is_safe' => ['html']]),
            new TwigFunction('notificacao_basica_render', [$this, 'renderNotificacaoBasica'], ['is_safe' => ['html']]),
            new TwigFunction('notificacao_para_min_render', [$this, 'renderMinhaNotificacao'], ['is_safe' => ['html']]),
        ];
    }

    public function renderNotificacaoBasica(string $topic, string $elementoAlvo){
        return  $this->createHtmlNotify($topic);
    }
    public function renderNotificacao(string $topic, string $elementoAlvo){
        return  $this->createHtmlDiv($topic,$elementoAlvo);

    }public function renderMinhaNotificacao(string $username){
        return  $this->createHtmlNotify('/global/'.$username);
    }

    public  function createHtmlNotify( $topic){
        return '
        <script>
        document.addEventListener(\'DOMContentLoaded\',function () {
            function addMessage(data){
                  new PNotify({
                      title: data.title,
                      text: data.text
                    });
            }
            fetch("'.$this->getPath('meloflavio_notificacao_discover').'").then(result => {
                const hubUrl = result.headers.get(\'Link\').match(/<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/)[1];

                const url  = new URL(hubUrl);
                url.searchParams.append(\'topic\',\''.$topic.'\')
                const eventSource = new EventSource(url,{
                    withCredentials: true
                });
                eventSource.onmessage = (event) => {
                    var data = JSON.parse(event.data);
                    console.log(data);
                    addMessage(data);
                };
            });
        })

    </script>
        ';
    }
    public  function createHtmlDiv( $topic, $alvo = '#message-board'){
        return '
        <script>
        document.addEventListener(\'DOMContentLoaded\',function () {
            function addMessage(data){
                 let html = "<div " +
                     "                <div " +
                     "                    <span><strong class=\"autor\">"+data.createdBy.username+"</strong> fez um comentário</span>\n" +
                     "                </div>\n" +
                     "                <div class=\"message-content\">\n"+data.texto+
                     "                </div>\n" +
                     "       </div>";
                document.querySelector("'. $alvo.'").firstElementChild.insertAdjacentHTML("afterend",html);
            }
            fetch("'.$this->getPath('meloflavio_notificacao_discover').'").then(result => {
                const hubUrl = result.headers.get(\'Link\').match(/<([^>]+)>;\s+rel=(?:mercure|"[^"]*mercure[^"]*")/)[1];

                const url  = new URL(hubUrl);
                url.searchParams.append(\'topic\',\''.$topic.'\')
                const eventSource = new EventSource(url,{
                    withCredentials: true
                });
                eventSource.onmessage = (event) => {
                    var data = JSON.parse(event.data);
                    console.log(data);
                    addMessage(data);
                };
            });
        })

    </script>
        ';
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getPath($name)
    {
        return $this->container->get('router')->generate($name);
    }


}