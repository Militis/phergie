<?php
/**
 * Phergie
 *
 * PHP version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://phergie.org/license
 *
 * @category  Phergie
 * @package   Phergie_Plugin_IMDB
 * @author    MilesMilitis <miles@militis.us>
 * @copyright 2012 MilesMilitis <http://militis.us>
 * @license   http://phergie.org/license New BSD License
 * @link      http://militis.us
 */

/**
 * Search IMDB via OMDb API
 *
 * @category Phergie
 * @package  Phergie_Plugin_IMDB
 * @author   MilesMilitis <miles@militis.us>
 * @license  http://phergie.org/license New BSD License
 * @link     http://militis.us
 * @uses     Phergie_Plugin_Command pear.phergie.org
 * @uses     Phergie_Plugin_Http pear.phergie.org
 */
class Phergie_Plugin_Imdb extends Phergie_Plugin_Abstract
{
    /**
     * Checks for dependencies.
     *
     * @return void
     */
    public function onLoad()
    {
        $plugins = $this->getPluginHandler();
        $plugins->getPlugin('Command');
        $plugins->getPlugin('Http');
    }

    /**
     * Queries the OMDb API, processes the first result, and sends
     * a message back to the current event source.
     *
     * @param string $query Search term
     *
     * @return object OMDb result object
     */
    protected function queryOmdb($query)
    {
        $url = 'http://www.omdbapi.com/';
        $params = array(
            'r' => 'json',
            't' => $query
        );
        $http = $this->plugins->getPlugin('Http');
        $response = $http->get($url, $params);
        $json = $response->getContent();
        
        if ($json->Response == 'False') {
            $this->doPrivmsg($this->event->getSource(), $json->Error);
            return;
        }

        $nick = $this->event->getNick();
        $imdbid = $json->imdbID;
        $title = $json->Title;
        $link = 'http://www.imdb.com/title/' . $imdbid . '/';
        
        $msg = "[ $link ] $title";
        $this->doPrivmsg($this->event->getSource(), $msg);
    }

    /**
     * Returns the first result of an IMDB search.
     *
     * @param string $query Search query
     *
     * @return void
     */
    public function onCommandImdb($query)
    {
        $this->queryOmdb($query);
    }

}
