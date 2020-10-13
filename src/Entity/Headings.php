<?php

namespace App\Entity;

use App\Repository\HeadingsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $titleHeading;

    /**
     * @ORM\OneToMany(targetEntity=HeadingPages::class, mappedBy="heading")
     */
    private $headingPages;

    public function __construct()
    {
        $this->headingPages = new ArrayCollection();
    }

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

    /**
     * @return Collection|HeadingPages[]
     */
    public function getHeadingPages(): Collection
    {
        return $this->headingPages;
    }

    public function addHeadingPage(HeadingPages $headingPage): self
    {
        if (!$this->headingPages->contains($headingPage)) {
            $this->headingPages[] = $headingPage;
            $headingPage->setHeading($this);
        }

        return $this;
    }

    public function removeHeadingPage(HeadingPages $headingPage): self
    {
        if ($this->headingPages->contains($headingPage))
            $this->headingPages->removeElement($headingPage);

        return $this;
    }
}
