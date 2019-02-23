<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductsRepository")
 */
class Products
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Заглавието трябва да е минимум 2 символа",
     *      maxMessage = "Заглавието трябва да е не повече от 255 символа"
     * )
     */
    private $head;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     * @Assert\Length(
     *      max = 512,
     *      maxMessage = "Заглавието трябва да е не повече от 512 символа"
     * )
     */
    private $cultiv;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     * @Assert\Length(
     *      max = 512,
     *      maxMessage = "Заглавието трябва да е не повече от 512 символа"
     * )
     */
    private $usefor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?int
    {
        return $this->category;
    }

    public function setCategory(int $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getHead(): ?string
    {
        return $this->head;
    }

    public function setHead(string $head): self
    {
        $this->head = $head;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCultiv(): ?string
    {
        return $this->cultiv;
    }

    public function setCultiv(?string $cultiv): self
    {
        $this->cultiv = $cultiv;

        return $this;
    }

    public function getUsefor(): ?string
    {
        return $this->usefor;
    }

    public function setUsefor(?string $usefor): self
    {
        $this->usefor = $usefor;

        return $this;
    }

    public function getPic(): ?string
    {
        return $this->pic;
    }

    public function setPic(?string $pic): self
    {
        $this->pic = $pic;

        return $this;
    }
}
