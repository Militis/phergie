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
}
