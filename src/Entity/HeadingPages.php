<?php

namespace App\Entity;

use App\Repository\HeadingPagesRepository;
use DateTimeInterface;
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
     * @ORM\Column(type="string", length=255)
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

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="headingPages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $redactor;



    /**
     * @ORM\Column(type="datetime")
     */
    private $modificationDate;

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

    public function getContentPage()
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

    public function getRedactor(): ?Users
    {
        return $this->redactor;
    }

    public function setRedactor(?Users $redactor): self
    {
        $this->redactor = $redactor;

        return $this;
    }

    public function getModificationDate(): ?DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(DateTimeInterface $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }
}
