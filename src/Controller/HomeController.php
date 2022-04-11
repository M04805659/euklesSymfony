<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LienRepository;
use App\Repository\MaterielRepository;
use App\Entity\Lien;
use App\Entity\Client;
use App\Entity\Materiel;
use App\Form\LienType;
use App\Form\MaterielType;
use App\Manager\MaterielManager;
use App\Manager\LienManager;

class HomeController extends AbstractController
{
	/**
	 * @Route("/", name="home")
	 */
	public function index(LienRepository $lien)
	{
		return $this->render('home/index.html.twig', [
			"items" => $lien->selectbyMatrielAndTotal(),
		]);
	}

	/**
	 * @Route("/related-materiel", name="related_materiel")
	 */
	public function relatedMateriel(Request $request, LienManager $lienManager)
	{
		$lien = new Lien();
		$form = $this->createForm(LienType::class, $lien)
			->add('saveAndCreateNew', SubmitType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$lienManager->relatedMaterielToClient($lien);
			return $this->redirectToRoute('related_materiel');
		}

		return $this->render('home/add_materiel.html.twig', [
			'lien' => $lien,
			'form' => $form->createView(),
		]);
	}


	/**
	 * @Route("/list-materiel", name="list_materiel")
	 */
	public function listMateriel(MaterielRepository $materiels)
	{
		$allMateriels = $materiels->findAll();
		return $this->render('home/list_materiel.html.twig', [
			'materiels' => $allMateriels
		]);
	}

	/**
	 * @Route("/new-materiel", name="new_materiel")
	 */
	public function newMateriel(Request $request, MaterielManager $materielManager)
	{
		$materiel = new Materiel();
		$form = $this->createForm(MaterielType::class, $materiel)
			->add('saveAndCreateNew', SubmitType::class);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$materielManager->saveMateriel($materiel);
			return $this->redirectToRoute('list_materiel');
		}

		return $this->render('home/new_materiel.html.twig', [
			'lien' => $materiel,
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/client-rentable", name="list_client_rentable")
	 */
	public function listClientRentable(LienRepository $lien)
	{
		return $this->render('home/list_client_rentable.html.twig', [
			"items" => $lien->getTotauxVenduByClient(),
		]);
	}

}
