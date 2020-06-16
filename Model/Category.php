<?php

namespace lightningsdk\sitemanager_blog\Model;

use lightningsdk\blog\Model\CategoryOverridable;
use lightningsdk\sitemanager\Model\Site;

class Category extends CategoryOverridable {
    protected static function getCategoriesQuery($where = [], $order = 'count', $sort_direction = 'DESC') {
        $where['site_id'] = Site::getInstance()->id;
        return parent::getCategoriesQuery($where, $order, $sort_direction);
    }
}
