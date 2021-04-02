<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\CustomerAddress;
use App\Entity\CustomerEmail;
use App\Entity\CustomerPhoneNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * CustomerRepository constructor.
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Customer::class);

        $this->entityManager = $entityManager;
    }

    /**
     * @param array $customerData
     * @param UserInterface $user
     * @return Customer
     */
    public function createCustomer(array $customerData, UserInterface $user): Customer
    {
        $customer = new Customer();

        $this->fillCustomerProperties($customer, $customerData);

        $customer->setOwner($user);

        return $customer;
    }

    /**
     * @param Customer $customer
     * @param array $customerData
     * @return Customer
     */
    public function updateCustomer(Customer $customer, array $customerData): Customer
    {
        $this->fillCustomerProperties($customer, $customerData);

        return $customer;
    }

    /**
     * @param Customer $customer
     * @return Customer
     */
    public function saveCustomer(Customer $customer): Customer
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();

        return $customer;
    }

    /**
     * @param Customer $customer
     */
    public function removeCustomer(Customer $customer): void
    {
        $this->entityManager->remove($customer);
        $this->entityManager->flush();
    }

    private function fillCustomerProperties(Customer $customer, array $customerData): Customer
    {
        $customer
            ->setFirstName($customerData['firstName'])
            ->setLastName($customerData['lastName']);

        foreach ($customerData['emails'] as $email) {
            $customerEmail = new CustomerEmail();
            $customerEmail->setEmail($email['email']);

            $customer->addCustomerEmail($customerEmail);
        }

        foreach ($customerData['phoneNumbers'] as $phoneNumber) {
            $customerPhoneNumber = new CustomerPhoneNumber();
            $customerPhoneNumber->setPhoneNumber($phoneNumber['phoneNumber']);

            $customer->addCustomerPhoneNumber($customerPhoneNumber);
        }

        if (isset($customerData['address'])) {
            $address = !$customer->getCustomerAddress() ? new CustomerAddress() : $customer->getCustomerAddress();
            $address
                ->setStreet($customerData['address']['street'])
                ->setApartment($customerData['address']['apartment'])
                ->setCity($customerData['address']['city'])
                ->setCountry($customerData['address']['country'])
                ->setZipCode($customerData['address']['zipCode']);

            $customer->setCustomerAddress($address);
        }

        return $customer;
    }
}
