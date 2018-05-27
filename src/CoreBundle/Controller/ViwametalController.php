<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\CallOfOffer;
use CoreBundle\Entity\Proposition;
use CoreBundle\Entity\Provider;
use CoreBundle\Form\CallOfOfferType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViwametalController extends Controller
{
    /**
     * Retourne la page d'accueil avec la liste des appels d'offre en cours
     *
     * @return Response Page d'accueil
     */
    public function indexAction()
    {
        $propositions = $this->listPropositions();
        return $this->render('@Core/Display/Viwametal/CallOfOffer/CallOfOffer.html.twig', [
            'listCooInProgress' => $this->listCallsOfOffer(true),
            'title' => "Appels d'offre",
            'propositions' => $propositions
        ]);
    }

    /**
     * Permet d'ajouter un appel d'offre
     *
     * @param Request $request pour récupérer les données du formulaire et les traiter
     *
     * @return RedirectResponse|Response redirection vers page d'accueil après ajout d'un appel d'offre
     */
    public function addAction(Request $request)
    {
        $propositions = $this->listPropositions();
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
            'listCooInProgress' => $this->listCallsOfOffer(true),
            'propositions' => $propositions
        ]);
    }

    public function acceptAction(Request $request)
    {

        $idProp = $request->get('id');
        $serviceCoo = $this->get('corebundle.servicecallofoffer');
        $propositionTag = $serviceCoo->getCooTagFromPropositionId($idProp)['tag'];
        $providerUsername = $serviceCoo->getCooProviderUsernameFromPropositionId($idProp)['username'];
        $coo = $serviceCoo->getCooFromPropositionId($idProp)->setInProgress(false);
        $proposition = new Proposition();
        $proposition->setComment("");
        $form = $this->createFormBuilder($proposition)
            ->add('responseViwametal', TextareaType::class, [
                'label' => "Commentaire ",
                'required' => true
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $request->get('form')['responseViwametal'];
            $proposition = $serviceCoo->acceptProposition($idProp, $comment);
            return $this->render("@Core/Display/Viwametal/Propositions/AcceptProposition.html.twig", [
                'tag' => $propositionTag,
                'title' => "Acceptation de la proposition  ",
                'prov_username' => $providerUsername,
                'acceptation' => true,
                'coo' => $coo
            ]);


        } else {

            return $this->render('@Core/Display/Viwametal/Propositions/AcceptProposition.html.twig', [
                'idProp' => $idProp,
                'title' => "Acceptation de la proposition : ",
                'tag' => $propositionTag,
                'form' => $form->createView(),
                'prov_username' => $providerUsername
            ]);
        }
    }

    public function refuseAction(Request $request)
    {
        $idProp = $request->get('id');
        $serviceCoo = $this->get('corebundle.servicecallofoffer');
        $propositionTag = $serviceCoo->getCooTagFromPropositionId($idProp)['tag'];
        $providerUsername = $serviceCoo->getCooProviderUsernameFromPropositionId($idProp)['username'];
        $coo = $serviceCoo->getCooFromPropositionId($idProp)->setInProgress(true);
        $proposition = new Proposition();
        $proposition->setComment("");
        $form = $this->createFormBuilder($proposition)
            ->add('responseViwametal', TextareaType::class, [
                'label' => "Commentaire ",
                'required' => true
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $request->get('form')['responseViwametal'];
            $proposition = $serviceCoo->refuseProposition($idProp, $comment);
            return $this->render("@Core/Display/Viwametal/Propositions/RefuseProposition.html.twig", [
                'tag' => $propositionTag,
                'title' => "Refus de la proposition  ",
                'prov_username' => $providerUsername,
                'refusal' => true,
                'coo' => $coo
            ]);


        } else {

            return $this->render('@Core/Display/Viwametal/Propositions/RefuseProposition.html.twig', [
                'idProp' => $idProp,
                'title' => "Refus de la proposition : ",
                'tag' => $propositionTag,
                'form' => $form->createView(),
                'prov_username' => $providerUsername
            ]);
        }
    }

    public function seeAction(Request $request)
    {
        $idCoo = $request->get('id');
        $repCoo = $this->getDoctrine()->getManager()->getRepository("CoreBundle:CallOfOffer");
        $repProp = $this->getDoctrine()->getManager()->getRepository("CoreBundle:Proposition");
        $coo = $repCoo->getCoo($idCoo);
        $tag = $coo->getTag();
        $propositions = $repProp->getAllPropositionByCooId($idCoo);

        return $this->render('@Core/Display/Viwametal/Propositions/SeePropositions.html.twig', [
            'title' => $tag,
            'propositions' => $propositions,  
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

    public function listCallsOfOffer($inProgress)
    {
        $rep = $this->getDoctrine()->getManager()->getRepository("CoreBundle:CallOfOffer");
        $listing = $rep->getAllCooInProgress($inProgress);
        return $listing;
    }

    public function listPropositions()
    {
        $rep = $this->getDoctrine()->getManager()->getRepository("CoreBundle:Proposition");
        $listProp = $rep->findAll();
        return $listProp;
    }

    public function listProviders()
    {
        $serviceQueries = $this->get('corebundle.servicesqlqueries');
        $providers = $serviceQueries->listAll('Provider');
        return $providers;
    }
}
