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
     * Description of constructor
     *
     * @author stretch
     */
    class PHPBones_Constructor {

        const NL = "\n";
        const SPACE = ' ';
        const INDENT = '    ';
        const KEYWORD_EXTENDS = 'extends';
        const KEYWORD_IMPLEMENTS = 'implements';
        const KEYWORD_FUNCTION = 'function';
        const KEYWORD_PUBLIC     = 'public';
        const KEYWORD_RETURN     = 'return';
        const LINEND             = ';';
        const BRACKET_OPEN       = '{';
        const BRACKET_CLOSE      = '}';
        const PAREN_OPEN         = '(';
        const PAREN_CLOSE        = ')';
        const EQUALS             = '=';

        private $output = '';
        private $php_class = null;

        public function __construct() {
            $this->output = '<?php ' . str_repeat(self::NL, 2);
        }

        public function buildClass(PHPBones_PHPClass $php_class) {
            $this->php_class = $php_class;

            $this->setRequires();
            $this->setIncludes();
            $this->setClassDeclaration();
            $this->setAllProperties();
            $this->setConstruct();
            $this->setAllAccessorMethods();
            $this->endClass();

            $this->write();
        }

        private function write() {
            file_put_contents($this->php_class->getLocation(), $this->output);
        }

        private function endClass() {
            $this->output .= self::NL . self::INDENT . self::BRACKET_CLOSE;
        }

        private function setAllAccessorMethods() {
            $this->setAccessorMethods($this->php_class->getPublic());
            $this->setAccessorMethods($this->php_class->getProtected());
            $this->setAccessorMethods($this->php_class->getPrivate());
            $this->setAccessorMethods($this->php_class->getPublicStatic());
            $this->setAccessorMethods($this->php_class->getProtectedStatic());
            $this->setAccessorMethods($this->php_class->getPrivateStatic());
        }

        private function setAccessorMethods(PHPBones_PHPClass_Properties $properties_obj) {
            foreach($properties_obj->getProperties() as $property) {
                $this->setGetterMethod($property);
                $this->setSetterMethod($property);
            }
        }

        private function setSetterMethod($property) {
            $this->output .= str_repeat(self::NL, 2) . str_repeat(self::INDENT, 2);
            $this->output .= self::KEYWORD_PUBLIC . self::SPACE .
                    self::KEYWORD_FUNCTION . self::SPACE .
                    'set' . str_replace(" ", "", ucwords(str_replace("_", " ", $property))) .
                    self::PAREN_OPEN . '$'.$property . self::PAREN_CLOSE . self::SPACE . self::BRACKET_OPEN;

            $this->output .= self::NL . str_repeat(self::INDENT, 3);
            $this->output .= self::KEYWORD_RETURN . '$this->' . $property . self::SPACE .
                    self::EQUALS . self::SPACE . '$'.$property . self::LINEND;

            $this->output .= self::NL . str_repeat(self::INDENT, 2) . self::BRACKET_CLOSE;
        }

        private function setGetterMethod($property) {
            $this->output .= str_repeat(self::NL, 2) . str_repeat(self::INDENT, 2);
            $this->output .= self::KEYWORD_PUBLIC . self::SPACE .
                    self::KEYWORD_FUNCTION . self::SPACE .
                    'get' . str_replace(" ", "", ucwords(str_replace("_", " ", $property))) .
                    self::PAREN_OPEN . self::PAREN_CLOSE . self::SPACE . self::BRACKET_OPEN;

            $this->output .= self::NL . str_repeat(self::INDENT, 3);
            $this->output .= self::KEYWORD_RETURN . '$this->' . $property . self::LINEND;
            $this->output .= self::NL . str_repeat(self::INDENT, 2) . self::BRACKET_CLOSE;
        }

        private function setConstruct() {
            $this->output .= self::NL . str_repeat(self::INDENT, 2);
            $this->output .= self::KEYWORD_PUBLIC . self::SPACE . self::KEYWORD_FUNCTION . self::SPACE;
            $this->output .= '__construct()' . self::SPACE . self::BRACKET_OPEN . self::BRACKET_CLOSE;
        }

        private function setAllProperties() {
            $this->setProperties($this->php_class->getPublic());
            $this->setProperties($this->php_class->getProtected());
            $this->setProperties($this->php_class->getPrivate());
            $this->setProperties($this->php_class->getPublicStatic());
            $this->setProperties($this->php_class->getProtectedStatic());
            $this->setProperties($this->php_class->getPrivateStatic());
        }

        private function setProperties(PHPBones_PHPClass_Properties $properties_obj) {
            $static = ($properties_obj->getStatic() === true) ? 'static' . self::SPACE : '';
            foreach($properties_obj->getProperties() as $property) {
                $this->output .=
                        $properties_obj->getAccess() . self::SPACE .
                        $static . $property . self::LINEND .
                        self::NL . str_repeat(self::INDENT, 2);
            }
        }

        private function setIncludes() {
            foreach($this->php_class->getIncludes() as $included) {
                $this->output .= self::INDENT . "'include_once('".$included."');" . self::NL;
            }
        }

        private function setRequires() {
            foreach($this->php_class->getRequires() as $required) {
                $this->output .= self::INDENT . "'require_once('".$required."');" . self::NL;
            }
        }

        private function setClassDeclaration() {
            $this->output .= self::INDENT . $this->php_class->getClassname() . self::SPACE;

            if($this->php_class->getExtends()) {
                $this->output .= self::KEYWORD_EXTENDS
                        . self::SPACE
                        . $this->php_class->getExtends()
                        . self::SPACE;
            }

            if(count($this->php_class->getImplements()) > 0) {
                $this->output .= self::KEYWORD_IMPLEMENTS . self::SPACE;

                $this->output .= implode(self::SPACE, $this->php_class->getImplements()) . self::SPACE;
            }

            $this->output .= self::BRACKET_OPEN . str_repeat(self::NL, 2) . str_repeat(self::INDENT, 2);
        }
    }
