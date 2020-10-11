<?php

namespace App\Entity;

use App\Repository\HeadingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HeadingsRepository::class)
 */
class Headings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titleHeading;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleHeading(): ?string
    {
        return $this->titleHeading;
    }

    public function setTitleHeading(string $titleHeading): self
    {
        $this->titleHeading = $titleHeading;

        return $this;
    }
}
