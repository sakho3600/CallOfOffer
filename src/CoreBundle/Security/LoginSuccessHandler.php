<?php
/**
 * Created by PhpStorm.
 * User: Phil
 * Date: 10/04/2018
 * Time: 19:01
 */

namespace CoreBundle\Security;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface {

    protected $router;
    protected $authorizationChecker;

    public function __construct(Router $router, AuthorizationChecker $authorizationChecker) {
        $this->router = $router;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {

        $response = null;

        if ($this->authorizationChecker->isGranted('ROLE_VIWAMETAL')) {
            $response = new RedirectResponse($this->router->generate('vm_user_index'));
        } else if ($this->authorizationChecker->isGranted('ROLE_PROVIDER')) {
            $response = new RedirectResponse($this->router->generate('provider_coo_index'));
        }

        return $response;
    }

}