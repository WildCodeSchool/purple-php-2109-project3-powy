<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TopicRepository::class)
 */
class Topic
{
    /**
     * this const is used to enumerate topic choices.
     * It is used to send integers (keys) in database
     */
    public const TOPICS = [
        "M'immerger dans un métier" => 1,
        'Me faire coacher' => 2,
        'Réussir mes candidatures' => 3,
        'Développer mes compétences' => 4,
        'Mieux gérer les outils digitaux pro' => 5,
        'Mieux communiquer en français' => 6,
        'Mieux communiquer en anglais' => 7,
        'Mieux communiquer en espagnol' => 8,
        'Mieux communiquer en allemand' => 9,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

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
     * @ORM\OneToOne(targetEntity=Student::class, mappedBy="topic", cascade={"persist", "remove"})
     */
    private ?Student $student;

    /**
     * @ORM\OneToOne(targetEntity=Mentor::class, mappedBy="topic", cascade={"persist", "remove"})
     */
    private ?Mentor $mentor;

    public function getId(): ?int
    {
        return $this->id;
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

    //solve proxy error message at user connexion
    public function __sleep()
    {
        return [];
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        // unset the owning side of the relation if necessary
        if ($student === null && $this->student !== null) {
            $this->student->setTopic(null);
        }

        // set the owning side of the relation if necessary
        if ($student !== null && $student->getTopic() !== $this) {
            $student->setTopic($this);
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
            $this->mentor->setTopic(null);
        }

        // set the owning side of the relation if necessary
        if ($mentor !== null && $mentor->getTopic() !== $this) {
            $mentor->setTopic($this);
        }

        $this->mentor = $mentor;

        return $this;
    }
}
