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
     * Queries the OMDb API, processes the result, and sends
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
            's' => $query
        );
        $http = $this->plugins->getPlugin('Http');
        $response = $http->get($url, $params);
        $json = json_decode($response->getContent());
        
        if (property_exists($json, 'Response') AND $json->Response == 'False') {
            $this->doPrivmsg($this->event->getSource(), $json->Error);
            return;
        }
        
        $msg = "Results: ";
        
        foreach ($json->Search as $search) {
            $imdbid = $search->imdbID;
            $title = $search->Title;
            $link = 'http://www.imdb.com/title/' . $imdbid . '/';
            
            $msg .= "[ $link ] $title\n";
        }
        
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
