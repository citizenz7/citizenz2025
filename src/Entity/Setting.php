<?php

namespace App\Entity;

use App\Repository\SettingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $siteName = null;

    #[ORM\Column(length: 255)]
    private ?string $siteEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $siteLogo = null;

    #[ORM\Column(length: 255)]
    private ?string $siteUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $siteUrlfull = null;

    #[ORM\Column(length: 255)]
    private ?string $siteAdresse = null;

    #[ORM\Column(length: 255)]
    private ?string $siteCp = null;

    #[ORM\Column(length: 255)]
    private ?string $siteVille = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sitePhone = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $siteFooterdescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiteName(): ?string
    {
        return $this->siteName;
    }

    public function setSiteName(string $siteName): static
    {
        $this->siteName = $siteName;

        return $this;
    }

    public function getSiteEmail(): ?string
    {
        return $this->siteEmail;
    }

    public function setSiteEmail(string $siteEmail): static
    {
        $this->siteEmail = $siteEmail;

        return $this;
    }

    public function getSiteLogo(): ?string
    {
        return $this->siteLogo;
    }

    public function setSiteLogo(?string $siteLogo): static
    {
        $this->siteLogo = $siteLogo;

        return $this;
    }

    public function getSiteUrl(): ?string
    {
        return $this->siteUrl;
    }

    public function setSiteUrl(string $siteUrl): static
    {
        $this->siteUrl = $siteUrl;

        return $this;
    }

    public function getSiteUrlfull(): ?string
    {
        return $this->siteUrlfull;
    }

    public function setSiteUrlfull(string $siteUrlfull): static
    {
        $this->siteUrlfull = $siteUrlfull;

        return $this;
    }

    public function getSiteAdresse(): ?string
    {
        return $this->siteAdresse;
    }

    public function setSiteAdresse(string $siteAdresse): static
    {
        $this->siteAdresse = $siteAdresse;

        return $this;
    }

    public function getSiteCp(): ?string
    {
        return $this->siteCp;
    }

    public function setSiteCp(string $siteCp): static
    {
        $this->siteCp = $siteCp;

        return $this;
    }

    public function getSiteVille(): ?string
    {
        return $this->siteVille;
    }

    public function setSiteVille(string $siteVille): static
    {
        $this->siteVille = $siteVille;

        return $this;
    }

    public function getSitePhone(): ?string
    {
        return $this->sitePhone;
    }

    public function setSitePhone(?string $sitePhone): static
    {
        $this->sitePhone = $sitePhone;

        return $this;
    }

    public function getSiteFooterdescription(): ?string
    {
        return $this->siteFooterdescription;
    }

    public function setSiteFooterdescription(string $siteFooterdescription): static
    {
        $this->siteFooterdescription = $siteFooterdescription;

        return $this;
    }
}
