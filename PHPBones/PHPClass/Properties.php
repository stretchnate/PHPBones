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
     * Description of Properties
     *
     * @author stretch
     */
    class PHPBones_PHPClass_Properties {
        const ACCESS_PUBLIC    = 'public';
        const ACCESS_PRIVATE   = 'private';
        const ACCESS_PROTECTED = 'protected';
        const TYPE_STATIC      = 'static';

        private $access = null;
        private $static = false;
        private $properties = array();

        public function __construct(array $properties) {
            $type = array_keys($properties);
            $pos = strpos($type, self::TYPE_STATIC);
            if($pos !== false) {
                $this->static = true;

                if($pos > 0) {
                    $access = substr($type, 0, $pos-1);
                } else {
                    $access = substr($type, 7);
                }
            }

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

            $this->properties = $properties;
        }

        public function getStatic() {
            return $this->static;
        }

        public function getProperties() {
            return $this->properties;
        }

        public function getAccess() {
            return $this->access;
        }
    }
