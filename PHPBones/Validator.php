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
     * Description of Validator
     *
     * @author stretch
     */
    class PHPBones_Validator {

        const CLASSNAME_REGEX = '/^[_A-Za-z][\w]+$/';

        private $php_class;
        private $valid = true;
        private $errors = array();

        public function __construct() {}

        public function validate(PHPBones_PHPClass $php_class) {
            $this->php_class = $php_class;

            $this->validateClassname();
            $this->validateLocation();
            $this->validatePublicProperties();
            $this->validatePublicStaticProperties();
            $this->validateProtectedProperties();
            $this->validateProtectedStaticProperties();
            $this->validatePrivateProperties();
            $this->validatePrivateStaticProperties();
//            $this->validateRequires();
//            $this->validateIncludes();
            $this->validateExtends();
            $this->validateImplements();

            if(count($this->errors) > 0) {
                $this->valid = false;
            }
        }

        private function validateImplements() {
            foreach($this->class_builder->getImplements() as $interface) {
                if(!preg_match(self::CLASSNAME_REGEX, $interface)) {
                    $this->errors[] = 'Implemented interface name ' . $interface . ' is invalid';
                }
            }
        }

        private function validateExtends() {
            if(!preg_match(self::CLASSNAME_REGEX, $this->class_builder->getExtends())) {
                $this->errors[] = 'Extended Classname ' . $this->class_builder->getExtends() . ' is invalid';
            }
        }

        private function validateIncludes() {
            if(isset($this->class_builder->getIncludes())
                && is_array($this->class_builder->getIncludes())
                && count($this->class_builder->getIncludes()) > 0) {

                $this->validateFiles($this->class_builder->getIncludes());
            }
        }

        private function validateRequires() {
            if(isset($this->class_builder->getRequires())
                && is_array($this->class_builder->getRequires())
                && count($this->class_builder->getRequires()) > 0) {

                $this->validateFiles($this->class_builder->getRequires());
            }
        }

        private function validateFiles($files_array) {
            foreach($files_array as $file_type => $filename) {
                if(!  file_exists( $filename )) {
                    $this->errors[] = $file_type . ' ' . $filename . ' does not exist';
                }
            }
        }

        private function validatePrivateStaticProperties() {
            if(is_array($this->class_builder->getPrivateStaticProperties())) {
                if(count($this->class_builder->getPrivateStaticProperties()) > 0) {
                    $this->validateProperties($this->class_builder->getPrivateStaticProperties());
                }
            }
        }

        private function validatePrivateProperties() {
            if(is_array($this->class_builder->getPrivateProperties())) {
                if(count($this->class_builder->getPrivateProperties()) > 0) {
                    $this->validateProperties($this->class_builder->getPrivateProperties());
                }
            }
        }

        private function validateProtectedStaticProperties() {
            if(is_array($this->class_builder->getProtectedStaticProperties())) {
                if(count($this->class_builder->getProtectedStaticProperties()) > 0) {
                    $this->validateProperties($this->class_builder->getProtectedStaticProperties());
                }
            }
        }

        private function validateProtectedProperties() {
            if(is_array($this->class_builder->getProtectedProperties())) {
                if(count($this->class_builder->getProtectedProperties()) > 0) {
                    $this->validateProperties($this->class_builder->getProtectedProperties());
                }
            }
        }

        private function validatePublicStaticProperties() {
            if(is_array($this->class_builder->getPublicStaticProperties())) {
                if(count($this->class_builder->getPublicStaticProperties()) > 0) {
                    $this->validateProperties($this->class_builder->getPublicStaticProperties());
                }
            }
        }

        private function validatePublicProperties() {
            if(is_array($this->class_builder->getPublicProperties())) {
                if(count($this->class_builder->getPublicProperties()) > 0) {
                    $this->validateProperties($this->class_builder->getPublicProperties());
                }
            }
        }

        private function validateProperties($properties_array) {
            foreach($properties_array as $property_type => $property) {
                if(!preg_match(self::CLASSNAME_REGEX, $property) ) {
                    $this->errors[] = 'Invalid value for '.$property_type.' property ['.$property.']';
                }
            }
        }

        private function validateLocation() {
            $pos = strrpos($this->class_builder->getLocation(), '/');
            $path = substr($this->class_builder->getLocation(), 0, $pos);

            if(!file_exists($path)) {
                $this->errors[] = 'Invalid location '.$path;
            } else if(!is_writable($path)) {
                $this->errors[] = 'Location is not writeable '.$path;
            }

        }

        private function validateClassname() {
            if(!isset($this->class_builder->getClassname())) {
                $this->errors[] = 'No classname set';
            } else if(!preg_match(self::CLASSNAME_REGEX, $this->class_builder->getClassname())) {
                $this->errors[] = 'Invalid value for classname';
            }
        }

        public function isValid() {
            return $this->valid;
        }
    }
