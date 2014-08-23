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
    require_once('PHPBones/Validator.php');
    require_once('PHPBones/phpClass.php');
    require_once('PHPBones/Constructor.php');

    /**
     * PHPBones is a php library that builds bare bones classes in PHP. Including require_once,
     * include_once, extends, implements, properties and property access methods.
     *
     * example command
     *      php php_bones.php classname=newClass extends=oldClass implements=classInterface private=property1,property2,property3 protected=property4,property5 public_static=property6 location=/tmp/newClass.php
     *
     * @author stretch
     */
    class PHPBones {
        private $php_class = null;

        /**
         * class constructor
         */
        public function __construct() {
            $this->php_class = new PHPBones_PHPClass();
        }

        /**
         * builds the php class after all args are validated
         *
         * @since 1.0
         * @return string
         */
        public function buildClass() {
            $result = 'succesfully built '. $this->php_class->getClassname();
            try {
                if($this->validate($this->php_class)) {
                    $constructor = new PHPBones_Constructor();
                    $constructor->buildClass($this->php_class);
                }
            } catch (Exception $e) {
                $result = $e->getMessage();
            }

            return $result;
        }

        /**
         * validates the arguments
         *
         * @since 1.0
         * @return bool
         */
        private function validate() {
            $validator = new PHPBones_Validator();
            $validator->validate($this->php_class);
            return $validator->isValid();
        }

        /**
         * converts an argument to a boolean value, 'false' === false
         *
         * @param mixed $value
         * @return bool
         */
        public static function getBoolean($value) {
            if(!is_bool($value)) {
                if($value == 'false') {
                    $value = false;
                } else {
                    $value = (bool)$value;
                }
            }

            return $value;
        }

        public function getPHPClass() {
            return $this->php_class;
        }
    }
