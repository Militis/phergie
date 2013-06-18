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
 * @package   Phergie_Plugin_Php
 * @author    Phergie Development Team <team@phergie.org>
 * @copyright 2008-2012 Phergie Development Team (http://phergie.org)
 * @license   http://phergie.org/license New BSD License
 * @link      http://pear.phergie.org/package/Phergie_Plugin_Php
 */

/**
 * Shortens urls via the goo.gl service
 *
 * @category Phergie
 * @package  Phergie_Plugin_Url
 * @author   Phergie Development Team <team@phergie.org>
 * @license  http://phergie.org/license New BSD License
 * @link     http://pear.phergie.org/package/Phergie_Plugin_Url
 */
class Phergie_Plugin_Url_Shorten_Google extends Phergie_Plugin_Url_Shorten_Abstract
{
    /**
     * is.gd says their URL will most likely never be longer than 18 characters
     * @link http://is.gd/faq.php#length
     *
     * @var integer
     */
    protected $minimumLength = 19;
    
    protected $api_key = 'AIzaSyDt31F7p-AeUvfB9yXcQF7hHPDuoh5SXXk';

    /**
     * Returns an array of request parameters given a url to shorten. The
     * following keys are valid request parameters:
     *
     * @param string $url the url to shorten
     *
     * @return array the request parameters
     */
    protected function getRequestParams($url)
    {
        return array(
            'uri' => 'https://www.googleapis.com/urlshortener/v1/url?key=' . $this->api_key,
            'post' => array('longUrl' => "$url"),
            'callback' => array($this, 'onComplete')
        );
    }

    /**
     * Callback for when the URL has been shortened. Checks for error messages.
     *
     * @param Phergie_Plugin_Http_Response $response the response object
     *
     * @return string|bool the shortened url or false on failure
     */
    protected function onComplete($response)
    {
        // if (strpos($response->getContent(), 'Error: ') === 0) {
        //     return false;
        // }

        return $response->getContent();
    }
}
