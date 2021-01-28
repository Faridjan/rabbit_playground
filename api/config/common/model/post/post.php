<?php

declare(strict_types=1);

use App\Model\Post\Entity\Post;
use App\Model\Post\Entity\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Container\ContainerInterface;

return [
    PostRepository::class => function (ContainerInterface $container): PostRepository {
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        /**
         * @var EntityRepository $repo
         * @psalm-var EntityRepository<Post> $repo
         */
        $repo = $em->getRepository(Post::class);
        return new PostRepository($em, $repo);
    }
];
