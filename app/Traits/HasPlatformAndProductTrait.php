<?php

namespace App\Traits;


trait HasPlatformAndProductTrait
{

    /**
     * Property for caching platforms.
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected $platforms;

    /**
     * Property for caching products.
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected $products;


    /**
     * User belongs to many platforms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function platforms()
    {
        return $this->belongsToMany('App\Models\Platform', 'charts_access', 'user_id', 'platform_id')->withTimestamps();
    }

    /**
     * Get all platforms as collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPlatforms()
    {
        return (!$this->platforms) ? $this->platforms = $this->platforms()->get() : $this->platforms;
    }



    /**
     * Check if the user has at least one platform.
     *
     * @param int|string|array $platform
     *
     * @return bool
     */
    public function isOnePlatform($platform)
    {
        foreach ($this->getArrayFrom($platform) as $platform)
        {
            if ($this->hasPlatform($platform))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all platforms.
     *
     * @param int|string|array $platform
     *
     * @return bool
     */
    public function isAllPlatforms($platform)
    {
        foreach ($this->getArrayFrom($platform) as $platform)
        {
            if (!$this->hasPlatform($platform))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the user has platform.
     *
     * @param int|string $platform
     *
     * @return bool
     */
    public function hasPlatform($platform)
    {
        return $this->getPlatforms()->contains(function ($model, $key) use ($platform)
        {
            return $platform == $model->id;
        });
    }

    /**
     * Attach platform to a user.
     *
     * @param int $platform
     *
     * @return null|bool
     */
    public function attachPlatform($platform)
    {
        if (!$this->getPlatforms()->contains($platform))
        {
            $this->platforms()->attach($platform);

            return $this->platforms = null;
        }

        return true;
    }

    /**
     * Detach platform from a user.
     *
     * @param int $platform
     *
     * @return int
     */
    public function detachPlatform($platform)
    {
        $this->platforms = null;

        return $this->platforms()->detach($platform);
    }

    /**
     * Detach all platforms from a user.
     *
     * @return int
     */
    public function detachAllPlatforms()
    {
        $this->platforms = null;

        return $this->platforms()->detach();
    }

    /**
     * User belongs to many products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany('App\Models\Platform', 'charts_access', 'user_id', 'product_id')->withTimestamps();
    }

    /**
     * Get all products as collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProducts()
    {
        return (!$this->products) ? $this->products = $this->products()->get() : $this->products;
    }



    /**
     * Check if the user has at least one product.
     *
     * @param int|string|array $product
     *
     * @return bool
     */
    public function isOneProduct($product)
    {
        foreach ($this->getArrayFrom($product) as $product)
        {
            if ($this->hasPlatform($product))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the user has all products.
     *
     * @param int|string|array $product
     *
     * @return bool
     */
    public function isAllProducts($product)
    {
        foreach ($this->getArrayFrom($product) as $product)
        {
            if (!$this->hasPlatform($product))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the user has product.
     *
     * @param int|string $product
     *
     * @return bool
     */
    public function hasProduct($product)
    {
        return $this->getPlatforms()->contains(function ($model, $key) use ($product)
        {
            return $product == $model->id;
        });
    }

    /**
     * Attach product to a user.
     *
     * @param int $product
     *
     * @return null|bool
     */
    public function attachProduct($product)
    {
        if (!$this->getProducts()->contains($product))
        {
            $this->products()->attach($product);

            return $this->products = null;
        }

        return true;
    }

    /**
     * Detach product from a user.
     *
     * @param int $product
     *
     * @return int
     */
    public function detachProduct($product)
    {
        $this->products = null;

        return $this->products()->detach($product);
    }

    /**
     * Detach all products from a user.
     *
     * @return int
     */
    public function detachAllProducts()
    {
        $this->products = null;

        return $this->products()->detach();
    }
}
