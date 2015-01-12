<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

// +-------------------------------------------------------------------+
// | WiFiDog Authentication Server                                     |
// | =============================                                     |
// |                                                                   |
// | The WiFiDog Authentication Server is part of the WiFiDog captive  |
// | portal suite.                                                     |
// +-------------------------------------------------------------------+
// | PHP version 5 required.                                           |
// +-------------------------------------------------------------------+
// | Homepage:     http://www.wifidog.org/                             |
// | Source Forge: http://sourceforge.net/projects/wifidog/            |
// +-------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or     |
// | modify it under the terms of the GNU General Public License as    |
// | published by the Free Software Foundation; either version 2 of    |
// | the License, or (at your option) any later version.               |
// |                                                                   |
// | This program is distributed in the hope that it will be useful,   |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of    |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the     |
// | GNU General Public License for more details.                      |
// |                                                                   |
// | You should have received a copy of the GNU General Public License |
// | along with this program; if not, contact:                         |
// |                                                                   |
// | Free Software Foundation           Voice:  +1-617-542-5942        |
// | 59 Temple Place - Suite 330        Fax:    +1-617-542-2652        |
// | Boston, MA  02111-1307,  USA       gnu@gnu.org                    |
// |                                                                   |
// +-------------------------------------------------------------------+

/**
 * @package    WiFiDogAuthServer
 * @subpackage ContentClasses
 * @author     Benoit Grégoire <benoitg@coeus.ca>
 * @copyright  2005-2006 Benoit Grégoire, Technologies Coeus inc.
 * @version    Subversion $Id$
 * @link       http://www.wifidog.org/
 */

/**
 * An IFrame to integrate an external HTML content from a REST-style get query.
 *
 * @package    WiFiDogAuthServer
 * @subpackage ContentClasses
 * @author     Benoit Grégoire <benoitg@coeus.ca>
 * @copyright  2005-2006 Benoit Grégoire, Technologies Coeus inc.
 */
class IFrameRest extends IFrame
{
    /**
     * Constructor
     *
     * @param string $content_id Content id
     *
     * @return void     */
    protected function __construct($content_id)
    {
        parent::__construct($content_id);
    }

    /**
     * Return the IFrame URL generated by parsing the data in the URL field.
     *
     * @return string HTML code for the administration interface

     */
    private function getGeneratedUrl()
    {
        $patterns = array('/\{node_id\}/', '/\{user_id\}/', '/\{last_viewed\}/');

        $current_node = Node::getCurrentNode();

        if ($current_node) {
            $node_id = $current_node->getId();
        } else {
            $node_id = '';
        }

        $current_user = User::getCurrentUser();

        if ($current_user) {
            $user_id = $current_user->getId();
        } else {
            $user_id = '';
        }

        $user_last_viewed_ts = $this->getLastDisplayTimestamp($current_user);

        if ($user_last_viewed_ts) {
            $user_last_viewed = date('c',$user_last_viewed_ts);
        } else {
            $user_last_viewed = null;
        }

        $replacements = array(urlencode($node_id), urlencode($user_id), urlencode($user_last_viewed));
        $url = $this->getUrl();
        $new_url = preg_replace($patterns, $replacements, $url);

        return $new_url;
    }

    /**
     * Shows the administration interface for IFrameRest
     *
     * @return string HTML code for the administration interface
     */
    public function getAdminUI($subclass_admin_interface = null, $title=null)
    {
        // Init values
        $html = '';
 		$html .= "<ul class='admin_element_list'>\n";

        $html .= "<li class='admin_element_item_container'>\n";
        $html .= "<div class='admin_element_label'>"._("Actual URL after substitution")." : </div>\n";
        $html .= "<div class='admin_element_data'>\n";
        $html .= "<p>\n";
        $html .= _("The IFrameRest content type is meant to allow the result of REST-style queries to remote systems to be displayed in a IFrame.  To that end, The following strings will be replaced in the URL:");
        $html .= "</p>\n";

        $html .= "<table>\n";
        $html .= "<tr><td>{node_id}</td>\n";
        $html .= "<td>";
        $html .= _("Will be replaced by the urlencoded node_id of the node
                    where the content is displayed, or an empty string if there is no
                    current node</td></tr>");
        $html .= "<tr><td>{user_id}</td>\n";
        $html .= "<td>";
        $html .= _("Will be replaced by the user_id");
        $html .= "</td></tr>\n";
        $html .= "<tr><td>{user_last_viewed}</td>\n";
        $html .= "<td>";
        $html .= _("will be replaced by a ISO-8601 timestamp of the date the user was last shown this content, or an empty string if the user was never presented with this IFrame.");
        $html .= "</td></tr>\n";
        $html .= "</table>\n";

        $generated_url = $this->getGeneratedUrl();

        $html .= "<p>Example of your generated URL:</p>\n";

        $html .= "<a href='$generated_url'>$generated_url</a>";
        $html .= "</div>\n";
        $html .= "</li>\n";

        return parent::getAdminUI($html, $title);
    }

    /**
     * Reloads the object from the database.
     *
     * Should normally be called after a set operation.
     *
     * This function is private because calling it from a subclass will call
     * the constructor from the wrong scope
     *
     * @return void

     */
    private function refresh()
    {
        $this->__construct($this->id);
    }

}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */


