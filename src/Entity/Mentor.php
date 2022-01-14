<?php

namespace App\Entity;

use App\Repository\MentorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(
     *      message = "Veuillez nous préciser votre dernier poste occupé.")
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
     * * @Assert\NotBlank(
     *      message = "Veuillez nous préciser le nom de votre entreprise.")
     */
    private ?Company $company;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="mentor", cascade={"persist", "remove"})
     */
    private ?User $user;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *      message = "Merci de choisir au moins un sujet de mentorat.")
     */
    private int $topic1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Choice ({1,2,3,4,5,6,7,8,9, null})
     */
    private ?int $topic2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Choice ({1,2,3,4,5,6,7,8,9, null})
     */
    private ?int $topic3;

    /**
     * @ORM\OneToOne(targetEntity=Mentoring::class, inversedBy="mentor", cascade={"persist", "remove"})
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMentoring(): ?Mentoring
    {
        return $this->mentoring;
    }

    public function setMentoring(?Mentoring $mentoring): self
    {
        $this->mentoring = $mentoring;

        return $this;
    }
}
