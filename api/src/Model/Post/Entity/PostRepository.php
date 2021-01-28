<?php

declare(strict_types=1);

namespace App\Model\Post\Entity;

use App\Model\Type\UUIDType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use DomainException;

class PostRepository
{
    private EntityManagerInterface $em;
    private EntityRepository $repository;

    public function __construct(
        EntityManagerInterface $em,
        EntityRepository $repository
    ) {
        $this->em = $em;
        $this->repository = $repository;
    }

    public function get(UUIDType $id): Post
    {
        /** @var Post|null $Post */
        $Post = $this->repository->find($id->getValue());
        return $this->fetch($Post);
    }

    public function getAll(): array
    {
        /** @var array|null $rows */
        $rows = $this->repository->createQueryBuilder('t')
            ->getQuery()
            ->getResult();

        return $this->fetchAll($rows);
    }

    public function add(Post $Post): void
    {
        $this->em->persist($Post);
    }

    public function fetch(?Post $Post): Post
    {
        if (!$Post) {
            throw new DomainException('Post is not found.');
        }
        return $Post;
    }

    public function fetchAll(?array $rows): array
    {
        if (!$rows) {
            throw new DomainException('Posts not found.');
        }

        return $rows;
    }

}