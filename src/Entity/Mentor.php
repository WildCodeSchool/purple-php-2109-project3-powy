<?php

namespace App\Entity;

use App\Repository\MentorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MentorRepository::class)
 */
class Mentor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $jobTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $careerDescription;

    /**
     * @ORM\ManyToOne(targetEntity=ProfessionalSector::class, inversedBy="mentors")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?ProfessionalSector $professionalSector;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Company $company;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="mentor", cascade={"persist", "remove"})
     */
    private ?User $user;

    /**
     * @ORM\Column(type="integer")
     */
    private int $topic1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $topic2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $topic3;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(string $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getCareerDescription(): ?string
    {
        return $this->careerDescription;
    }

    public function setCareerDescription(?string $careerDescription): self
    {
        $this->careerDescription = $careerDescription;

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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setMentor(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getMentor() !== $this) {
            $user->setMentor($this);
        }

        $this->user = $user;

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

    public function setTopic2(?int $topic2): self
    {
        $this->topic2 = $topic2;

        return $this;
    }

    public function getTopic3(): ?int
    {
        return $this->topic3;
    }

    public function setTopic3(?int $topic3): self
    {
        $this->topic3 = $topic3;

        return $this;
    }
}
