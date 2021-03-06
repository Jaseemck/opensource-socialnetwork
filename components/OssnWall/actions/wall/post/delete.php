<?php
/**
 *    OpenSource-SocialNetwork
 *
 * @package   (Informatikon.com).ossn
 * @author    OSSN Core Team <info@opensource-socialnetwork.com>
 * @copyright 2014 iNFORMATIKON TECHNOLOGIES
 * @license   General Public Licence http://opensource-socialnetwork.com/licence
 * @link      http://www.opensource-socialnetwork.com/licence
 */
$ossnwall = new OssnWall;
$id = input('post');
$post = $ossnwall->GetPost($id);
if ($post->type == 'user' && ossn_isAdminLoggedin()) {
    if ($post->poster_guid !== ossn_loggedin_user()->guid) {
        if (!ossn_is_xhr()) {
            ossn_trigger_message(ossn_print('post:delete:fail'), 'error');
            redirect(REF);
        } else {
            echo 0;
            exit;
        }
    }
}
if ($post->type == 'group' && !ossn_isAdminLoggedin()) {
    $group = new OssnGroup;
    $group = $group->getGroup($post->owner_guid);
    if ($post->poster_guid !== ossn_loggedin_user()->guid || ossn_loggedin_user()->guid !== $group->owner_guid) {
        if (!ossn_is_xhr()) {
            ossn_trigger_message(ossn_print('post:delete:fail'), 'error');
            redirect(REF);
        } else {
            echo 0;
            exit;
        }
    }
}
if ($ossnwall->deletePost($id)) {
    if (ossn_is_xhr()) {
        echo 1;
    } else {
        ossn_trigger_message(ossn_print('post:delete:success'), 'success');
        redirect(REF);
    }
} else {
    if (ossn_is_xhr()) {
        echo 0;
    } else {
        ossn_trigger_message(ossn_print('post:delete:fail'), 'error');
        redirect(REF);
    }
}