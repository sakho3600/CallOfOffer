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


    public function createViwaUserAction()
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

    public function reinitAction()
    {
        $serviceQueries = $this->get('corebundle.servicesqlqueries');

        $test = $serviceQueries->truncate('call_of_offer');
        $test = $serviceQueries->truncate('proposition');
        $test = $serviceQueries->truncate('provider');
        $test = $serviceQueries->truncate('user');
        return new Response("Réinitialisation des tables : ok!");

    }

    public function testAction()
    {
        $rep = $this->getDoctrine()->getManager()->getRepository("CoreBundle:Proposition");

        $propositions = $rep->getAllPropositionByProviderId(2);

        return $this->render("@Core/Test/test.html.twig", [
            'test1' => $propositions,
            'test2' => 'test'

        ]);
    }

    public function listPropositions($idProvider)
    {
        $rep = $this->getDoctrine()->getManager()->getRepository("CoreBundle:Proposition");

        $propositions = $rep->getAllPropositionByProviderId($idProvider);
        return $propositions;
    }


    public function listCallsOfOffer($inProgress)
    {
        $rep = $this->getDoctrine()->getManager()->getRepository("CoreBundle:CallOfOffer");
        $listing = $rep->getAllCooInProgress($inProgress);
        return $listing;
    }
}