<?php

declare(strict_types=1);

namespace App\Model\Post\Command\Add;


use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2, allowEmptyString=true)
     */
    public string $title = '';

    /**
     * @Assert\NotBlank()
     * @Assert\String()
     */
    public string $description = '';
}
