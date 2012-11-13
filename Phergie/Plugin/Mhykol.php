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
 * @package   Phergie_Plugin_Join
 * @author    Phergie Development Team <team@phergie.org>
 * @copyright 2008-2012 Phergie Development Team (http://phergie.org)
 * @license   http://phergie.org/license New BSD License
 * @link      http://pear.phergie.org/package/Phergie_Plugin_Join
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
     * Checks for dependencies.
     *
     * @return void
     */
    public function onLoad()
    {
        $this->getPluginHandler()->getPlugin('Command');
    }

    /**
     * Prints link to Rules
     *
     *
     * @return void
     */
    public function onCommandRules()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://mc.mhykol.com/pages/rules/');
    }

    /**
     * Prints link to Ranks
     *
     *
     * @return void
     */
    public function onCommandRanks()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://mc.mhykol.com/threads/ranks.1203/');
    }

    /**
     * Prints link to VIP/Partner donation
     *
     *
     * @return void
     */
    public function onCommandVip()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://mc.mhykol.com/pages/buyrank/');
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
        $this->doPrivmsg($this->event->getSource(), 'http://mc.mhykol.com/threads/read-before-posting-appeal.1283/');
        $this->doPrivmsg($this->event->getSource(), 'Create a new topic with your ingame name as the title in this forum: http://mc.mhykol.com/forums/ban-appeals.6/');
    }
    
    /**
     * Prints link to how to register with Q
     *
     *
     * @return void
     */
    public function onCommandTalkinginirc()
    {
        $this->doPrivmsg($this->event->getSource(), 'http://www.quakenet.org/help/q/how-to-register-an-account-with-q');
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
        // $this->doPrivmsg($this->event->getSource(), '');
    }
}
