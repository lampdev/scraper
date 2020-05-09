<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var Manufacturer|null
     *
     * @ORM\ManyToOne(targetEntity="Manufacturer", inversedBy="products")
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id")
     */
    private $manufacturer;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=256, nullable=false)
     */
    private $title;

    /**
     * @var string|null
     *
     * @ORM\Column(name="price", type="string", length=32, nullable=true)
     */
    private $price;

    /**
     * @var string|null
     *
     * @ORM\Column(name="soldBy", type="string", length=256, nullable=true)
     */
    private $soldby;

    /**
     * @var float|null
     *
     * @ORM\Column(name="rating", type="float", precision=2, scale=1, nullable=true)
     */
    private $rating;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set price.
     *
     * @param string|null $price
     *
     * @return Product
     */
    public function setPrice($price = null)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return string|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set soldby.
     *
     * @param string|null $soldby
     *
     * @return Product
     */
    public function setSoldby($soldby = null)
    {
        $this->soldby = $soldby;

        return $this;
    }

    /**
     * Get soldby.
     *
     * @return string|null
     */
    public function getSoldby()
    {
        return $this->soldby;
    }

    /**
     * Set rating.
     *
     * @param float|null $rating
     *
     * @return Product
     */
    public function setRating($rating = null)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating.
     *
     * @return float|null
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set category.
     *
     * @param \App\Entity\Category|null $category
     *
     * @return Product
     */
    public function setCategory(\App\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return \App\Entity\Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set manufacturer.
     *
     * @param \App\Entity\Manufacturer|null $manufacturer
     *
     * @return Product
     */
    public function setManufacturer(\App\Entity\Manufacturer $manufacturer = null)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get manufacturer.
     *
     * @return \App\Entity\Manufacturer|null
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }
}
