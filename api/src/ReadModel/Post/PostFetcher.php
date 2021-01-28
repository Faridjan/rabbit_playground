<?php

declare(strict_types=1);

namespace App\ReadModel\Post;

use App\Model\Post\Command\Post\Command;
use App\Model\Post\Entity\Post;
use App\Model\Post\Entity\PostRepository;
use App\Model\Type\UUIDType;

class PostFetcher
{
    private PostRepository $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPostByUuid(Command $command): array
    {
        $uuid = new UUIDType($command->id);
        return $this->convertDomainToArray($this->repository->get($uuid));
    }

    public function getAll(): array
    {
        $result = [];

        foreach ($this->repository->getAll() as $post) {
            $result[] = $this->convertDomainToArray($post);
        }

        return $result;
    }

    private function convertDomainToArray(Post $post): array
    {
        return [
            'id' => $post->getId()->getValue(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription()
        ];
    }
}