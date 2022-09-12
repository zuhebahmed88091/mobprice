<?php

namespace App\Traits;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;

trait ProductTrait
{
    /**
     * Get first product as selected product from product list when no product selected
     *
     * @param $products
     * @return int
     */
    function getSelectedProductId($products)
    {
        $selectedProductId = 0;
        if ($products->isNotEmpty()) {
            foreach ($products as $product) {
                $selectedProductId = $product->id;
                break;
            }
        }
        return $selectedProductId;
    }

    /**
     * Get selected product details
     *
     * @param $productId
     * @return string|Product
     */
    function getSelectedProduct($productId)
    {
        $selectedProduct = '';
        if (!empty($productId)) {
            $selectedProduct = Product::findOrFail($productId);
        }
        return $selectedProduct;
    }
}
