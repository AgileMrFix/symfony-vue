<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Index(['country_id'])]
#[ORM\Index(['state_id'])]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $postal_code = null;

    #[ORM\Column(length: 255)]
    private ?string $line_1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $line_2 = null;

    #[ORM\OneToOne(mappedBy: 'address', cascade: ['persist', 'remove'])]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?State $state = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getLine1(): ?string
    {
        return $this->line_1;
    }

    public function setLine1(string $line_1): self
    {
        $this->line_1 = $line_1;

        return $this;
    }

    public function getLine2(): ?string
    {
        return $this->line_2;
    }

    public function setLine2(?string $line_2): self
    {
        $this->line_2 = $line_2;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        // unset the owning side of the relation if necessary
        if ($customer === null && $this->customer !== null) {
            $this->customer->setAddress(null);
        }

        // set the owning side of the relation if necessary
        if ($customer !== null && $customer->getAddress() !== $this) {
            $customer->setAddress($this);
        }

        $this->customer = $customer;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }
}
