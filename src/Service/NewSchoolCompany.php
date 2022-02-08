<?php

namespace App\Service;

use App\Entity\Mentor;
use App\Entity\School;
use App\Entity\Company;
use App\Entity\Student;
use App\Repository\SchoolRepository;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewSchoolCompany extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SchoolRepository $schoolRepository;
    private CompanyRepository $companyRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SchoolRepository $schoolRepository,
        CompanyRepository $companyRepository
    ) {
        $this->entityManager = $entityManager;
        $this->schoolRepository = $schoolRepository;
        $this->companyRepository = $companyRepository;
    }

    // method to add a new school to the database if user chose "other"
    public function setNewSchool(FormInterface $form, Student $student): void
    {
        // fetch the school data
        $school = $form->get('school')->getData();
        // if school data is "Autre"
        if ($school instanceof School && $school->getName() == 'Autre') {
            // fetch the school name (with first letter capitalized) from the add input
            /* @phpstan-ignore-next-line */
            $schoolName = ucfirst($form->get('schoolAdd')->getData());
            $listOfSchools = $this->schoolRepository->findAll();
            // for all the schools already in the database
            foreach ($listOfSchools as $alreadyExistedSchool) {
                // check if the school is already known
                if (
                    $alreadyExistedSchool->getName() != null &&
                    strcasecmp($schoolName, $alreadyExistedSchool->getName()) == 0
                ) {
                    // if it exists add the already existed school to the student
                    $student->setSchool($alreadyExistedSchool);
                    return;
                }
            }
            // if not set a new school
            $newSchool = new School();
            $newSchool->setName(ucfirst(strtolower($schoolName)));
            $student->setSchool($newSchool);
            $this->entityManager->persist($newSchool);
            return;
        }
    }

    // method to add a new company to the database if user chose "other"
    public function setNewCompany(FormInterface $form, Mentor $mentor): void
    {
        // fetch the company data
        $company = $form->get('company')->getData();
        // if companydata is "Autre"
        if ($company instanceof Company && $company->getName() == 'Autre') {
            // fetch the company name (with first letter capitalized) from the add input
            /* @phpstan-ignore-next-line */
            $companyName = ucfirst($form->get('companyAdd')->getData());
            $listOfCompanies = $this->companyRepository->findAll();
            // for all the companies already in the database
            foreach ($listOfCompanies as $existedCompany) {
                // check if the company is already known (case insensitive compare)
                if (
                    $existedCompany->getName() != null &&
                    strcasecmp($companyName, $existedCompany->getName()) == 0
                ) {
                    // if it exists add the already existed company to the mentor
                    $mentor->setCompany($existedCompany);
                    return;
                }
            }
            // if not set a new company
            $newCompany = new Company();
            $newCompany->setName(ucfirst(strtolower($companyName)));
            $mentor->setCompany($newCompany);
            $this->entityManager->persist($newCompany);
            return;
        }
    }
}
