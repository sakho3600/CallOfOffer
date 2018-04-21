<?php
/**
 * Created by PhpStorm.
 * User: Phil
 * Date: 26/01/2018
 * Time: 12:21
 */

namespace CoreBundle\Controller;

use CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class TestController extends Controller
{


    public function synchroViwaUserAction()
    {
        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('CoreBundle\Entity\ViwaUser');

        $userManager = $this->container->get('pugx_user_manager');

        $viwaUser = $userManager->createUser();

        $viwaUser->setUsername('viwametal');
        $viwaUser->setPlainPassword('viwametal');
        $viwaUser->setEnabled(true);

        $userManager->updateUser($viwaUser, true);

        return new Response('Utilisateur Viwametal créé');
    }

    public function synchroProvider1Action()
    {
        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('CoreBundle\Entity\Provider');

        $userManager = $this->container->get('pugx_user_manager');

        $provider = $userManager->createUser();

        $provider->setUsername('fournisseur1');
        $provider->setPlainPassword('fournisseur1');
        $provider->setEnabled(true);

        $userManager->updateUser($provider, true);

        return new Response('Utilisateur Fournisseur créé');
    }

    public function synchroProvider2Action()
    {
        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('CoreBundle\Entity\Provider');

        $userManager = $this->container->get('pugx_user_manager');

        $provider = $userManager->createUser();

        $provider->setUsername('fournisseur2');
        $provider->setPlainPassword('fournisseur2');
        $provider->setEnabled(true);

        $userManager->updateUser($provider, true);

        return new Response('Utilisateur Fournisseur créé');

    }

    public function reinitAction()
    {
        $serviceQueries = $this->get('corebundle.servicesqlqueries');

        $test = $serviceQueries->truncate('call_of_offer');
        $test = $serviceQueries->truncate('proposition');
        $test = $serviceQueries->truncate('provider');
        $test = $serviceQueries->truncate('user');
        return new Response("Réinitialisation des tables : ok!");

    }

}