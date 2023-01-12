<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/customer')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'api_customer_index', methods: ['GET'])]
    public function index(CustomerRepository $customerRepository, Request $request): Response
    {
        //TODO validations
        $search = $request->get('s');

        // orderBy[first_name]=asc, orderBy[last_name]=desc
        $orderBy = $request->get('o',[]);

        // f[country.name]=Canada
        $filter = $request->get('f',[]);

        $customers = $customerRepository->findByAllColumns($search, $orderBy, $filter);
        //TODO make resource class
        $data = [];
        foreach ($customers as $customer)
            /** @var Customer $customer */
            $data[] = [
                'id' => $customer->getId(),
                'first_name'=> $customer->getFirstName(),
                'last_name'=> $customer->getLastName(),
                'email' => $customer->getEmail(),
                'address' => $this->getAddressString($customer->getAddress()),
            ];

            //TODO pagination
        return $this->json($data);
    }

    //TODO make service
    private function getAddressString(Address|null $address): string
    {
        if (!$address)
            return '';

        $arr = [
            $address->getLine2(),
            $address->getLine1(),
            $address->getState()->getName(),
            $address->getCountry()->getName()
        ];

        //filter empty columns
        $arr = array_filter($arr, fn($i) => $i);

        return implode(', ', $arr);
    }
}
