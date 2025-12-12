<?php

namespace App\Entity;

use App\Repository\BurialRecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BurialRecordRepository::class)]
class BurialRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Deceased $deacesed = null;

    /**
     * @var Collection<int, BurialPlot>
     */
    #[ORM\OneToMany(targetEntity: BurialPlot::class, mappedBy: 'burialRecord')]
    private Collection $plot_id;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $burial_date = null;

    #[ORM\Column(length: 255)]
    private ?string $funeral_home = null;

    #[ORM\Column]
    private ?\DateTime $record_created_at = null;

    #[ORM\Column]
    private ?\DateTime $record_updated_at = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notes = null;

    public function __construct()
    {
        $this->plot_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeacesed(): ?Deceased
    {
        return $this->deacesed;
    }

    public function setDeacesed(?Deceased $deacesed): static
    {
        $this->deacesed = $deacesed;

        return $this;
    }

    /**
     * @return Collection<int, BurialPlot>
     */
    public function getPlotId(): Collection
    {
        return $this->plot_id;
    }

    public function addPlotId(BurialPlot $plotId): static
    {
        if (!$this->plot_id->contains($plotId)) {
            $this->plot_id->add($plotId);
            $plotId->setBurialRecord($this);
        }

        return $this;
    }

    public function removePlotId(BurialPlot $plotId): static
    {
        if ($this->plot_id->removeElement($plotId)) {
            // set the owning side to null (unless already changed)
            if ($plotId->getBurialRecord() === $this) {
                $plotId->setBurialRecord(null);
            }
        }

        return $this;
    }

    public function getBurialDate(): ?\DateTime
    {
        return $this->burial_date;
    }

    public function setBurialDate(\DateTime $burial_date): static
    {
        $this->burial_date = $burial_date;

        return $this;
    }

    public function getFuneralHome(): ?string
    {
        return $this->funeral_home;
    }

    public function setFuneralHome(string $funeral_home): static
    {
        $this->funeral_home = $funeral_home;

        return $this;
    }

    public function getRecordCreatedAt(): ?\DateTime
    {
        return $this->record_created_at;
    }

    public function setRecordCreatedAt(\DateTime $record_created_at): static
    {
        $this->record_created_at = $record_created_at;

        return $this;
    }

    public function getRecordUpdatedAt(): ?\DateTime
    {
        return $this->record_updated_at;
    }

    public function setRecordUpdatedAt(\DateTime $record_updated_at): static
    {
        $this->record_updated_at = $record_updated_at;

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
}
