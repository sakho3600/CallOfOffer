<?php

namespace CoreBundle\Controller;

use CoreBundle\Entity\Proposition;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProviderController extends Controller
{
    /**
     * @return Response Page d'accueil vierge
     */
    public function indexAction()
    {
        return $this->render('@Core/Display/Provider/index.html.twig');
    }

    /**
     * @return Response Consultation des appels d'offre en cours
     */
    public function consultAction()
    {
        $user = $this->getUser();
        $listOfCallOfOffer = $this->listCallsOfOffer(true);
        $listOfPropositions = $this->listPropositions($user->getId());

        return $this->render('@Core/Display/Provider/NewProposition/ConsultCallOfOffer.html.twig', [
            'title' => "Liste des appels d'offre",
            'listCoo' => $listOfCallOfOffer,
            'propositions' => $listOfPropositions,
            'user' => $user
        ]);
    }

    public function consultAcceptedAction()
    {
        $idProvider = $this->getUser()->getId();
        $repProp = $this->getDoctrine()->getManager()->getRepository("CoreBundle:Proposition");
        $listPropAccepted = $repProp->getAllPropositionByProviderIdIsAccepted($idProvider);
        return $this->render('@Core/Display/Provider/Propositions/ConsultAccepted.html.twig', [
            'title' => "Proposition(s) acceptée(s)",
            'listPropAccepted' => $listPropAccepted,
        ]);
    }

    public function consultRefusedAction()
    {
        $idProvider = $this->getUser()->getId();
        $repProp = $this->getDoctrine()->getManager()->getRepository("CoreBundle:Proposition");
        $listPropRefused = $repProp->getAllPropositionByProviderIdIsRefused($idProvider);
        return $this->render('@Core/Display/Provider/Propositions/ConsultRefused.html.twig', [
            'title' => "Proposition(s) refusée(s)",
            'listPropRefused' => $listPropRefused,
        ]);
    }

    public function deleteAction(Request $request)
    {
        $idCallOfOffer = $request->get('id');
        $serviceQueries = $this->get('corebundle.servicesqlqueries');
        $provider = $this->getUser();
        $idProvider = $provider->getId();
        $repProposition = $this->getDoctrine()->getManager()->getRepository("CoreBundle:Proposition");
        $prop = $repProposition->getByCooAndUser($idCallOfOffer, $idProvider);
        $idProp = $prop->getId();
        $serviceQueries->delete($idProp, 'Proposition');

        return $this->redirectToRoute('provider_coo_consult');

    }

    public function proposeAction(Request $request)
    {
        $proposition = new Proposition();
        $idOffer = $request->get('id');
        $user = $this->getUser();
        $proposition->setProvider($user);
        $serviceQueries = $this->get('corebundle.servicesqlqueries');
        $callOfOffer = $serviceQueries->getRow($idOffer, "CallOfOffer");
        $proposition->setCallOfOffer($callOfOffer);
        $form = $this->createFormBuilder($proposition)
            ->add('price', MoneyType::class, [
                'label' => 'Prix'
            ])
            ->add('dateLivPrev', DateType::class, [
                'widget' => 'choice'
            ])
            ->add('comment', TextareaType::class, ['required' => false,
                'label' => 'Commentaire'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $serviceQueries->add($proposition);
            $listOfCallsOfOffer = $this->listCallsOfOffer(true);
            $listOfPropositions = $this->listPropositions($user->getId());
            return $this->render('@Core/Display/Provider/NewProposition/ConsultCallOfOffer.html.twig', [
                'title' => "Liste des appels d'offre",
                'listCoo' => $listOfCallsOfOffer,
                'propositions' => $listOfPropositions,
                'user' => $user
            ]);
        }

        return $this->render('@Core/Display/Provider/NewProposition/NewProposition.html.twig', [
            'form' => $form->createView(),
            'title' => "Faire une offre",
            'coo' => $callOfOffer
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
