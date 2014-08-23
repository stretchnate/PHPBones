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
    require_once('PHPClass/Properties.php');

    /**
     * PHPBones_PHPClass holds all properties to be built into the new class
     *
     * @author stretch
     * @since 1.0
     */
    class PHPBones_PHPClass {
        private $public             = null;
        private $public_static      = null;
        private $protected          = null;
        private $protected_static   = null;
        private $private            = null;
        private $private_static     = null;
        private $add_access_methods = true;
        private $classname          = null;
        private $extends            = null;
        private $implements         = array();
        private $requires           = array();
        private $includes           = array();
        private $location           = null;

        /**
         * class constructor, instantiates properties objects
         *
         * @return void
         * @since 1.0
         */
        public function __construct() {
            $this->public = new PHPBones_PHPClass_Properties(
                    PHPBones_PHPClass_Properties::ACCESS_PUBLIC
            );

            $this->protected = new PHPBones_PHPClass_Properties(
                    PHPBones_PHPClass_Properties::ACCESS_PROTECTED
            );

            $this->private = new PHPBones_PHPClass_Properties(
                    PHPBones_PHPClass_Properties::ACCESS_PRIVATE
            );

            $this->public_static = new PHPBones_PHPClass_Properties(
                    PHPBones_PHPClass_Properties::ACCESS_PUBLIC,
                    true
            );

            $this->protected_static = new PHPBones_PHPClass_Properties(
                    PHPBones_PHPClass_Properties::ACCESS_PROTECTED,
                    true
            );

            $this->private_static = new PHPBones_PHPClass_Properties(
                    PHPBones_PHPClass_Properties::ACCESS_PRIVATE,
                    true
            );
        }

        /**
         * converts a string to an array
         *
         * @param mixed $var
         * @return array
         * @since 1.0
         */
        public static function arrayify($var) {
            if(!is_array($var)) {
                $var = array($var);
            }

            return $var;
        }

        /**
         * sets public properties
         *
         * @param array $public
         * @return \PHPBones_PHPClass
         * @since 1.0
         */
        public function setPublic($public) {
            $this->public->setProperties(self::arrayify($public));
            return $this;
        }

        /**
         * sets protected properties
         *
         * @param array $protected
         * @return \PHPBones_PHPClass
         * @since 1.0
         */
        public function setProtected($protected) {
            $this->protected->setProperties(self::arrayify($protected));
            return $this;
        }

        /**
         * sets the private properties
         *
         * @param array $private
         * @return \PHPBones_PHPClass
         * @since 1.0
         */
        public function setPrivate($private) {
            $this->private->setProperties(self::arrayify($private));
            return $this;
        }

        /**
         * sets the public static properties
         *
         * @param array $public_static
         * @return \PHPBones_PHPClass
         * @since 1.0
         */
        public function setPublicStatic($public_static) {
            $this->public_static->setProperties(self::arrayify($public_static));
            return $this;
        }

        /**
         * sets the protected static properties
         *
         * @param array $protected_static
         * @return \PHPBones_PHPClass
         * @since 1.0
         */
        public function setProtectedStatic($protected_static) {
            $this->protected_static->setProperties(self::arrayify($protected_static));

            return $this;
        }

        /**
         * sets the private static properties
         *
         * @param array $private_static
         * @return \PHPBones_PHPClass
         * @since 1.0
         */
        public function setPrivateStatic($private_static) {
            $this->private_static->setProperties(self::arrayify($private_static));
            return $this;
        }

        /**
         * adds/removes accessor methods
         *
         * @param bool $with_accessors
         * @return \PHPBones_PHPClass
         */
        public function addAccessMethods($with_accessors) {
            $this->add_access_methods = self::getBoolean($with_accessors);
            return $this;
        }

        /**
         * sets the classname
         *
         * @param string $classname
         * @return \PHPBones_PHPClass
         * @return void
         */
        public function setClassname($classname) {
            $this->classname = $classname;
            return $this;
        }

        /**
         * sets the parent class
         *
         * @param string $extends
         * @return \PHPBones_PHPClass
         * @return void
         */
        public function setExtends($extends) {
            $this->extends = $extends;
            return $this;
        }

        /**
         * sets the interfaces to implement
         *
         * @param array $implements
         * @return \PHPBones_PHPClass
         * @return void
         */
        public function setImplements($implements) {
            $this->implements = (is_array($implements)) ? $implements : array($implements);
            return $this;
        }

        /**
         * sets the require_once's
         *
         * @param array $requires
         * @return \PHPBones_PHPClass
         * @return void
         */
        public function setRequires($requires) {
            $this->requires = (is_array($requires)) ? $requires : array($requires);
            return $this;
        }

        /**
         * sets the include_once's
         *
         * @param array $includes
         * @return \PHPBones_PHPClass
         * @return void
         */
        public function setIncludes($includes) {
            $this->includes = (is_array($includes)) ? $includes : array($includes);
            return $this;
        }

        /**
         * sets the file location
         *
         * @param string $location - filepath with filename
         * @return \PHPBones_PHPClass
         * @return void
         */
        public function setLocation($location) {
            $this->location = str_replace("\\" , "/", $location);
            return $this;
        }

        /**
         * @return PHPBones_PHPClass_Properties
         */
        public function getPublic() {
            return $this->public;
        }

        /**
         * @return PHPBones_PHPClass_Properties
         */
        public function getPublicStatic() {
            return $this->public_static;
        }

        /**
         * @return PHPBones_PHPClass_Properties
         */
        public function getProtected() {
            return $this->protected;
        }

        /**
         * @return PHPBones_PHPClass_Properties
         */
        public function getProtectedStatic() {
            return $this->protected_static;
        }

        /**
         * @return PHPBones_PHPClass_Properties
         */
        public function getPrivate() {
            return $this->private;
        }

        /**
         * @return PHPBones_PHPClass_Properties
         * @since 1.0
         */
        public function getPrivateStatic() {
            return $this->private_static;
        }

        /**
         * @return string
         * @since 1.0
         */
        public function getClassname() {
            return $this->classname;
        }

        /**
         * @return string
         * @since 1.0
         */
        public function getExtends() {
            return $this->extends;
        }

        /**
         * @return array
         * @since 1.0
         */
        public function getImplements() {
            return $this->implements;
        }

        /**
         * @return array
         * @since 1.0
         */
        public function getRequires() {
            return $this->requires;
        }

        /**
         * @return array
         * @since 1.0
         */
        public function getIncludes() {
            return $this->includes;
        }

        /**
         * @return string
         * @since 1.0
         */
        public function getLocation() {
            return $this->location;
        }
    }
