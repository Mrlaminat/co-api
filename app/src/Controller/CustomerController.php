<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CustomerController
 * @package App\Controller
 */
class CustomerController extends AbstractController
{
    /**
     * @var CustomerRepository $customerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * @var SerializerInterface $serializer
     */
    private SerializerInterface $serializer;

    /**
     * @var ValidatorInterface $validator
     */
    private ValidatorInterface $validator;

    /**
     * CustomerController constructor.
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        CustomerRepository $customerRepository
    ) {
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("api/customers/", name="get_customers", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        try {
            $customers = $this->customerRepository->findAll();

            $data = [];
            foreach ($customers as $customer) {
                $data[] = $this->prepareCustomerData($customer);
            }

            return new JsonResponse($data, Response::HTTP_OK);
        } catch (Exception|ExceptionInterface $exception) {
            return new JsonResponse(
                [
                'type' => 'error',
                'message' => $exception->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("api/customers/{id}", name="get_customer_by_id", methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getCustomer(int $id): JsonResponse
    {
        try {
            $customer = $this->customerRepository->findOneBy(['id' => $id]);
            if (!$customer) {
                throw new NotFoundHttpException(sprintf('Customer with ID: %s not exist.', $id));
            }

            $data = $this->prepareCustomerData($customer);

            return new JsonResponse($data, Response::HTTP_OK);
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(
                [
                    'type'    => 'error',
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception|ExceptionInterface $exception) {
            return new JsonResponse(
                [
                    'type'    => 'error',
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("api/customers", name="create_customer", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createCustomer(Request $request): JsonResponse
    {
        try {
            $customerData = json_decode($request->getContent(), true);

            if (!$customerData) {
                throw new Exception('Customer data is missing for customer.');
            }

            $customer = $this->customerRepository->createCustomer($customerData, $this->getUser());

            $errors = $this->validateCustomer($customer);
            if ($errors) {
                return new JsonResponse(
                    [
                        'type' => 'validation_error',
                        'message' => 'There was a validation error!',
                        'errors' => $errors
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $this->customerRepository->saveCustomer($customer);

            return new JsonResponse(
                [
                    'type' => 'success',
                    'customer' => $this->prepareCustomerData($customer)
                ],
                Response::HTTP_CREATED
            );
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(
                [
                    'type'    => 'error',
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception|ExceptionInterface $exception) {
            return new JsonResponse(
                [
                    'type'    => 'error',
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("api/customers/{id}", name="update_customer", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateCustomer(int $id, Request $request): JsonResponse
    {
        try {
            $customer = $this->customerRepository->findOneBy(['id' => $id]);

            if ($customer->getOwner() !== $this->getUser() && !$this->isAdmin($this->getUser())) {
                throw $this->createAccessDeniedException();
            }

            if (!$customer) {
                throw new NotFoundHttpException(sprintf('Customer with ID: %s not exist.', $id));
            }

            $customerData = json_decode($request->getContent(), true);
            if (!$customerData) {
                throw new Exception(sprintf('Customer data is missing for customer with ID: %s.', $id));
            }

            $customer = $this->customerRepository->updateCustomer($customer, $customerData);

            $errors = $this->validateCustomer($customer);
            if ($errors) {
                return new JsonResponse(
                    [
                        'type' => 'validation_error',
                        'message' => 'There was a validation error!',
                        'errors' => $errors
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $this->customerRepository->saveCustomer($customer);

            return new JsonResponse(
                [
                    'type' => 'success',
                    'customer' => $this->prepareCustomerData($customer)
                ],
                Response::HTTP_CREATED
            );
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(
                [
                    'type'    => 'error',
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception|ExceptionInterface $exception) {
            return new JsonResponse(
                [
                    'type'    => 'error',
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @Route("api/customers/{id}", name="delete_customer", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function deleteCustomer(int $id): JsonResponse
    {
        try {
            $customer = $this->customerRepository->findOneBy(['id' => $id]);

            if ($customer->getOwner() !== $this->getUser() && !$this->isAdmin($this->getUser())) {
                throw $this->createAccessDeniedException();
            }

            if (!$customer) {
                throw new NotFoundHttpException(sprintf('Customer with ID: %s not exist.', $id));
            }

            $this->customerRepository->removeCustomer($customer);

            return new JsonResponse(
                [
                    'type'    => 'success',
                    'message' => 'Customer deleted'
                ],
                Response::HTTP_NO_CONTENT
            );
        } catch (NotFoundHttpException $exception) {
            return new JsonResponse(
                [
                    'type'    => 'error',
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                [
                    'type'    => 'error',
                    'message' => $exception->getMessage()
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * TODO Good to create output service and use it interface, but let's keep it simple for now
     * @param Customer $customer
     * @return array
     * @throws ExceptionInterface
     */
    private function prepareCustomerData(Customer $customer): array
    {
        return [
            'id' => $customer->getId(),
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'emails' => $this->serializer->normalize($customer->getCustomerEmails()),
            'phoneNumbers' => $this->serializer->normalize($customer->getCustomerPhoneNumbers()),
            'address' => $customer->getCustomerAddress()->toArray(),
        ];
    }

    /**
     * @param Customer $customer
     * @return array
     */
    private function validateCustomer(Customer $customer): array
    {
        $errors = [];
        foreach ($this->validator->validate($customer) as $error) {
            $errors[] = $error->getMessage();
        }

        return $errors;
    }

    /**
     * TODO good to move to auth service
     * @param UserInterface $user
     * @return bool
     */
    private function isAdmin(UserInterface $user): bool
    {
        $admin = 'ROLE_ADMIN'; //fetch from auth service const or any proper place.

        return in_array($admin, $user->getRoles());
    }
}
