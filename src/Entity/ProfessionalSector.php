<?php

namespace App\Entity;

use App\Repository\ProfessionalSectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessionalSectorRepository::class)
 */
class ProfessionalSector
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
     * message = "Veuillez préciser votre secteur professionel.")
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="professionalSector")
     */
    private Collection $students;

    /**
     * @ORM\OneToMany(targetEntity=Mentor::class, mappedBy="professionalSector")
     */
    private Collection $mentors;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->mentors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setProfessionalSector($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getProfessionalSector() === $this) {
                $student->setProfessionalSector(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Mentor[]
     */
    public function getMentors(): Collection
    {
        return $this->mentors;
    }

    public function addMentor(Mentor $mentor): self
    {
        if (!$this->mentors->contains($mentor)) {
            $this->mentors[] = $mentor;
            $mentor->setProfessionalSector($this);
        }

        return $this;
    }

    public function removeMentor(Mentor $mentor): self
    {
        if ($this->mentors->removeElement($mentor)) {
            // set the owning side to null (unless already changed)
            if ($mentor->getProfessionalSector() === $this) {
                $mentor->setProfessionalSector(null);
            }
        }

        return $this;
    }
}
