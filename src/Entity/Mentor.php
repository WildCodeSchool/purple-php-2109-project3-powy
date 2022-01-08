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
     * @ORM\OneToOne(targetEntity=Mentoring::class, mappedBy="mentor", cascade={"persist", "remove"})
     */
    private ?Mentoring $mentoring;

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

    public function getMentoring(): ?Mentoring
    {
        return $this->mentoring;
    }

    public function setMentoring(?Mentoring $mentoring): self
    {
        // unset the owning side of the relation if necessary
        if ($mentoring === null && $this->mentoring !== null) {
            $this->mentoring->setMentor(null);
        }

        // set the owning side of the relation if necessary
        if ($mentoring !== null && $mentoring->getMentor() !== $this) {
            $mentoring->setMentor($this);
        }

        $this->mentoring = $mentoring;

        return $this;
    }
}
