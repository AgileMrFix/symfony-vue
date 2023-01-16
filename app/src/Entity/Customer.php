<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Filters\CustomerSearch;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ORM\UniqueConstraint('email_uniq_idx', ['email'])]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext:['groups' => ['customer:index']], paginationEnabled: true)
    ]

)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'first_name', 'last_name', 'email'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(SearchFilter::class, properties: ['address.country.id' => 'exact'])]
#[ApiFilter(CustomerSearch::class, properties: ['first_name','last_name','email'])]

//#[ApiFilter(SearchFilter::class, properties: ['first_name', 'last_name'])]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('customer:index')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('customer:index')]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[Groups('customer:index')]

    private ?string $last_name = null;

    #[ORM\Column(length: 100)]
    #[Groups('customer:index')]
    private ?string $email = null;

    #[ORM\OneToOne(inversedBy: 'customer', cascade: ['persist', 'remove'])]
    #[Groups('customer:index')]
    private ?Address $address = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Phone::class, orphanRemoval: true)]
    private Collection $phones;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, Phone>
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones->add($phone);
            $phone->setCustomer($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phones->removeElement($phone)) {
            // set the owning side to null (unless already changed)
            if ($phone->getCustomer() === $this) {
                $phone->setCustomer(null);
            }
        }

        return $this;
    }
}
