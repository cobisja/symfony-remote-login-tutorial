<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\DummyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DummyRepository::class)]
#[ApiResource(
    operations: [
        new Post()
    ]
)]
class Dummy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $dummyText = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDummyText(): ?string
    {
        return $this->dummyText;
    }

    public function setDummyText(string $dummyText): static
    {
        $this->dummyText = $dummyText;

        return $this;
    }
}
