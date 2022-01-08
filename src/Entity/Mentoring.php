<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MentoringRepository;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass=MentoringRepository::class)
 */
class Mentoring
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $startingDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $endingDate;

    /**
     * @ORM\OneToOne(targetEntity=Student::class, inversedBy="mentoring", cascade={"persist", "remove"})
     */
    private ?Student $student;

    /**
     * @ORM\OneToOne(targetEntity=Mentor::class, inversedBy="mentoring", cascade={"persist", "remove"})
     */
    private ?Mentor $mentor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->startingDate;
    }

    public function setStartingDate(\DateTimeInterface $startingDate): self
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->endingDate;
    }

    public function setEndingDate(\DateTimeInterface $endingDate): self
    {
        $this->endingDate = $endingDate;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getMentor(): ?Mentor
    {
        return $this->mentor;
    }

    public function setMentor(?Mentor $mentor): self
    {
        $this->mentor = $mentor;

        return $this;
    }
}
