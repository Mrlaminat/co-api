<?php

namespace App\Entity;

use App\Repository\CustomerPhoneNumberRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerPhoneNumberRepository::class)
 */
class CustomerPhoneNumber implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=16)
     * @Assert\Regex(
     *     pattern     = "/^\+?[0-9]{6,16}$/",
     *     message     = "The phone number has forbidden symbols"
     * )
     */
    private ?string $phoneNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="customerPhoneNumber")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Customer $customer;

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
    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return $this
     */
    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return array(
            'id' => $this->getId(),
            'phoneNumber' => $this->getPhoneNumber()
        );
    }
}
