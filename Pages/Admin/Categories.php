<?php

namespace lightningsdk\sitemanager_blog\Pages\Admin;

use lightningsdk\sitemanager\Model\Site;

class Categories extends \lightningsdk\blog\Pages\Categories {
    protected function initSettings() {
        parent::initSettings();
        $site = Site::getInstance();
        $this->accessControl = ['site_id' => $site->id];
        $this->preset['site_id'] = [
            'type' => 'hidden',
            'default' => $site->id,
            'force_default_new' => true,
        ];
    }
}
