<?php


namespace MeloFlavio\NotificacaoBundle\Controller;


use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\WebLink\Link;

class DiscoverController extends  AbstractController
{

    public function notificacaoDicoverAction(Request $request)
    {
        $username = $this->getUser()->getUsername();

        $hubUrl = $this->container->getParameter('mercure.default_hub');

        $this->addLink($request,new Link('mercure',$hubUrl));

        $response = $this->json('done');

        $token = (new Builder())
            ->withClaim('mercure', ['subscribe' => [sprintf("/%s", $username)]])
            ->getToken(
                new Sha256(),
                new Key($this->container->getParameter('mercure_secret_key'))
            )
        ;

        $response->headers->setCookie(
            new Cookie(
                'mercureAuthorization',
                $token,
                (new \DateTime())
                    ->add(new \DateInterval('PT2H')),
                '/.well-known/mercure',
                null,
                false,
                true,
                false,
                'strict'
            )
        );


        return $response;
    }


}