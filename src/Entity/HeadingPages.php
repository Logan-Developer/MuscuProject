<?php

namespace App\Entity;

use App\Repository\HeadingPagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HeadingPagesRepository::class)
 */
class HeadingPages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $titlePage;

    /**
     * @ORM\Column(type="text")
     */
    private $contentPage;

    /**
     * @ORM\ManyToOne(targetEntity=Headings::class, inversedBy="headingPages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $heading;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitlePage(): ?string
    {
        return $this->titlePage;
    }

    public function setTitlePage(string $titlePage): self
    {
        $this->titlePage = $titlePage;

        return $this;
    }

    public function getContentPage(): ?string
    {
        return $this->contentPage;
    }

    public function setContentPage(string $contentPage): self
    {
        $this->contentPage = $contentPage;

        return $this;
    }

    public function getHeading(): ?Headings
    {
        return $this->heading;
    }

    public function setHeading(?Headings $heading): self
    {
        $this->heading = $heading;

        return $this;
    }
}
