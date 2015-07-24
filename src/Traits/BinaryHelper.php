<?php namespace SocialEngine\Unum\Traits;

trait BinaryHelper
{
    /**
     * Returns true if the value of $key has been changed to true. Otherwise, false.
     *
     * @param $key
     * @return bool
     */
    public function changedAndEnabled($key)
    {
        $changed = $this->hasChanged($key);

        return ($changed && $this->$key === true);
    }
}
