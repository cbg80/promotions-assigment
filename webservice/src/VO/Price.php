<?php

declare(strict_types=1);

namespace App\VO;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Price
{
    /**
     * @ORM\Column(type="integer")
     */
    private int $original;

    private ?int $final = NULL;

    private ?string $discountPercentage = NULL;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private string $currency = "EUR";

    public function getOriginal(): int
    {
        return $this->original;
    }

    public function setOriginal(int $original): self
    {
        $this->original = $original;

        return $this;
    }

    public function getFinal(): ?int
    {
        return $this->final;
    }

    public function setFinal(?int $final): self
    {
        $this->final = $final;

        return $this;
    }

    public function getDiscountPercentage(): ?string
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(?string $discountPercentage): self
    {
        $this->discountPercentage = $discountPercentage;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
    
    public function evalFinal(string $discountPercentage = NULL): int
    {
        if (isset($discountPercentage, $this->discountPercentage)) {
            if (is_null($discountPercentage)) {
                $discountPercentage = $this->$discountPercentage;
            }
        } else {
            $discountPercentage = 0;
        }
        
        return (int)($this->original * (1 - (float)$discountPercentage/100));
    }
}
