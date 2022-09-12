<?php

namespace App\Traits;

use App\Models\Article;
use App\Models\Category;

trait CategoryTreeTrait
{
    /**
     * Generate category and subcategory list recursively
     *
     * @param int $parentId
     * @param int $productId
     * @return string
     */
    function getCategoryTreeForDragDropSorting($parentId, $productId)
    {
        $listHtml = '';
        if (is_null($parentId)) {
            $categories = Category::whereNull('parent_id')
                ->where('product_id', $productId)
                ->orderBy('sorting', 'ASC')
                ->get();
        } else {
            $categories = Category::where('parent_id', $parentId)
                ->where('product_id', $productId)
                ->orderBy('sorting', 'ASC')
                ->get();
        }

        if ($categories->isNotEmpty()) {
            $listHtml .= '<ul class="dd-list">';
            foreach ($categories as $category) {
                $listHtml .= '<li class="dd-item" data-id="' . $category->id . '">';
                $listHtml .= view('categories.list_item', compact('category'));

                $listHtml .= $this->getCategoryTreeForDragDropSorting($category->id, $productId);
                $listHtml .= "</li>";
            }
            $listHtml .= '</ul>';
        }

        return $listHtml;
    }

    /**
     * Generate article list with level 2
     *
     * @param int $productId
     * @param int $parentId
     * @param int $level
     * @return string
     */
    public function getArticleListWithLevelTwo($productId, $parentId, $level = 0)
    {
        $listHtml = '';
        if (is_null($parentId)) {
            $children = Category::whereNull('parent_id')
                ->where('product_id', $productId)
                ->orderBy('sorting', 'ASC')
                ->get();
            if ($children->isNotEmpty()) {
                foreach ($children as $category) {
                    $listHtml .= $this->getArticleListWithLevelTwo($productId, $category->id, $level + 1);
                }
            }
        } else {
            $category = Category::findOrFail($parentId);
            $children = Category::where('parent_id', $parentId)
                ->where('product_id', $productId)
                ->orderBy('sorting', 'ASC')
                ->get();

            if ($children->isNotEmpty()) {
                if ($level == 1) {
                    $listHtml .= '<h2>' . $category->title . '</h2>';
                    $listHtml .= '<div class="row equal padding-bottom-10">';
                }

                foreach ($children as $category) {
                    $listHtml .= $this->getArticleListWithLevelTwo($productId, $category->id, $level + 1);
                }

                if ($level == 1) {
                    $listHtml .= '</div>';
                }
            } else {
                // Get articles based on category
                $articles = Article::whereHas('categories', function ($q) use ($category) {
                    $q->where('id', $category->id);
                })->get();

                if ($level == 1 && $articles->isNotEmpty()) {
                    $listHtml .= view('front_end.doc_home_item1', compact('productId', 'category', 'articles'));
                } else if ($level == 2) {
                    $listHtml .= view('front_end.doc_home_item2', compact('productId', 'category', 'articles'));
                }
            }
        }

        return $listHtml;
    }

    /**
     * Generate article list with level 1
     *
     * @param int $productId
     * @return string
     */
    public function getArticleListWithLevelOne($productId)
    {
        $listHtml = '';
        $count = 0;
        $children = Category::whereNull('parent_id')->orderBy('sorting', 'ASC')->get();
        if ($children->isNotEmpty()) {
            foreach ($children as $category) {
                // Get articles based on category
                $articles = Article::whereHas('categories', function ($q) use ($category) {
                    $q->where('id', $category->id);
                })->get();

                if ($articles->isEmpty()) {
                    continue;
                }

                if ($count % 3 == 0) {
                    $listHtml .= '<div class="row equal padding-bottom-10">';
                }

                $listHtml .= view('front_end.doc_home_item2', compact('productId', 'category', 'articles'));

                if ($count % 3 == 2) {
                    $listHtml .= '</div>';
                }

                $count++;
            }
        }

        return $listHtml;
    }

    /**
     * Generate article list with level 2
     *
     * @param int $productId
     * @param int $parentId
     * @param int $level
     * @return string
     */
    public function getArticleListWithAccordion($productId, $parentId, $level = 0)
    {
        $listHtml = '';
        if (is_null($parentId)) {
            $categories = Category::whereNull('parent_id')->orderBy('sorting', 'ASC')->get();
        } else {
            $categories = Category::where('parent_id', $parentId)->orderBy('sorting', 'ASC')->get();
        }

        if ($categories->isNotEmpty()) {
            $listHtml .= '<div id="accordion_' . $level . $parentId . '">';
            foreach ($categories as $category) {
                // Get articles based on category
                $articles = Article::whereHas('categories', function ($q) use ($category) {
                    $q->where('id', $category->id);
                })->get();

                $listHtml .= '<div class="card">
                                <div class="card-header">
                                    <a class="card-link" data-toggle="collapse" href="#collapse_' . $category->id . '">
                                        ' . $category->title . '
                                    </a>
                                </div>
                                <div id="collapse_' . $category->id . '" class="collapse" data-parent="#accordion_' . $level . $parentId . '">
                                    <div class="card-body">';

                $listHtml .= view('front_end.doc_home_item3', compact('productId', 'category', 'articles'));

                $listHtml .= $this->getArticleListWithAccordion($productId, $category->id, $level + 1);

                $listHtml .= '</div>
                            </div>
                        </div>';
            }

            $listHtml .= '</div>';
        }

        return $listHtml;
    }

