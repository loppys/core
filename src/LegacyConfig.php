<?php

namespace Vengine;

class LegacyConfig implements Injectable
{
   /**
    * @var array
    */
   protected $property = array();

   public function __get(string $name)
   {
     if (array_key_exists($name, $this->property)) {
         return $this->property[$name];
     }

     return null;
   }

   public function __isset($name)
   {
       return isset($this->data[$name]);
   }

   public function __set(string $name, $value)
   {
       $this->property[$name] = $value;
   }

   public function __unset(string $name)
   {
       unset($this->property[$name]);
   }
}
