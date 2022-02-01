<?php

namespace App\Entity;

use App\Entity\Mentoring;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\StudentRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

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
     *      message = "Veuillez nous préciser votre job de rêve.")
     */
    private string $dreamJob;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $dreamDescription;

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

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="student", cascade={"persist", "remove"})
     */
    private ?User $user;

    /**
     * @ORM\OneToOne(targetEntity=Topic::class, inversedBy="student", cascade={"persist", "remove"})
     */
    private ?Topic $topic;

    /**
     * @ORM\OneToMany(targetEntity=Mentoring::class, mappedBy="student", cascade={"persist", "remove"})
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

    //solve proxy error message at user connexion
    public function __sleep()
    {
        return [];
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

    /**
     * return an active mentoring or null
     * a pending mentoring is considered as an active mentoring
     */
    public function getMentoring(): ?Mentoring
    {
        foreach ($this->getMentorings() as $mentoring) {
            if ($mentoring->getIsAccepted() !== false || $mentoring->getEndingDtae() > (new DateTime())) {
                return $mentoring;
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
            $mentoring->setStudent($this);
        }

        return $this;
    }

    public function removeMentoring(Mentoring $mentoring): self
    {
        if ($this->mentorings->removeElement($mentoring)) {
            // set the owning side to null (unless already changed)
            if ($mentoring->getStudent() === $this) {
                $mentoring->setStudent(null);
            }
        }

        return $this;
    }
}
