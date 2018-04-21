<?php

namespace CoreBundle\Controller;

use CoreBundle\CoreBundle;
use CoreBundle\Entity\CallOfOffer;
use CoreBundle\Entity\Proposition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class ProviderController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Core/Display/Provider/index.html.twig');
    }

    public function consultAction()
    {
        $listOfCallOfOffer = $this->listCallsOfOffer();
        $listOfPropositions = $this->listPropositions();
        $user = $this->getUser();
        return $this->render('@Core/Display/Provider/NewProposition/ConsultCallOfOffer.html.twig', [
            'title' => "Liste des appels d'offre",
            'list' => $listOfCallOfOffer,
            'propositions' => $listOfPropositions,
            'user' => $user
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
        $serviceQueries->delete($idProp,'Proposition');

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
            ->add('price', MoneyType::class)
            ->add('comment', TextareaType::class, array('required' => false))
            ->getForm();
        $form->handleRequest($request);
        $listOfCallsOfOffer = $this->listCallsOfOffer();
        $listOfPropositions = $this->listPropositions();
        if ($form->isSubmitted() && $form->isValid()) {
            $serviceQueries->add($proposition);
            $consultAndOffer = new Proposition();
            $form = $this->createFormBuilder($consultAndOffer)
                ->add('price', MoneyType::class)
                ->add('comment', TextareaType::class, array('required' => true))
                ->getForm();
            return $this->render('@Core/Display/Provider/NewProposition/ConsultCallOfOffer.html.twig', [
                'title' => "Liste des appels d'offre",
                'list' => $listOfCallsOfOffer,
                'idProp' => $idOffer,
                'propositions' => $listOfPropositions,
                'user' => $user
            ]);
        }

        return $this->render('@Core/Display/Provider/NewProposition/NewProposition.html.twig', [
            'form' => $form->createView(),
            'title' => "Faire une offre",
            'list' => $listOfCallsOfOffer
        ]);
    }

    public function listPropositions()
    {
        $serviceQueries = $this->get('corebundle.servicesqlqueries');
        $propositions = $serviceQueries->listAll('Proposition');
        return $propositions;
    }

    public function listCallsOfOffer()
    {
        $serviceQueries = $this->get('corebundle.servicesqlqueries');
        $listing = $serviceQueries->listAll('CallOfOffer');
        return $listing;
    }
}
