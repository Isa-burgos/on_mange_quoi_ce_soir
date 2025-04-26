<?php

namespace App\Entity;

use App\Enum\MealTime;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $meal_date = null;

    #[ORM\Column(nullable: true, enumType: MealTime::class)]
    private ?MealTime $meal_time = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    private ?User $user_id = null;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\ManyToMany(targetEntity: Recipe::class, inversedBy: 'menus')]
    private Collection $recipes;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMealDate(): ?\DateTimeInterface
    {
        return $this->meal_date;
    }

    public function setMealDate(\DateTimeInterface $meal_date): static
    {
        $this->meal_date = $meal_date;

        return $this;
    }

    public function getMealTime(): ?MealTime
    {
        return $this->meal_time;
    }

    public function setMealTime(?MealTime $meal_time): static
    {
        $this->meal_time = $meal_time;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipes(Recipe $recipeId): static
    {
        if (!$this->recipes->contains($recipeId)) {
            $this->recipes->add($recipeId);
        }

        return $this;
    }

    public function removeRecipes(Recipe $recipeId): static
    {
        $this->recipes->removeElement($recipeId);

        return $this;
    }
}
