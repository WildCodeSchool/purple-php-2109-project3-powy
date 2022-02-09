<?php

namespace App\Entity;

use App\Repository\MentorRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToOne(targetEntity=Mentoring::class, inversedBy="mentor", cascade={"persist"}, orphanRemoval=false))
     */
    private ?Topic $topic;

    /**
     * @ORM\OneToMany(targetEntity=Mentoring::class, mappedBy="mentor", cascade={"persist", "remove"})
     */
    private Collection $mentorings;

    public function __construct()
    {
        $this->mentorings = new ArrayCollection();
    }

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
        $this->user = $user;

        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    //solve proxy error message at user connexion
    public function __sleep()
    {
        return [];
    }

    public function __toString()
    {
        return $this->jobTitle;
    }

    /**
     * return an active mentoring or null
     * a pending mentoring is considered as an active mentoring
     */
    public function getMentoring(): ?Mentoring
    {
        if ($this->getMentorings() !== null) {
            foreach ($this->getMentorings() as $mentoring) {
                //get accepted mentorings which are ongoing
                if ($mentoring->getIsAccepted() === true && $mentoring->getEndingDtae() > (new DateTime())) {
                    return $mentoring;
                }
                //get pending mentorings (student never accepted or refused the mentoring by mail)
                if ($mentoring->getIsAccepted() === null && $mentoring->getEndingDtae() === null) {
                    return $mentoring;
                }
            }
        }
        return null;
    }

    /**
     * @return Collection|Mentoring[]
     */
    public function getMentorings(): Collection
    {
        return $this->mentorings;
    }

    public function addMentoring(Mentoring $mentoring): self
    {
        if (!$this->mentorings->contains($mentoring)) {
            $this->mentorings[] = $mentoring;
            $mentoring->setMentor($this);
        }

        return $this;
    }

    public function removeMentoring(Mentoring $mentoring): self
    {
        if ($this->mentorings->removeElement($mentoring)) {
            // set the owning side to null (unless already changed)
            if ($mentoring->getMentor() === $this) {
                $mentoring->setMentor(null);
            }
        }

        return $this;
    }
}
