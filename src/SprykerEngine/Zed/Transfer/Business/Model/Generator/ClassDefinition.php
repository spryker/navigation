<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

class ClassDefinition implements ClassDefinitionInterface
{
    const TYPE_ARRAY    = 'array';
    const TYPE_BOOLEAN  = 'boolean';
    const TYPE_INTEGER  = 'integer';

    protected $className;
    protected $interfaces = [];
    protected $properties = [];

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->setClassName($className);
    }

    /**
     * @param array|string $implementsInterface
     * @return $this
     */
    public function setInterface($implementsInterface)
    {
        if ( is_array($implementsInterface) ) {
            foreach ($implementsInterface as $newInterface) {
                $this->setInterface($newInterface);
            }
        } else {
            $this->addInterface($implementsInterface);
        }

        return $this;
    }

    /**
     * Add interface to list if it doesn't exists already
     *
     * @param string $interface
     */
    protected function addInterface($interface)
    {
        if ( ! in_array($interface, $this->interfaces) ) {
            $this->interfaces[] = $interface;
        }
    }

    /**
     * @param array $properties
     */
    public function setProperty(array $properties)
    {
        $this->properties[$properties['name']] = [
            'name' => $properties['name'],
            'type_special' => $this->isTypeSpecial($properties['type']),
            'type' => $this->getType($properties['type']),
            'default' => (isset($properties['default'])) ? $properties['default'] : '',
        ];
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getType($type)
    {
        if ( $this->isTypeSpecial($type) ) {
            if ( $type === '[]' ) {
                return self::TYPE_ARRAY;
            }

            // this should be class type
            return strtr($type, [
                '[]' => '',
            ]);
        }

        return $type;
    }

    /**
     * @param string $type
     * @return int
     */
    protected function isTypeSpecial($type)
    {
        return (bool) preg_match('/\[\]/', $type);
    }

    /**
     * Transfer objects should have Transfer word in they're name
     * append it if not
     *
     * @param string $name
     */
    public function setClassName($name)
    {
        if ( strpos($name, 'Transfer') === false ) {
            $name .= 'Transfer';
        }
        $this->className = $name;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @return array
     */
    public function getInterfaces()
    {
        return $this->interfaces;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
