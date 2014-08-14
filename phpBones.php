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
    require_once('PHPBones/constructor.php');

    /**
     * Description of phpClassBuilder
     *
     * @author stretch
     */
    class PHPBones {
        private $php_class = null;

        public function __construct() {
            $this->php_class = new PHPClass();
        }

        public function buildClass() {
            if($this->validate()) {

            }
        }

        private function validate() {
            $validator = new PHPClassBuilder_Validator();
            $validator->validate($this->php_class);
            return $validator->isValid();
        }

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

        public function &getPHPClass() {
            return $this->php_class;
        }
    }

    $property_array = array();
    foreach($argv as $arg) {
        if(strpos("=", $arg) !== false) {
            $arg = explode("=", $arg);
            if(strpos(",", $arg[1]) !== false) {
                $property_array[$arg[0]] = explode(",", $arg[1]);
            } else {
                $property_array[$arg[0]] = $arg[1];
            }
        } else {
            switch($arg) {
                case '-a':
                    $with_getters = false;
                    break;
            }
        }
    }

    $class_builder = new PHPClassBuilder();
    $class = $class_builder->getPHPClass();
    if(isset($with_getters)) {
        $class->addAccessMethods($with_getters);
    }

    foreach($property_array as $index => $value) {
        $metod = 'set';
        if(strpos($index, "_") > 0) {
            $method .= str_replace(" ", "", ucwords(str_replace("_", " ", $index)));
        } else {
            $method .= ucwords($index);
        }

        $class->$method($value);
    }

    $class_builder->buildClass();
