<?php
/***************************************************************************
 * Copyright (C) 1999-2013 Gadz.org                                        *
 * http://opensource.gadz.org/                                             *
 *                                                                         *
 * This program is free software; you can redistribute it and/or modify    *
 * it under the terms of the GNU General Public License as published by    *
 * the Free Software Foundation; either version 2 of the License, or       *
 * (at your option) any later version.                                     *
 *                                                                         *
 * This program is distributed in the hope that it will be useful,         *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 * GNU General Public License for more details.                            *
 *                                                                         *
 * You should have received a copy of the GNU General Public License       *
 * along with this program; if not, write to the Free Software             *
 * Foundation, Inc.,                                                       *
 * 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA                   *
 ***************************************************************************/

namespace CarnegieLearning\LdapOrmBundle\Entity;

/**
 * Class used to Decorate DateTime objects (and permits them to be printed)
 *
 * @author Eric Bourderau <eric.bourderau@soce.fr>
 * @category API
 * @package  GramApiServerBundle
 * @license  GNU General Public License
 */
class DateTimeDecorator
{
    /**
     * @var \DateTime
     */
    protected $_instance;

    /**
     * Default format for LDAP
     *
     * @var string
     */
    private $format;

    /**
     * @return string
     */
    public function __toString() {
        return $this->_instance->format($this->format);
    }

    /**
     * @param $format
     */
    public function setFormat($format) {
        $this->format = $format;
    }

    /**
     * Decorator of a DateTime object (adding __toString() and setFormat method)
     * @param  string|\DateTime  $datetime
     */
    public function __construct($datetime) {
        $this->format = 'YmdHisO';
        if ($datetime instanceof \DateTime) {
            $this->_instance = $datetime;
        }
        else {
            $this->_instance = new \DateTime($datetime);
        }
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args) {
        return call_user_func_array(array($this->_instance, $method), $args);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function __get($key) {
        return $this->_instance->$key;
    }

    /**
     * @param $key
     * @param $val
     * @return mixed
     */
    public function __set($key, $val) {
        return $this->_instance->$key = $val;
    }
}
