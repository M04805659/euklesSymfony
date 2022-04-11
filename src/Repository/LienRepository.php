<?php

namespace App\Repository;

use App\Entity\Lien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lien[]    findAll()
 * @method Lien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lien::class);
    }

	public function selectbyMatrielAndTotal()
	{
		return $this->getEntityManager()
			->createQueryBuilder()
			->select ('COUNT(l.client) AS nombreMateriel')
			->addSelect('sum(m.prix_vente) as total')
			->addSelect('c.nom')
			->addSelect('c.prenom')
			->from('App\Entity\Lien', 'l')
			->innerJoin('App\Entity\Client', 'c', 'with', "l.client = c.id")
			->innerJoin('App\Entity\Materiel', 'm', 'with', "l.materiel  = m.id")
			->where('l.vendu = :vendu')
			->groupBy('l.client')
			->having('nombreMateriel >= :nbMateriel and total > 30000')
			->setParameter('vendu', 1)
			->setParameter('nbMateriel', 30)
			->orderBy('total', 'DESC')
			->getQuery()
			->getResult();
	}

	public function getTotauxVenduByClient()
	{
		return $this->getEntityManager()
			->createQueryBuilder()
			->addSelect('sum(m.prix_vente) as total')
			->addSelect('c.nom')
			->addSelect('c.prenom')
			->from('App\Entity\Lien', 'l')
			->innerJoin('App\Entity\Client', 'c', 'with', "l.client = c.id")
			->innerJoin('App\Entity\Materiel', 'm', 'with', "l.materiel  = m.id")
			->where('l.vendu = :vendu')
			->groupBy('l.client')
			->setParameter('vendu', 1)
			->orderBy('total', 'DESC')
			->getQuery()
			->getResult();
	}
}
