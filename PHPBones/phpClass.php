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
     * Description of phpClassBuilder
     *
     * @author stretch
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
        private $implements         = null;
        private $requires           = null;
        private $includes           = null;
        private $location           = null;

        public function __construct() {}

        public function buildClass() {
            if($this->validate()) {

            }
        }

        private function validate() {
            $validator = new PHPClassBuilder_Validator();
            $validator->validate($this);
            return $validator->isValid();
        }

        public function setPublic(array $public) {
            $this->public = new PHPClassBuilder_PHPClass_Properties($public);
            return $this;
        }

        public function setProtected(array $protected) {
            $this->protected = new PHPClassBuilder_PHPClass_Properties($protected);
            return $this;
        }

        public function setPrivate(array $private) {
            $this->private = new PHPClassBuilder_PHPClass_Properties($private);
            return $this;
        }

        public function setPublicStatic(array $public_static) {
            $this->public_static = new PHPClassBuilder_PHPClass_Properties($public_static);
            return $this;
        }

        public function setProtectedStatic(array $protected_static) {
            $this->protected_static = new PHPClassBuilder_PHPClass_Properties($protected_static);
            return $this;
        }

        public function setPrivateStatic(array $private_static) {
            $this->private_static = new PHPClassBuilder_PHPClass_Properties($private_static);
            return $this;
        }

        public function addAccessMethods($with_getters) {
            $this->add_access_methods = self::getBoolean($with_getters);
            return $this;
        }

        public function setClassname($classname) {
            $this->classname = $classname;
            return $this;
        }

        public function setExtends($extends) {
            $this->extends = $extends;
            return $this;
        }

        public function setImplements($implements) {
            $this->implements = (is_array($implements)) ? $implements : array($implements);
            return $this;
        }

        public function setRequires($requires) {
            $this->requires = (is_array($requires)) ? $requires : array($requires);
            return $this;
        }

        public function setIncludes($includes) {
            $this->includes = (is_array($includes)) ? $includes : array($includes);
            return $this;
        }

        public function setLocation($location) {
            $this->location = str_replace("\\" , "/", $location);
            return $this;
        }

        public function getPublicProperties() {
            return $this->public;
        }

        public function getPublicStaticProperties() {
            return $this->public_static;
        }

        public function getProtectedProperties() {
            return $this->protected;
        }

        public function getProtectedStaticProperties() {
            return $this->protected_static;
        }

        public function getPrivateProperties() {
            return $this->private;
        }

        public function getPrivateStaticProperties() {
            return $this->private_static;
        }

        public function getClassname() {
            return $this->classname;
        }

        public function getExtends() {
            return $this->extends;
        }

        public function getRequires() {
            return $this->requires;
        }

        public function getIncludes() {
            return $this->includes;
        }

        public function getLocation() {
            return $this->location;
        }
    }
