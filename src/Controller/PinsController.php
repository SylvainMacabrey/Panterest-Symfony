<?php

namespace App\Controller;

use App\Entity\Pins;
use App\Form\PinsFormType;
use App\Repository\PinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="pins.index", methods="GET")
     */
    public function index(PinsRepository $pinsRepository)
    {
        $pins = $pinsRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', [
            'pins' => $pins
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="pins.show", methods="GET")
     */
    public function show(Pins $pin)
    {
        return $this->render('pins/show.html.twig', [
			'pin' => $pin,
		]);
    }

    /**
     * @Route("/pins/create", name="pins.create", methods="GET|POST")
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $pin = new Pins();
        $form = $this->createForm(PinsFormType::class, $pin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($pin);
			$em->flush();
			$this->addFlash('success', 'Création effectuée avec succès');
			return $this->redirectToRoute('pins.index');
        }
        return $this->render('pins/create.html.twig', [
			'pin' => $pin,
			'form' => $form->createView()
		]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="pins.edit", methods="GET|PUT")
     */
    public function edit(Pins $pin, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(PinsFormType::class, $pin, [
            'method' => 'PUT'
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$this->addFlash('success', 'Modification effectuée avec succès');
			return $this->redirectToRoute('pins.index');
        }
        return $this->render('pins/edit.html.twig', [
			'pin' => $pin,
			'form' => $form->createView()
		]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="pins.delete", methods="DELETE")
     */
    public function delete(Pins $pin, Request $request, EntityManagerInterface $em)
    {
        if($this->isCsrfTokenValid('delete' . $pin->getId(), $request->get('_token'))) {
			$em->remove($pin);
			$em->flush();
			$this->addFlash('success', 'Suppression effectuée avec succès');
			return $this->redirectToRoute('pins.index');
        } else {
        	throw new InvalidCsrfTokenException('Invalid CSRF token');
        }
    }

}
