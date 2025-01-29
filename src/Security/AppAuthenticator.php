<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    private RouterInterface $router;
    private Security $security;

    public function __construct(RouterInterface $router, Security $security)
    {
        $this->router = $router;
        $this->security = $security;
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }
    
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        if (empty($email) || empty($password)) {
            throw new AuthenticationException('Les champs email et mot de passe sont requis.');
        }

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $this->security->getUser();

        // Vérification des rôles
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('admin_dashboard'));
        } elseif (in_array('ROLE_VETERINAIRE', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('veterinaire_dashboard'));
        } elseif (in_array('ROLE_EMPLOYE', $user->getRoles())) {
            return new RedirectResponse($this->router->generate('employe_dashboard'));
        }

        // Gestion des utilisateurs avec des rôles non valides
        $session = $request->getSession();

        // Vérification que la session implémente FlashBagInterface
        if ($session instanceof FlashBagInterface) {
            $session->add('error', 'Accès non autorisé.');
        }

        return new RedirectResponse($this->router->generate('app_login')); // Redirection vers le login
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $session = $request->getSession();

        // Vérification que la session implémente FlashBagInterface
        if ($session instanceof FlashBagInterface) {
            $session->add('error', 'Identifiants incorrects.');
        }

        return new RedirectResponse($this->getLoginUrl($request));
    }
}
