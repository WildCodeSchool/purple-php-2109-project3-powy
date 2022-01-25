<?php

namespace App\Entity;

use App\Repository\MentoringRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $startingDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $endingDtae;

    /**
     * @ORM\OneToOne(targetEntity=Student::class, mappedBy="mentoring", cascade={"persist", "remove"})
     */
    private ?Student $student;

    /**
     * @ORM\OneToOne(targetEntity=Mentor::class, mappedBy="mentoring", cascade={"persist", "remove"})
     */
    private ?Mentor $mentor;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isAccepted = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->startingDate;
    }

    public function setStartingDate(?\DateTimeInterface $startingDate): self
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getEndingDtae(): ?\DateTimeInterface
    {
        return $this->endingDtae;
    }

    public function setEndingDtae(?\DateTimeInterface $endingDtae): self
    {
        $this->endingDtae = $endingDtae;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        // unset the owning side of the relation if necessary
        if ($student === null && $this->student !== null) {
            $this->student->setMentoring(null);
        }

        // set the owning side of the relation if necessary
        if ($student !== null && $student->getMentoring() !== $this) {
            $student->setMentoring($this);
        }

        $this->student = $student;

        return $this;
    }

    public function getMentor(): ?Mentor
    {
        return $this->mentor;
    }

    public function setMentor(?Mentor $mentor): self
    {
        // unset the owning side of the relation if necessary
        if ($mentor === null && $this->mentor !== null) {
            $this->mentor->setMentoring(null);
        }

        // set the owning side of the relation if necessary
        if ($mentor !== null && $mentor->getMentoring() !== $this) {
            $mentor->setMentoring($this);
        }

        $this->mentor = $mentor;

        return $this;
    }

    public function getIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(?bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }
}
