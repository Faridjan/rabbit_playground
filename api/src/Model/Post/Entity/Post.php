<?php

declare(strict_types=1);

namespace App\Model\Post\Entity;

use App\Model\Type\UUIDType;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Post
{

    /**
     * @ORM\Column(type="uuid_type")
     * @ORM\Id
     */
    private UUIDType $id;

    /**
     * @ORM\Column(unique=true)
     */
    private string $title;

    /**
     * @ORM\Column()
     */
    private string $description;

    public function __construct(UUIDType $id, string $title, string $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }

    public function getId(): UUIDType
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }


}
