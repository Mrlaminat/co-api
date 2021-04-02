<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @Assert\NotNull(message="Please enter an first name")
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9_-]*$/i",
     *     message     = "The first name has forbidden symbols"
     * )
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotNull(message="Please enter a last name")
     * @Assert\Length(
     *      min = 2,
     *      max = 64,
     *      minMessage = "Your last name must be at least {{ limit }} characters long",
     *      maxMessage = "Your last name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex(
     *     pattern     = "/^[a-z0-9_-]*$/i",
     *     message     = "The last name has forbidden symbols"
     * )
     */
    private ?string $lastName;

    /**
     * @ORM\OneToMany(targetEntity=CustomerEmail::class, mappedBy="customer", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid()
     */
    private Collection $customerEmails;

    /**
     * @ORM\OneToMany(
     *     targetEntity=CustomerPhoneNumber::class,
     *     mappedBy="customer",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @Assert\Valid()
     */
    private Collection $customerPhoneNumbers;

    /**
     * @ORM\OneToOne(targetEntity=CustomerAddress::class, mappedBy="customer", cascade={"persist", "remove"})
     * @Assert\Valid()
     * @Assert\NotNull(message="Please add address properties")
     */
    private ?CustomerAddress $customerAddress;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
        $this->customerEmails = new ArrayCollection();
        $this->customerPhoneNumbers = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection|CustomerEmail[]
     */
    public function getCustomerEmails(): Collection
    {
        return $this->customerEmails;
    }

    /**
     * @param CustomerEmail $customerEmail
     * @return $this
     */
    public function addCustomerEmail(CustomerEmail $customerEmail): self
    {
        $filteredEmails = $this->customerEmails->filter(function (CustomerEmail $email) use ($customerEmail) {
            return $email->getEmail() === $customerEmail->getEmail();
        });

        if ($filteredEmails->isEmpty()) {
            $this->customerEmails[] = $customerEmail;
            $customerEmail->setCustomer($this);
        }

        return $this;
    }

    /**
     * @param CustomerEmail $customerEmail
     * @return $this
     */
    public function removeCustomerEmail(CustomerEmail $customerEmail): self
    {
        if ($this->customerEmails->removeElement($customerEmail)) {
            if ($customerEmail->getCustomer() === $this) {
                $customerEmail->setCustomer(null);
            }
        }


        return $this;
    }

    /**
     * @return Collection|CustomerPhoneNumber[]
     */
    public function getCustomerPhoneNumbers(): Collection
    {
        return $this->customerPhoneNumbers;
    }

    /**
     * @param CustomerPhoneNumber $customerPhoneNumber
     * @return $this
     */
    public function addCustomerPhoneNumber(CustomerPhoneNumber $customerPhoneNumber): self
    {
        $filteredPhoneNumbers = $this->customerPhoneNumbers->filter(
            function (CustomerPhoneNumber $phoneNumber) use ($customerPhoneNumber) {
                return $phoneNumber->getPhoneNumber() === $customerPhoneNumber->getPhoneNumber();
            }
        );

        if ($filteredPhoneNumbers->isEmpty()) {
            $this->customerPhoneNumbers[] = $customerPhoneNumber;
            $customerPhoneNumber->setCustomer($this);
        }

        return $this;
    }

    /**
     * @param CustomerPhoneNumber $customerPhoneNumber
     * @return $this
     */
    public function removeCustomerPhoneNumber(CustomerPhoneNumber $customerPhoneNumber): self
    {
        if ($this->customerPhoneNumbers->removeElement($customerPhoneNumber)) {
            if ($customerPhoneNumber->getCustomer() === $this) {
                $customerPhoneNumber->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return CustomerAddress|null
     */
    public function getCustomerAddress(): ?CustomerAddress
    {
        return $this->customerAddress;
    }

    /**
     * @param CustomerAddress $customerAddress
     * @return $this
     */
    public function setCustomerAddress(CustomerAddress $customerAddress): self
    {
        if ($customerAddress->getCustomer() !== $this) {
            $customerAddress->setCustomer($this);
        }

        $this->customerAddress = $customerAddress;

        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
