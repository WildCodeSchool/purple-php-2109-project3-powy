<?php

namespace App\Entity;

use App\Repository\MentoringRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?DateTimeInterface $startingDate = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $endingDtae = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $isAccepted = null;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="mentoring")
     */
    private Collection $messages;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $mentoringTopic;

    /**
     * @ORM\ManyToOne(targetEntity=Student::class, inversedBy="mentorings", cascade={"remove"})
     */
    private ?Student $student;

    /**
     * @ORM\ManyToOne(targetEntity=Mentor::class, inversedBy="mentorings", cascade={"remove"})
     */
    private ?Mentor $mentor;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

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

    public function getIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(?bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }
    /**
     * @return Collection|Message[]
     */
    public function getMessages(): ?Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setMentoring($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getMentoring() === $this) {
                $message->setMentoring(null);
            }
        }

        return $this;
    }

    //solve proxy error message at user connexion
    public function __sleep()
    {
        return [];
    }

    public function getMentoringTopic(): ?int
    {
        return $this->mentoringTopic;
    }

    public function setMentoringTopic(?int $mentoringTopic): self
    {
        $this->mentoringTopic = $mentoringTopic;

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
