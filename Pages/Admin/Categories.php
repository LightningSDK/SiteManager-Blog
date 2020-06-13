<?php

namespace Source\SiteAdmin;

use Source\Model\Site;

class Categories extends \lightningsdk\core\Pages\Categories {
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
