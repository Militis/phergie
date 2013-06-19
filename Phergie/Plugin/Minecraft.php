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
 * @package   Phergie_Plugin_Minecraft
 * @author    MilesMilitis <miles@militis.us>
 * @copyright 2013 MilesMilitis <http://militis.us>
 * @license   http://phergie.org/license New BSD License
 * @link      http://militis.us
 */

/**
 * Useful commands for Minecraft things
 *
 * @category Phergie
 * @package  Phergie_Plugin_Minecraft
 * @author   MilesMilitis <miles@militis.us>
 * @license  http://phergie.org/license New BSD License
 * @link     http://militis.us
 * @uses     Phergie_Plugin_Command pear.phergie.org
 */
class Phergie_Plugin_Minecraft extends Phergie_Plugin_Abstract
{
    /**
     * Cache plugin
     *
     * @var Phergie_Plugin_Cache
     */
    protected $cache;
    
    /**
     * HTTP plugin
     *
     * @var Phergie_Plugin_Http
     */
    protected $http;
    
    /**
     * Mojang Status JSON URL
     *
     * @var string
     */
    protected $statusUrl = 'http://status.mojang.com/check';
    
    /**
     * Timestamp for the last instance in which an event was received
     *
     * @var int
     */
    protected $lastEvent;
    
    /**
     * Initialize event timestamp upon connecting to the server.
     *
     * @return void
     */
    public function onConnect()
    {
        $this->lastEvent = time();
    }
    
    /**
     * 
     */
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
        $this->plugins->getPlugin('Cache');
        $this->cache = $this->plugins->cache;
        $this->plugins->getPlugin('Http');
        $this->http = $this->plugins->http;
    }

    /**
     * Updates the timestamp since the last received event when a new event
     * arrives.
     *
     * @return void
     */
    public function preEvent()
    {
        $this->lastEvent = time();
    }
    
    /**
     * Performs a self ping if the event threshold has been exceeded or
     * issues a termination command if the ping threshold has been exceeded.
     *
     * @return void
     */
    public function onTick()
    {
        $time = time();
        if (
            $time - $this->lastEvent > $this->getConfig('status.event', 300)
        ) {
            $status = $this->checkStatus();
            $this->lastPing = $time;
            if ($status != 'All Mojang services appear to be up.')
            {
                $this->doPrivmsg($this->getConfig('status.channels'), $status);
            }
        }
    }
    
    /**
     * Prints MC Status
     *
     *
     * @return void
     */
    public function onCommandMcstatus()
    {
        $status = $this->getStatus();
        echo $status;
        
        $this->doPrivmsg($this->event->getSource(), $status);
    }
    
    /**
     * Checks cache to see if status has changed
     *
     * @param string $status The JSON data from status.mojang.net
     *
     * @return bool
     */
    protected function checkCache($status)
    {
        $status = json_decode($status);
        $cache = $this->cache->fetch('statusCache');
        $expire = $this->getConfig('status.expire', 1800);
        $this->debug("Cache expire: {$expire}");
        
        /**
         * If cache expiration is enabled, check if status has expired.
         */
        if ($expire > 0 && isset($cache))
        {
            if ($status == $cache)
            {
                unset($status, $cache, $expire);
                return true;
            }
        }
        unset($status, $cache, $expire);
        return false;
    }

    /**
     * Updates the cache and adds the status. It
     * also handles cleaning the cache of old entries as well.
     *
     * @param string $status The JSON data from status.mojang.net
     *
     * @return bool
     */
    protected function updateCache($status)
    {
        $status = json_decode($status);
        $cache = $this->cache->fetch('statusCache');
        $expire = $this->getConfig('status.expire', 1800);
        $this->debug("Cache expire: {$expire}");
        $time = time();
        
        $this->cache->store('statusCache', $status, $expire);
        unset($status, $expire, $time);
    }
    
    /**
     * 
     */
    protected function getStatus()
    {
        $options = array(
            'timeout' => 3.5,
            'user_agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.60 Safari/537.11'
        );

        $response = $this->http->head($this->statusUrl, array(), $options);

        if ($response->getCode() == 405) { // HEAD request method not allowed
            $response = $this->http->get($this->statusUrl, array(), $options);
        }

        $header = $response->getHeaders('Content-Type');
        $matches = preg_match(
            '#^(application/json)(?:;.*)?$#',
            $header
        );
        
        if ($matches)
        {
            return $response->getContent();
        }
        
        return json_encode(array());
    }
    
    /**
     * 
     */
    protected function checkStatus()
    {
        $down = array();
        $status = $this->getStatus();
        $status = json_decode($status);
        
        if ( ! empty($status))
        {
            foreach ($status as $obj)
            {
                foreach (get_object_vars($obj) as $key => $val)
                {
                    if ($val !== 'green')
                    {
                        $down[] = $key;
                    }
                }
            }
            
            if ( ! empty($down))
            {
                return 'The following Mojang Services appear to be down: '.implode(';', $down);
            }
            
            return 'All Mojang services appear to be up.';
        }
        else
        {
            return 'Unable to fetch status from http://help.mojang.com/ Might want to check http://xpaw.ru/mcstatus/';
        }
        
        return ''; // What the fuck happened here?
    }
    
}
