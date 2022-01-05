<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 */
class Student
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $scholarship;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $dreamJob;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $dreamDescription;

    /**
     * @ORM\OneToOne(targetEntity=user::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=ProfessionalSector::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ProfessionalSector $professionalSector;

    /**
     * @ORM\ManyToOne(targetEntity=School::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private School $school;

    /**
     * @ORM\ManyToOne(targetEntity=StudyLevel::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private StudyLevel $studyLevel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScholarship(): ?bool
    {
        return $this->scholarship;
    }

    public function setScholarship(?bool $scholarship): self
    {
        $this->scholarship = $scholarship;

        return $this;
    }

    public function getDreamJob(): ?string
    {
        return $this->dreamJob;
    }

    public function setDreamJob(string $dreamJob): self
    {
        $this->dreamJob = $dreamJob;

        return $this;
    }

    public function getDreamDescription(): ?string
    {
        return $this->dreamDescription;
    }

    public function setDreamDescription(?string $dreamDescription): self
    {
        $this->dreamDescription = $dreamDescription;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getProfessionalSector(): ?ProfessionalSector
    {
        return $this->professionalSector;
    }

    public function setProfessionalSector(?ProfessionalSector $professionalSector): self
    {
        $this->professionalSector = $professionalSector;

        return $this;
    }

    public function getSchool(): School
    {
        return $this->school;
    }

    public function setSchool(School $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getStudyLevel(): StudyLevel
    {
        return $this->studyLevel;
    }

    public function setStudyLevel(StudyLevel $studyLevel): self
    {
        $this->studyLevel = $studyLevel;

        return $this;
    }
}
