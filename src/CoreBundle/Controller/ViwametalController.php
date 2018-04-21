<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\CallOfOffer;
use CoreBundle\Entity\Provider;
use CoreBundle\Form\CallOfOfferType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ViwametalController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Core/Display/Viwametal/CallOfOffer/CallOfOffer.html.twig',[
            'list' => $this->listCallsOfOffer(),
            'title' => "Appels d'offre",
            'propositions' => $this->listPropositions()
        ]);
    }

    public function seeAction(Request $request)
    {
        $idProp = $request->get('id');
        $tag = $request->get('tag');

        return $this->render('@Core/Display/Viwametal/Propositions/SeePropositions.html.twig',[
            'title' => $tag,
            'propositions' => $this->listPropositions(),
            'idProp' => $idProp
        ]);
    }

    public function validateAction(Request $request)
    {
        $idProp = $request->get('id');

        return $this->redirectToRoute('vm_user_coo_see');
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
        }
        $listOfCallOfOffer = $this->listCallsOfOffer();
        return $this->render('@Core/Display/Viwametal/NewCallOfOffer/NewCallOfOffer.html.twig', [
            'form' => $form->createView(),
            'title' => "Faire un appel d'offre",
            'list' => $listOfCallOfOffer,
            'form1' => $form,
            'propositions' => $this->listPropositions()
        ]);
    }

    public function deleteAction(Request $request)
    {
        $id = $request->get('id');
        $serviceQueries = $this->get('corebundle.servicesqlqueries');
        $serviceQueries->delete($id, 'CallOfOffer');
        return $this->redirectToRoute('vm_user_coo_add');
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

            return $this->render('@Core/Test/test.html.twig', [
                "form" => $form->createView()
            ]);
        }
        return $this->render('@Core/Test/test.html.twig', [
            "form" => $form->createView()
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
}
