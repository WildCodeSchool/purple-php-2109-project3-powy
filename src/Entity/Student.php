<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Assert\Choice({true, false, null})
     */
    private ?bool $scholarship;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     * message = "Veuillez nous préciser votre job de rêve")
     */
    private string $dreamJob;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $dreamDescription;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     * message = "Veuillez nous préciser votre job de rêve")
     */
    private int $topic1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $topic2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private int $topic3;

    /**
     * @ORM\ManyToOne(targetEntity=ProfessionalSector::class, inversedBy="students")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ProfessionalSector $professionalSector;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="student", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\ManyToOne(targetEntity=School::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?School $school;

    /**
     * @ORM\ManyToOne(targetEntity=StudyLevel::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?StudyLevel $studyLevel;

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

    public function getTopic1(): ?int
    {
        return $this->topic1;
    }

    public function setTopic1(int $topic1): self
    {
        $this->topic1 = $topic1;

        return $this;
    }

    public function getTopic2(): ?int
    {
        return $this->topic2;
    }

    public function setTopic2(int $topic2): self
    {
        $this->topic2 = $topic2;

        return $this;
    }

    public function getTopic3(): ?int
    {
        return $this->topic3;
    }

    public function setTopic3(int $topic3): self
    {
        $this->topic3 = $topic3;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): self
    {
        $this->school = $school;

        return $this;
    }

    public function getStudyLevel(): ?StudyLevel
    {
        return $this->studyLevel;
    }

    public function setStudyLevel(?StudyLevel $studyLevel): self
    {
        $this->studyLevel = $studyLevel;

        return $this;
    }
}
