<?php namespace SocialEngine\Unum\Contracts;

interface Entity
{
    /**
     * Populate Entity from an Array
     *
     * @param array $data
     * @return self
     * @throws \InvalidArgumentException
     */
    public function fromArray(array $data);

    /**
     * Populate an array from Entity. When $dirty is true,
     * array is limited to only modified values since last
     * time $this->clean() was called.
     *
     * @param bool $dirty
     * @return array
     */
    public function toArray($dirty = false);

    /**
     * Marks Entity as clean
     *
     * @return self
     */
    public function clean();
}
