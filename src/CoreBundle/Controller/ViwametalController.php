<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\CallOfOffer;
use CoreBundle\Entity\Provider;
use CoreBundle\Form\CallOfOfferType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViwametalController extends Controller
{
    public function indexAction()
    {
        $propositions = $this->listPropositions();
        return $this->render('@Core/Display/Viwametal/CallOfOffer/CallOfOffer.html.twig', [
            'list' => $this->listCallsOfOffer(),
            'title' => "Appels d'offre",
            'propositions' => $propositions
        ]);
    }

    public function acceptAction(Request $request)
    {
        $idProp = $request->get('id');
        return new Response("accepter");
    }

    public function refuseAction(Request $request)
    {
        $idProp = $request->get('id');
        return new Response("refus");
    }

    public function seeAction(Request $request)
    {
        $idProp = $request->get('id');
        $tag = $request->get('tag');
        $propositions = $this->listPropositions();
        $providers = $this->listProviders();

        //$repProposition = $this->getDoctrine()->getManager()->getRepository("CoreBundle:Proposition");
       // $prop = $repProposition->getByUser($idProvider);
        return $this->render('@Core/Display/Viwametal/Propositions/SeePropositions.html.twig', [
            'title' => $tag,
            'propositions' => $propositions,
            'idProp' => $idProp,
            'providers' => $providers
        ]);
    }


    public function addAction(Request $request)
    {
        $coo = new CallOfOffer();
        $form = $this->createForm(CallOfOfferType::class, $coo)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $serviceQueries = $this->get('corebundle.servicesqlqueries');
            $serviceQueries->add($coo);
            $coo = new CallOfOffer();
            $form = $this->createForm(CallOfOfferType::class, $coo);
            return $this->redirectToRoute('vm_user_index');
        }

        return $this->render('@Core/Display/Viwametal/NewCallOfOffer/NewCallOfOffer.html.twig', [
            'form' => $form->createView(),
            'title' => "Faire un appel d'offre",
        ]);
    }

    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $serviceQueries = $this->get('corebundle.servicesqlqueries');
        $serviceQueries->delete($id, 'CallOfOffer');
        return $this->redirectToRoute('vm_user_index');
    }


    public function addProviderAction(Request $request)
    {

        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
        $discriminator->setClass('CoreBundle\Entity\Provider');

        $userManager = $this->container->get('pugx_user_manager');
        $provider = new Provider();

        $form = $this->createFormBuilder($provider)
            ->add('username', TextType::class,
                [
                    "required" => true,
                    "label" => "Nom d'utilisateur"
                ])
            ->add('password', TextType::class, [
                "required" => true,
                "label" => "Mot de passe"
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $provider = $userManager->createUser();

            $provider->setUsername($request->get('form')["username"]);
            $provider->setPlainPassword($request->get('form')["password"]);
            $provider->setEnabled(true);

            $userManager->updateUser($provider, true);

            return $this->redirectToRoute('vm_user_index');
        }
        return $this->render('@Core/Security/newProvider.html.twig', [
            "form" => $form->createView(),
            "title" => "Ajout d'un fournisseur"
        ]);

    }

    public function listCallsOfOffer()
    {
        $serviceQueries = $this->get('corebundle.servicesqlqueries');
        $listing = $serviceQueries->listAll('CallOfOffer');
        return $listing;
    }

    public function listPropositions()
    {
        $serviceQueries = $this->get('corebundle.servicesqlqueries');
        $propositions = $serviceQueries->listAll('Proposition');
        return $propositions;
    }
    public function listProviders()
    {
        $serviceQueries = $this->get('corebundle.servicesqlqueries');
        $providers = $serviceQueries->listAll('Provider');
        return $providers;
    }
}
