<?php

declare(strict_types=1);

namespace Uro\TeltonikaFmParser\Model;

use JsonSerializable;

class SensorsData implements JsonSerializable
{
    private $eventId;

    private $elements;

    public function __construct($eventId, $elements)
    {
        $this->eventId = $eventId;
        $this->elements = $elements;
    }

    /**
     * Get the IO element ID of Event generated
     *
     * @return number
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * Get the element with specified id in hexadecimal.
     * If the element is not present on elements array returns null.
     *
     * @param string $id
     * @return string|null
     */
    protected function getElement(string $id, string $type)
    {
        if(isset($this->elements[$id])) {
            $element = $this->elements[$id];

            $method = 'getValueAs'.ucfirst($type);
            if(method_exists($element, $method)) {
                return $element->{$method}();
            } else {

            }
        }

        return null;
    }

    public function getUnsignedElement(string $id)
    {
        return $this->getElement($id, 'unsigned');
    }

    public function getSignedElement(string $id)
    {
        return $this->getElement($id, 'signed');
    }

    public function getStringElement(string $id)
    {
        return $this->getElement($id, 'string');
    }

    public function getHexElement(string $id)
    {
        return $this->getElement($id, 'hex');
    }

    /**
     * Create sensor data from hexadecimal payload
     * 
     * @param string $payload
     * @param int $position
     * @return SensorsData
     */
    public static function createFromHex(string $payload, &$position): SensorsData
    {
        $eventId = hexdec(substr($payload, $position, 2));
        $position += 2;

        $elements = [];

        $numberOfIoElements = substr($payload, $position, 2);
        $position += 2;

        self::getIoElements($elements, $payload, $position, 1);
        self::getIoElements($elements, $payload, $position, 2);
        self::getIoElements($elements, $payload, $position, 4);
        self::getIoElements($elements, $payload, $position, 8);

        return new SensorsData($eventId, $elements);
    }

    /**
     * Gets IO Elements and store them into elements array
     *
     * @param array $elements
     * @param string $payload
     * @param integer $position
     * @param integer $bytes
     * @return void
     */
    protected static function getIoElements(array &$elements, string $payload, &$position, $bytes)
    {
        $numberOfElements = substr($payload, $position, 2);
        $position += 2;

        for($i = 0; $i < $numberOfElements; $i++) {
            $id = hexdec(substr($payload, $position, 2));
            $position += 2;

            $value = substr($payload, $position, $bytes * 2);
            $position += $bytes * 2;

            $elements[$id] = new IoElement($id, $value, $bytes);
        }
    }

    public function jsonSerialize(): array
    {
        return [
            'eventId' => $this->eventId,
            'elements' => $this->elements 
        ];
    }
}
