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
 * @package   Phergie_Plugin_Mhykol
 * @author    MilesMilitis <miles@militis.us>
 * @copyright 2012 MilesMilitis <http://militis.us>
 * @license   http://phergie.org/license New BSD License
 * @link      http://militis.us
 */

/**
 * Useful commands for Mhykol Mines
 *
 * @category Phergie
 * @package  Phergie_Plugin_Mhykol
 * @author   MilesMilitis <miles@militis.us>
 * @license  http://phergie.org/license New BSD License
 * @link     http://militis.us
 * @uses     Phergie_Plugin_Command pear.phergie.org
 */
class Phergie_Plugin_Mhykol extends Phergie_Plugin_Abstract
{
    /**
     * HTTP plugin
     *
     * @var Phergie_Plugin_Http
     */
    protected $http;
    
    /**
     * URL for checking Minecraft Clones.
     * 
     * @var url
     */
    protected $clonesUrl = 'https://nook.bassh.net/query/checkclones/';
    
    /**
     * Checks for dependencies.
     *
     * @return void
     */
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
        $this->plugins->getPlugin('Http');
        $this->http = $this->plugins->http;
    }

    /**
     * Prints link to Rules
     *
     *
     * @return void
     */
    public function onCommandRules()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://mhykol.net/rules/');
    }

    /**
     * Prints link to Ranks
     *
     *
     * @return void
     */
    public function onCommandRanks()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://mc.mhykol.com/pages/ranks/');
    }
    
    /**
     * Prints link to Griefing Form
     *
     *
     * @return void
     */
    public function onCommandGrief()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://mhykol.net/grief/');
    }

    /**
     * Prints link to VIP/Partner donation
     *
     *
     * @return void
     */
    public function onCommandVip()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://mhykol.net/shop/');
    }

    /**
     * Prints link to FAQ
     *
     *
     * @return void
     */
    public function onCommandFaq()
    {
        $this->doPrivmsg($this->event->getSource(), '');
    }
    
    /**
     * Prints link to Ban Appeal
     *
     *
     * @return void
     */
    public function onCommandBanappeal()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://mhykol.net/appeal/');
    }
    
    /**
     * Prints link to how to register with Q
     *
     *
     * @return void
     */
    public function onCommandTalkinginirc()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://www.bit.ly/HelloQ');
    }
    
    /**
     * Prints link to how to chat in IRC
     *
     *
     * @return void
     */
    public function onCommandIrc()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://mc.mhykol.com/pages/chat/');
    }
    
    /**
     * Prints link to Dynamic Map
     *
     *
     * @return void
     */
    public function onCommandMap()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://mhykol.net/maps/');
    }

    /**
     * Prints links to Mhykol
     *
     *
     * @return void
     */
    public function onCommandMhykol()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://www.youtube.com/mhykol');
        $this->doPrivmsg($this->event->getSource(), 'http://twitter.com/mhykol');
        $this->doPrivmsg($this->event->getSource(), 'http://www.facebook.com/mhykolmines');
        $this->doPrivmsg($this->event->getSource(), 'http://www.twitch.tv/mhykol');
        $this->doPrivmsg($this->event->getSource(), 'http://mhykol.com');
        // $this->doPrivmsg($this->event->getSource(), '');
    }
    
    /**
     * Prints link to Reset
     *
     *
     * @return void
     */
    public function onCommandReset()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://bit.ly/18Bv27p');
    }
    
    /**
     * Prints link to Update
     *
     *
     * @return void
     */
    public function onCommandUpdate()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://bit.ly/13MH0Jr');
    }
    
    /**
     * Check for Clones
     *
     * @param string $username The user to search
     *
     * @return void
     */
    public function onCommandClone($username)
    {
        $url = $this->clonesUrl.$username;
        
        $options = array(
            'timeout' => 3.5,
            'user_agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.60 Safari/537.11'
        );

        $response = $this->http->head($url, array(), $options);

        if ($response->getCode() == 405) { // HEAD request method not allowed
            $response = $this->http->get($url, array(), $options);
        }

        $header = $response->getHeaders('Content-Type');
        $matches = preg_match(
            '#^(application/json)(?:;.*)?$#',
            $header
        );
        
        if ($matches)
        {
            $json = json_decode(file_get_contents($url));
        }
        else
        {
            $json = array();
        }
        
        if ( ! empty($json))
        {
            $this->doPrivmsg($this->event->getSource(), "Matches:");
            foreach (get_object_vars($json) as $key => $val)
            {
                if ($key === 'players' || $key === 'ips')
                {
                    $this->doPrivmsg($this->event->getSource(), ($key === 'players' ? ucfirst($key).': ' : 'IPs: ').implode(',', $val));
                }
            }
        }
        else
        {
            $this->doPrivmsg($this->event->getSource(), 'No matches.');
        }
    }
    
    /**
     * Prints link to Lotion
     *
     *
     * @return void
     */
    public function onCommandLotion()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://www.imdb.com/title/tt0102926/quotes?qt=qt0334795');
    }
}
