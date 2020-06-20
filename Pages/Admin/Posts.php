<?php

namespace lightningsdk\sitemanager_blog\Pages\Admin;

use lightningsdk\core\Tools\ClientUser;
use lightningsdk\core\Model\Permissions;
use lightningsdk\sitemanager\Model\Site;

class Posts extends \lightningsdk\blog\Pages\Admin\Posts {

    public function hasAccess() {
        ClientUser::requireLogin();
        $user = ClientUser::getInstance();
        return $user->hasPermission(Permissions::EDIT_BLOG);
    }

    protected function initSettings() {
        parent::initSettings();
        $site = Site::getInstance();

        $this->preset['site_id'] = [
            'type' => 'hidden',
            'default' => $site->id,
            'force_default_new' => true,
        ];

        $this->accessControl['site_id'] = $site->id;
        $this->links['blog_category']['accessControl'] = ['site_id' => $site->id];
    }
}
