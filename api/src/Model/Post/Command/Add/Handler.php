<?php

declare(strict_types=1);

namespace App\Model\Post\Command\Add;

use App\Infrastructure\Doctrine\Flusher;
use App\Model\Post\Entity\Post;
use App\Model\Post\Entity\PostRepository;
use App\Model\Type\UUIDType;

class Handler
{
    private Flusher $flusher;
    private PostRepository $repository;

    public function __construct(Flusher $flusher, PostRepository $repository)
    {
        $this->flusher = $flusher;
        $this->repository = $repository;
    }

    public function handle(string $title, string $description): void
    {
        $uuid = UUIDType::generate();

        $post = new Post(
            $uuid,
            $title,
            $description
        );

        $this->repository->add($post);
        $this->flusher->flush();
    }
}
