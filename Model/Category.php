<?php

namespace lightningsdk\sitemanager_blog\Model;

use lightningsdk\blog\Model\CategoryCore;
use lightningsdk\sitemanager\Model\Site;

class Category extends CategoryCore {
    protected static function getCategoriesQuery($where = [], $order = 'count', $sort_direction = 'DESC') {
        $where['site_id'] = Site::getInstance()->id;
        return parent::getCategoriesQuery($where, $order, $sort_direction);
    }
}
