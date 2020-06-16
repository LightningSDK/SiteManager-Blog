<?php

namespace lightningsdk\sitemanager_blog\Model;

use lightningsdk\blog\Model\BlogOverridable;
use lightningsdk\blog\Model\Post;
use lightningsdk\core\Tools\Configuration;
use lightningsdk\core\Tools\Database;
use lightningsdk\sitemanager\Model\Site;

class Blog extends BlogOverridable {
    public function getAuthorID($search_value) {
        return Database::getInstance()->selectField(
            'user_id',
            Post::TABLE . Post::AUTHOR_TABLE,
            ['author_url' => $search_value, 'site_id' => Site::getInstance()->id]
        );
    }

    public function loadContentByURL($url) {
        $this->isList = false;
        $site_id = Site::getInstance()->id;
        $this->posts = Post::loadPosts([
            'url' => $url,
            '#OR' => [
                ['blog_author.site_id' => $site_id],
                ['blog_author.site_id' => null],
            ]
        ]);
        if (!empty($this->posts)) {
            $this->id = $this->posts[0]['blog_id'];
        }
    }

    protected function loadPostCount($where, $join) {
        $where['blog.site_id'] = Site::getInstance()->id;
        $this->post_count = Database::getInstance()->count(
            [
                'from' => Post::TABLE,
                'join' => $join,
            ],
            $where
        );
    }

    protected function loadCategories($force = false) {
        if ($force || empty($this->categories)) {
            $this->categories = Database::getInstance()->selectColumnQuery([
                'select' => ['category', 'cat_url'],
                'from' => 'blog_category',
                'where' => ['site_id' => Site::getInstance()->id],
            ]);
        }
    }

    public static function getSitemapUrls() {
        $web_root = Configuration::get('web_root');
        $blogs = Database::getInstance()->select([
            'from' => Post::TABLE,
        ],
            ['site_id' => Site::getInstance()->id],
            [
                [Post::TABLE => ['blog_time' => 'time']],
                'url',
            ],
            'GROUP BY blog_id'
        );

        $urls = [];
        foreach($blogs as $b) {
            $urls[] = [
                'loc' => $web_root . "/blog/{$b['url']}",
                'lastmod' => date("Y-m-d", $b['blog_time'] ?: time()),
                'changefreq' => 'yearly',
                'priority' => .3,
            ];
        }
        return $urls;
    }
}
