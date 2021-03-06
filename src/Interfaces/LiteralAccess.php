<?php
/**
 * League.Url (http://url.thephpleague.com)
 *
 * @link      https://github.com/thephpleague/url/
 * @copyright Copyright (c) 2013-2015 Ignace Nyamagana Butera
 * @license   https://github.com/thephpleague/url/blob/master/LICENSE (MIT License)
 * @version   4.0.0
 * @package   League.url
 */
namespace League\Uri\Interfaces;

/**
 * An Interface to allow accessing the literal string representation
 * of a component without encoding
 *
 * @package League.url
 * @since   4.0.0
 */
interface LiteralAccess
{
    /**
     * Returns the instance literal representation
     * without encoding
     *
     * @return string
     */
    public function getLiteral();
}
