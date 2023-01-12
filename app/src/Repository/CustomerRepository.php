<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function save(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param $value
     * @return QueryBuilder Returns an array of Customer objects
     */
    public function findByAllColumns($search = null, $orders = [], $filters = []): array
    {
        $q = $this->createQueryBuilder('c')
            ->leftJoin('c.address', 'address')
            ->leftJoin('address.country', 'country')
            ->leftJoin('address.state', 'state');


        if ($search)
            $q->andWhere('c.email LIKE :val'
                . ' or c.first_name LIKE :val'
                . ' or c.last_name LIKE :val'
                . ' or address.line_1 LIKE :val'
                . ' or address.postal_code LIKE :val'
                . ' or country.name LIKE :val'
                . ' or state.name LIKE :val')
                ->setParameter('val', '%' . $search . '%');

        foreach ($orders as $column => $order)
            $q->orderBy($column, $order);

        if ($filters) {
            $q->andWhere($this->toOrX($q, $filters));

        }
        return $q->getQuery()
            ->getResult();
    }

    private function toOrX($q, $arr)
    {
        $orX = $q->expr()->orX();
        $i = 0;
        foreach ($arr as $column => $value) {
            $orX->add($q->expr()->eq($column, ':val' . $i));
            $q->setParameter('val' . $i, $value);
            $i++;
        }
        return $orX;
    }



//    public function findOneBySomeField($value): ?Customer
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
