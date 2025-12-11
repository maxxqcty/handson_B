<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $ProductName = null;

    #[ORM\Column(length: 255)]
    private ?string $Type = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $Price = null;

    #[ORM\Column(length: 255)]
    private ?string $Unit = null;

    #[ORM\Column(length: 255)]
    private ?string $Stock = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->ProductName;
    }

    public function setProductName(string $ProductName): static
    {
        $this->ProductName = $ProductName;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): static
    {
        $this->Type = $Type;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->Price;
    }

    public function setPrice(string $Price): static
    {
        $this->Price = $Price;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->Unit;
    }

    public function setUnit(string $Unit): static
    {
        $this->Unit = $Unit;

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->Stock;
    }

    public function setStock(string $Stock): static
    {
        $this->Stock = $Stock;

        return $this;
    }
}