    /**
     * Generate category and subcategory list recursively for left menu
     *
     * @param int $productId
     * @param int $selectedCategoryId
     * @param int $parentId
     * @return string
     */
    function getCategoryTreeForMenuList($productId, $parentId, $selectedCategoryId = null)
    {
        $listHtml = '';
        if (is_null($parentId)) {
            $children = Category::whereNull('parent_id')
                ->where('product_id', $productId)
                ->orderBy('sorting', 'ASC')
                ->get();
            if ($children->isNotEmpty()) {
                foreach ($children as $category) {
                    $listHtml .= $this->getCategoryTreeForMenuList($productId, $category->id, $selectedCategoryId);
                }
            }
        } else {
            $category = Category::findOrFail($parentId);
            $children = Category::where('parent_id', $parentId)
                ->where('product_id', $productId)
                ->orderBy('sorting', 'ASC')
                ->get();

            if ($children->isNotEmpty()) {
                $active = '';
                if (in_array($selectedCategoryId, $children->pluck('id')->toArray())) {
                    $active = ' active';
                }

                $listHtml .= '<li class="treeview' . $active . '">';
                $listHtml .= view('front_end.item_parent_menu', compact('category'));
                $listHtml .= '<ul class="treeview-menu">';
                foreach ($children as $category) {
                    $listHtml .= $this->getCategoryTreeForMenuList($productId, $category->id, $selectedCategoryId);
                }
                $listHtml .= '</ul>';
                $listHtml .= '</li>';
            } else {
                $listHtml .= view('front_end.item_single_menu', compact('category', 'selectedCategoryId', 'productId'));
            }
        }

        return $listHtml;
    }

    /**
     * Generate category and subcategory dropdown recursively
     *
     * @param int $productId
     * @param int $parentId
     * @param int $level
     * @param array $selectedCategoryIds
     * @return string
     */
    function getCategoryTreeForCheckbox($productId, $parentId, $level, $selectedCategoryIds = [])
    {
        $dropdownHtml = '';
        if (is_null($parentId)) {
            $categories = Category::whereNull('parent_id')
                ->where('product_id', $productId)
                ->orderBy('sorting', 'ASC')
                ->get();
        } else {
            $categories = Category::where('parent_id', $parentId)
                ->where('product_id', $productId)
                ->orderBy('sorting', 'ASC')
                ->get();
        }

        if ($categories->isNotEmpty()) {
            foreach ($categories as $category) {
                $marginLeft = 0;
                if ($level) {
                    $marginLeft = ($level * 22) . 'px';
                }

                $selected = in_array($category->id, $selectedCategoryIds) ? ' checked' : '';

                $dropdownHtml .= '<div class="checkbox icheck" style="margin-left: ' . $marginLeft . '">
                                    <label>
                                        <input name="categories[]" 
                                               type="checkbox"
                                               value="' . $category->id . '"
                                               ' . $selected . '>
                                        ' . $category->title . '
                                    </label>
                                </div>';
                $dropdownHtml .= $this->getCategoryTreeForCheckbox(
                    $productId,
                    $category->id,
                    $level + 1,
                    $selectedCategoryIds
                );
            }
        }

        return $dropdownHtml;
    }

    /**
     * Generate category and subcategory dropdown recursively
     *
     * @param int $productId
     * @param int $parentId
     * @param int $level
     * @param int $selectedCategory
     * @return string
     */
    function getCategoryTreeForDropdown($productId, $parentId, $level, $selectedCategory = 0)
    {
        $dropdownHtml = '';
        if (is_null($parentId)) {
            $categories = Category::whereNull('parent_id')
                ->where('product_id', $productId)
                ->orderBy('sorting', 'ASC')
                ->get();
        } else {
            $categories = Category::where('parent_id', $parentId)
                ->where('product_id', $productId)
                ->orderBy('sorting', 'ASC')
                ->get();
        }

        if ($categories->isNotEmpty()) {
            foreach ($categories as $category) {
                $dash = '';
                if ($level) {
                    $dash = str_repeat("&nbsp;", $level * 4);
                }

                $selected = $selectedCategory == $category->id ? 'selected' : '';

                $dropdownHtml .= '<option value="' . $category->id . '" ' . $selected . '>
                                       ' . $dash . $category->title . '
                                  </option>';
                $dropdownHtml .= $this->getCategoryTreeForDropdown($productId, $category->id, $level + 1, $selectedCategory);
            }
        }

        return $dropdownHtml;
    }

    /**
     * Get article list with category tree based on level
     *
     * @param $productId
     * @return string
     */

    function getArticleListWithCategoryTree($productId)
    {
        $maxLevel = Category::where('product_id', $productId)->max('level');
        if ($maxLevel == 0) {
            return $articleListWithCategoryTree = $this->getArticleListWithLevelOne($productId);
        } else if ($maxLevel == 1) {
            return $articleListWithCategoryTree = $this->getArticleListWithLevelTwo($productId, null);
        } else {
            return $articleListWithCategoryTree = $this->getArticleListWithAccordion($productId, null);
        }
    }
}
