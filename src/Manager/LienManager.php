<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use App\Services\MessageService;
use App\Entity\Lien;

class LienManager
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
	 * @param Lien $lien
	 */
	public function relatedMaterielToClient(Lien $lien)
	{
		if ($lien instanceof Lien) {
			$this->em->persist($lien);
			$this->em->flush();
			$this->messageService->addSuccess('Materiel ajouté  à un client !');
		}
	}

}