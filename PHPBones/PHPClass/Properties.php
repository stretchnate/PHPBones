<?php
    /**
     * copyright (C) 2013, 2014 Dustin Nate <stretchnate@gmail.com>
     * This file is part of PHPBones.
     *
     * PHPBones is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 3 of the License, or
     * (at your option) any later version.
     *
     * PHPBones is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     * GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License
     * along with PHPBones.  If not, see <http://www.gnu.org/licenses/>.
     */

    /**
     * PHPBones_PHPClass_Properties contains properties of a certain type for PHPBones_PHPClass
     *
     * @author stretch
     * @since 1.0
     */
    class PHPBones_PHPClass_Properties {
        const ACCESS_PUBLIC    = 'public';
        const ACCESS_PRIVATE   = 'private';
        const ACCESS_PROTECTED = 'protected';

        private $access = null;
        private $static = false;
        private $properties = array();

        /**
         * class constructor method, sets access and static
         *
         * @param string $access
         * @param bool $static
         * @throws UnexpectedValueException
         * @return void
         * @since 1.0
         */
        public function __construct($access, $static = false) {
            $this->static = $static;

            switch($access) {
                case self::ACCESS_PRIVATE:
                    $this->access = self::ACCESS_PRIVATE;
                    break;
                case self::ACCESS_PROTECTED:
                    $this->access = self::ACCESS_PROTECTED;
                    break;
                case self::ACCESS_PUBLIC:
                    $this->access = self::ACCESS_PUBLIC;
                    break;
                default:
                    throw new UnexpectedValueException("Invalid property access");
            }
        }

        /**
         * sets the properties
         *
         * @param array $properties
         * @return \PHPBones_PHPClass_Properties
         * @since 1.0
         */
        public function setProperties(array $properties) {
            $this->properties = $properties;
            return $this;
        }

        /**
         * @return bool
         * @since 1.0
         */
        public function getStatic() {
            return $this->static;
        }

        /**
         * @return array
         * @since 1.0
         */
        public function getProperties() {
            return $this->properties;
        }

        /**
         * @return string
         * @since 1.0
         */
        public function getAccess() {
            return $this->access;
        }
    }
