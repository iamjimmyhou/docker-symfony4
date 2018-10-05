<?php

namespace App\Controller;
use App\Form\EmployeeType;
use App\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @Route("/employee", name="employee")
     */
    public function index()
    {
        return $this->render('employee/index.html.twig', [
            'controller_name' => 'EmployeeController',
        ]);
    }

    /**
     * @Route("/employeeList", name="employeeList")
     */
    public function employeeList()
    {
        $repository = $this->getDoctrine()->getRepository(Employee::class);
        $employeeList = $repository->findAll();
        return $this->render('employee/list.html.twig', [
            'employeeList' => $employeeList,
        ]);
    }

    /**
     * @Route("/addEmployee", name="addEmployee")
     */
    public function addEmployeeAction(Request $request)
    {
        $form = $this->createForm(EmployeeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $employee = $form->getData();
            $em->persist($employee);
            $em->flush();
            return $this->redirectToRoute('employeeList');
        }
        return $this->render('employee/add.html.twig', [
            'employeeForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="editEmployee")
     */
    public function editEmployeeAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $employee = $em->find(Employee::class, $id);
        $form = $this->createForm(EmployeeType::class, $employee);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('employeeList');
        }
        return $this->render('employee/add.html.twig', [
            'employeeForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="deleteEmployee")
     */
    public function deleteEmployeeAction(Request $request, int $id)
    {
        $em = $this->getDoctrine()->getManager();
        $employee = $em->find(Employee::class, $id);
        $em->remove($employee);
        $em->flush();
        return $this->redirectToRoute('employeeList');
    }
}
