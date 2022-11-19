<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
   public function findLastArticle($value): array
   {
       return $this->createQueryBuilder('a')
           ->andWhere('a.statut = :val')
           ->setParameter('val', $value)
           ->orderBy('a.datedecreation', 'DESC')
           ->setMaxResults(3)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findArticleUser($value): array
   {
       return $this->createQueryBuilder('a')
           ->andWhere('a.User = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getResult()
       ;
   }
   public function findArticle($titre, $user, $statut): array
   {
        if ($titre != NULL && $user != NULL && $statut != NULL){
            return $this->createQueryBuilder('a')
                ->andWhere('a.titre LIKE :val')
                ->andWhere('a.User = :val1')
                ->andWhere('a.statut = :val2')
                ->setParameter('val', "%" . $titre . "%")
                ->setParameter('val1', $user)
                ->setParameter('val2', $statut)
                ->getQuery()
                ->getResult()
            ;
        }elseif($titre == NULL && $user != NULL && $statut != NULL){
            var_dump($user);
            return $this->createQueryBuilder('a')
                ->andWhere('a.User = :val1')
                ->andWhere('a.statut = :val2')
                ->setParameter('val1', $user)
                ->setParameter('val2', $statut)
                ->getQuery()
                ->getResult()
            ;
        }elseif($titre == NULL && $user == NULL && $statut != NULL){
            return $this->createQueryBuilder('a')
                ->andWhere('a.statut = :val2')
                ->setParameter('val2', $statut)
                ->getQuery()
                ->getResult()
            ;
        }elseif($titre == NULL && $user != NULL && $statut == NULL){
            return $this->createQueryBuilder('a')
            ->andWhere('a.User = :val1')
            ->setParameter('val1', $user)
            ->getQuery()
            ->getResult()
        ; 
        }elseif($titre != NULL && $user == NULL && $statut == NULL){
            return $this->createQueryBuilder('a')
                ->andWhere('a.titre LIKE :val')
                ->setParameter('val', "%" . $titre . "%")
                ->getQuery()
                ->getResult()
            ;
        }elseif($titre != NULL && $user == NULL && $statut != NULL){
            return $this->createQueryBuilder('a')
                ->andWhere('a.titre LIKE :val')
                ->setParameter('val', "%" . $titre . "%")
                ->andWhere('a.statut = :val2')
                ->setParameter('val2', $statut)
                ->getQuery()
                ->getResult()
            ;
        }elseif($titre != NULL && $user != NULL && $statut == NULL){
            return $this->createQueryBuilder('a')
                ->andWhere('a.titre LIKE :val')
                ->setParameter('val', "%" . $titre . "%")
                ->andWhere('a.User = :val1')
                ->setParameter('val1', $user)
                ->getQuery()
                ->getResult()
            ;
        }
   }
}
