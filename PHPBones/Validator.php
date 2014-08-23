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
     * PHPBones_Validator validates the PHPBones_PHPClass object
     *
     * @author stretch
     * @since 1.0
     */
    class PHPBones_Validator {

        const CLASSNAME_REGEX = '/^[_A-Za-z][\w]+$/';

        private $php_class;
        private $valid = true;
        private $errors = array();

        public function __construct() {}

        /**
         * main validation method
         *
         * @param PHPBones_PHPClass $php_class
         * @return void
         * @since 1.0
         */
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

        /**
         * validates the implements (interface) values
         *
         * @since 1.0
         * @return void
         */
        private function validateImplements() {
            foreach($this->php_class->getImplements() as $interface) {
                if(!preg_match(self::CLASSNAME_REGEX, $interface)) {
                    $this->errors[] = 'Implemented interface name ' . $interface . ' is invalid';
                }
            }
        }

        /**
         * validates the extends (parent class) value
         *
         * @since 1.0
         * @return void
         */
        private function validateExtends() {
            if(!preg_match(self::CLASSNAME_REGEX, $this->php_class->getExtends())) {
                $this->errors[] = 'Extended Classname ' . $this->php_class->getExtends() . ' is invalid';
            }
        }

        /**
         * validates the include_once values
         *
         * @since 1.0
         * @return void
         */
        private function validateIncludes() {
            $includes = $this->php_class->getIncludes();
            if(isset($includes) && is_array($includes) && count($includes) > 0) {
                $this->validateFiles($includes);
            }
        }

        /**
         * validates the require_once values
         *
         * @since 1.0
         * @return void
         */
        private function validateRequires() {
            $requires = $this->php_class->getRequires();
            if(isset($requires)
                && is_array($requires)
                && count($requires) > 0) {

                $this->validateFiles($requires);
            }
        }

        /**
         * validates files
         *
         * @since 1.0
         * @return void
         */
        private function validateFiles($files_array) {
            foreach($files_array as $file_type => $filename) {
                if(!  file_exists( $filename )) {
                    $this->errors[] = $file_type . ' ' . $filename . ' does not exist';
                }
            }
        }

        /**
         * validates private static properties
         *
         * @since 1.0
         * @return void
         */
        private function validatePrivateStaticProperties() {
            if(is_array($this->php_class->getPrivateStatic())) {
                if(count($this->php_class->getPrivateStatic()) > 0) {
                    $this->validateProperties($this->php_class->getPrivateStatic());
                }
            }
        }

        /**
         * validates the private properties
         *
         * @since 1.0
         * @return void
         */
        private function validatePrivateProperties() {
            if(is_array($this->php_class->getPrivate())) {
                if(count($this->php_class->getPrivate()) > 0) {
                    $this->validateProperties($this->php_class->getPrivate());
                }
            }
        }

        /**
         * validates the protected static properties
         *
         * @since 1.0
         * @return void
         */
        private function validateProtectedStaticProperties() {
            if(is_array($this->php_class->getProtectedStatic())) {
                if(count($this->php_class->getProtectedStatic()) > 0) {
                    $this->validateProperties($this->php_class->getProtectedStatic());
                }
            }
        }

        /**
         * validates the protected properties
         *
         * @since 1.0
         * @return void
         */
        private function validateProtectedProperties() {
            if(is_array($this->php_class->getProtected())) {
                if(count($this->php_class->getProtected()) > 0) {
                    $this->validateProperties($this->php_class->getProtected());
                }
            }
        }

        /**
         * validates the public static properties
         *
         * @since 1.0
         * @return void
         */
        private function validatePublicStaticProperties() {
            if(is_array($this->php_class->getPublicStatic())) {
                if(count($this->php_class->getPublicStatic()) > 0) {
                    $this->validateProperties($this->php_class->getPublicStatic());
                }
            }
        }

        /**
         * validates the public properties
         *
         * @since 1.0
         * @return void
         */
        private function validatePublicProperties() {
            if(is_array($this->php_class->getPublic())) {
                if(count($this->php_class->getPublic()) > 0) {
                    $this->validateProperties($this->php_class->getPublic());
                }
            }
        }

        /**
         * validates the properties
         *
         * @since 1.0
         * @return void
         */
        private function validateProperties($properties_array) {
            foreach($properties_array as $property_type => $property) {
                if(!preg_match(self::CLASSNAME_REGEX, $property) ) {
                    $this->errors[] = 'Invalid value for '.$property_type.' property ['.$property.']';
                }
            }
        }

        /**
         * validates the file location of the class
         *
         * @since 1.0
         * @return void
         */
        private function validateLocation() {
            $pos = strrpos($this->php_class->getLocation(), '/');
            $path = substr($this->php_class->getLocation(), 0, $pos);

            if(!file_exists($path)) {
                $this->errors[] = 'Invalid location '.$path;
            } else if(!is_writable($path)) {
                $this->errors[] = 'Location is not writeable '.$path;
            }

        }

        /**
         * validates the classname
         *
         * @since 1.0
         * @return void
         */
        private function validateClassname() {
            if(!$this->php_class->getClassname()) {
                $this->errors[] = 'No classname set';
            } else if(!preg_match(self::CLASSNAME_REGEX, $this->php_class->getClassname())) {
                $this->errors[] = 'Invalid value for classname';
            }
        }

        /**
         * getter for $this->valid
         *
         * @since 1.0
         * @return void
         */
        public function isValid() {
            return $this->valid;
        }
    }
