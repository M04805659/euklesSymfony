<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Services\MessageService;
use App\Entity\Materiel;

class MaterielManager
{

	/**
	 * @var EntityManagerInterface
	 */
	protected $em;

	/**
	 * @var MessageService
	 */
	protected $messageService;

	public function __construct(
		EntityManagerInterface $em,
		MessageService $messageService
	)
	{
		$this->em = $em;
		$this->messageService = $messageService;
	}


	/**
	 * @param Materiel $materiel
	 */
	public function saveMateriel(Materiel $materiel)
	{
		if ($materiel instanceof Materiel) {
			$this->em->persist($materiel);
			$this->em->flush();
			$this->messageService->addSuccess('Materiel à été Bien ajouté !');
		}
	}

}