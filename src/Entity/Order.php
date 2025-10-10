<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $carrierName = null;

    #[ORM\Column]
    private ?float $carrierPrice = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $delivery = null;

    /**
     * @var Collection<int, OrederDetail>
     */
    #[ORM\OneToMany(targetEntity: OrederDetail::class, mappedBy: 'myOrder', cascade: ['persist'])]
    private Collection $orederDetails;

    /**
     * 1 : En atante de paiment 
     * 2 : Paiment validé
     * 3 : En cours de préparation
     * 4 : Expédié
     * 5 : Annulé
     */
    #[ORM\Column]
    private ?int $state = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $stripe_session_id = null;



    public function __construct()
    {
        $this->orederDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getcarrierName(): ?string
    {
        return $this->carrierName;
    }

    public function setcarrierName(string $carrierName): static
    {
        $this->carrierName = $carrierName;

        return $this;
    }

    public function getcarrierPrice(): ?float
    {
        return $this->carrierPrice;
    }

    public function setcarrierPrice(float $carrierPrice): static
    {
        $this->carrierPrice = $carrierPrice;

        return $this;
    }

    public function getdelivery(): ?string
    {
        return $this->delivery;
    }

    public function setdelivery(string $delivery): static
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * @return Collection<int, OrederDetail>
     */
    public function getOrederDetails(): Collection
    {
        return $this->orederDetails;
    }

    public function addOrederDetail(OrederDetail $orederDetail): static
    {
        if (!$this->orederDetails->contains($orederDetail)) {
            $this->orederDetails->add($orederDetail);
            $orederDetail->setMyOrder($this);
        }

        return $this;
    }

    public function removeOrederDetail(OrederDetail $orederDetail): static
    {
        if ($this->orederDetails->removeElement($orederDetail)) {
            // set the owning side to null (unless already changed)
            if ($orederDetail->getMyOrder() === $this) {
                $orederDetail->setMyOrder(null);
            }
        }

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
    public function getTotalWt()
    {

        (float)$totalTTC = 0;
        $products = $this->getOrederDetails();
        foreach ($products as $product) {
            $coefficient = 1 + ($product->getProductTva() / 100);
            $totalTTC += ($coefficient * $product->getProductPrice()) * $product->getProductQuantity();
        }
        $formated_totalTTC = number_format($totalTTC + $this->getcarrierPrice(), 2);
        return $formated_totalTTC;
    }
    public function getTotalTVA()
    {
        $totalTva = 0;
        $products = $this->getOrederDetails();
        foreach ($products as $product) {
            $coefficient = $product->getProductTva() / 100;
            $totalTva += $coefficient * $product->getProductPrice();
        }
        $formated_totalTva = number_format($totalTva, 2);
        return $formated_totalTva;
    }
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getStripeSessionId(): ?string
    {
        return $this->stripe_session_id;
    }

    public function setStripeSessionId(?string $stripe_session_id): static
    {
        $this->stripe_session_id = $stripe_session_id;

        return $this;
    }

 
}
