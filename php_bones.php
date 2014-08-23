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
     * PHPBones is a php library that builds bare bones classes in PHP. Including require_once,
     * include_once, extends, implements, properties and property access methods.
     *
     * example command
     *      php php_bones.php classname=newClass extends=oldClass implements=classInterface private=property1,property2,property3 protected=property4,property5 public_static=property6 location=/tmp/newClass.php
     *
     * @author stretch
     */
    require_once('phpBones.php');

    //iterate through argv and set up property values
    $class_structure_array = array();
    foreach($argv as $arg) {
        if(strpos($arg, "=") !== false) {
            $arg = explode("=", $arg);
            if(strpos($arg[1], ",") !== false) {
                $class_structure_array[$arg[0]] = explode(",", $arg[1]);
            } else {
                $class_structure_array[$arg[0]] = $arg[1];
            }
        } else {
            switch($arg) {
                case '-a':
                    $with_accessors = false;
                    break;
            }
        }
    }

    //get our class object
    $class_builder = new PHPBones();
    $class = $class_builder->getPHPClass();

    //remove accessors
    if(isset($with_accessors)) {
        $class->addAccessMethods($with_accessors);
    }

    //add class properties, extends, implements, require_once and include_once
    foreach($class_structure_array as $index => $value) {
        $method = 'set';
        if(strpos($index, "_") > 0) {
            $method .= str_replace(" ", "", ucwords(str_replace("_", " ", $index)));
        } else {
            $method .= ucwords($index);
        }

        if(method_exists($class, $method )) {
            $class->$method($value);
        } else {
            echo "invalid method $method\n";
        }
    }

    //build our class
    echo PHPBones_Constructor::NL . $class_builder->buildClass() . PHPBones_Constructor::NL;

