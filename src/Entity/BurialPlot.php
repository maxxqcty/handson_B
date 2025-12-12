<?php

namespace App\Entity;

use App\Repository\BurialPlotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BurialPlotRepository::class)]
class BurialPlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $plot_number = null;

    #[ORM\Column(length: 255)]
    private ?string $section = null;

    #[ORM\Column(length: 255)]
    private ?string $row_num = null;

    #[ORM\Column]
    private ?bool $is_occupied = null;

    #[ORM\Column(length: 255)]
    private ?string $size = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notes = null;

    #[ORM\ManyToOne(inversedBy: 'plot_id')]
    private ?BurialRecord $burialRecord = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlotNumber(): ?string
    {
        return $this->plot_number;
    }

    public function setPlotNumber(string $plot_number): static
    {
        $this->plot_number = $plot_number;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(string $section): static
    {
        $this->section = $section;

        return $this;
    }

    public function getRowNum(): ?string
    {
        return $this->row_num;
    }

    public function setRowNum(string $row_num): static
    {
        $this->row_num = $row_num;

        return $this;
    }

    public function isOccupied(): ?bool
    {
        return $this->is_occupied;
    }

    public function setIsOccupied(bool $is_occupied): static
    {
        $this->is_occupied = $is_occupied;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): static
    {
        $this->size = $size;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getBurialRecord(): ?BurialRecord
    {
        return $this->burialRecord;
    }

    public function setBurialRecord(?BurialRecord $burialRecord): static
    {
        $this->burialRecord = $burialRecord;

        return $this;
    }
}
