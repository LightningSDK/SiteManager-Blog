<?php

namespace lightningsdk\sitemanager_blog\Model;

use lightningsdk\core\Tools\Database;
use lightningsdk\sitemanager\Model\Site;

class Post extends \lightningsdk\blog\Model\PostOverridable {
    public static function loadPosts($where = [], $join = [], $limit = '') {
        $where['blog.site_id'] = Site::getInstance()->id;
        return Database::getInstance()->selectAll(
            [
                'from' => static::TABLE,
                'join' => array_merge($join, static::joinAuthorCatTables()),
            ],
            $where,
            [
                static::TABLE . '.*',
                'blog_author.*',
                'categories' => ['expression' => 'GROUP_CONCAT(blog_blog_category.cat_id)']
            ],
            'GROUP BY ' . static::TABLE . '.blog_id ORDER BY time DESC ' . $limit
        );
    }

    public static function getRecent() {
        static $recent;
        if (empty($recent)) {
            $recent = Database::getInstance()->selectAll(static::TABLE, ['site_id' => Site::getInstance()->id], [], 'ORDER BY time DESC LIMIT 5');
        }
        return $recent;
    }

    public static function getCategory($search_value) {
        return Database::getInstance()->selectRow(
            static::TABLE . static::CATEGORY_TABLE,
            ['cat_url' => ['LIKE', $search_value], 'site_id' => Site::getInstance()->id]
        );
    }

    public static function getAllCategoriesIndexed() {
        static $categories = [];

        if (empty($categories)) {
            $categories = Database::getInstance()->selectAllQuery([
                'from' => static::TABLE . static::CATEGORY_TABLE,
                'indexed_by' => 'cat_id',
                'where' => ['site_id' => Site::getInstance()->id],
                'order_by' => ['category' => 'ASC'],
            ]);
        }

        return $categories;
    }

    public static function getAllCategories($order = 'count', $sort_direction = 'DESC') {
        static $categories = [];
        if (empty($categories[$order][$sort_direction])) {
            $categories[$order][$sort_direction] = Database::getInstance()->selectAll(
                [
                    'from' => static::TABLE . static::BLOG_CATEGORY_TABLE,
                    'join' => ['JOIN', static::TABLE . static::CATEGORY_TABLE, 'USING (cat_id)'],
                ],
                ['site_id' => Site::getInstance()->id],
                [
                    'count' => ['expression' => 'COUNT(*)'],
                    'category',
                    'cat_url'
                ],
                'GROUP BY cat_id ORDER BY `' . $order . '` ' . $sort_direction . ' LIMIT 10'
            );
        }
        return $categories[$order][$sort_direction];
    }
}
