<?php

namespace Services\Customer\Models\RandomUser;

use ArrayAccess;
use Illuminate\Support\Arr;

class RandomUserModel implements ArrayAccess
{
    /**
     * @var string
     */
    public const GENDER_FEMALE = 'female';

    /**
     * @var string
     */
    public const GENDER_MALE = 'male';

    /**
     * @var string
     */
    public const PASSWORD_TYPE_MD5 = 'md5';

    /**
     * @var string
     */
    public const PASSWORD_TYPE_RAW = 'raw';

    /**
     * @var string
     */
    public const PASSWORD_TYPE_SHA1 = 'sha1';

    /**
     * @var string
     */
    public const PASSWORD_TYPE_SHA256 = 'sha256';

    /**
     * @var array
     */
    protected $attributes;

    /**
     * RandomUser constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getDotAttributes() : array
    {
        return Arr::dot($this->attributes);
    }

    public function getAttribute($key, $default = null)
    {
        if ($this->offsetExists($key)) {
            return $this->offsetGet($key);
        }

        return $this->getDotAttributes()[$key] ?? $default;
    }

    public function offsetExists($attribute)
    {
        return isset($this->attributes[$attribute]);
    }

    public function offsetGet($attribute)
    {
        return $this->attributes[$attribute];
    }

    public function offsetUnset($key)
    {
        unset($this->attributes[$key]);
    }

    public function __get($attribute)
    {
        return $this->offsetGet($attribute);
    }

    public function __set($attribute, $value)
    {
        $this->offsetSet($attribute, $value);
    }

    public function offsetSet($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __isset($attribute)
    {
        return $this->offsetExists($attribute);
    }

    public function getName($key = null, $withTitle = false) : string
    {
        $name = $this->name;
        if ($key !== null && isset($name[$key])) {
            return $name[$key];
        }
        $fullName = [$name['first'], $name['last']];
        if ($withTitle) {
            array_unshift($fullName, "${name['title']}.");
        }

        return implode(' ', $fullName);
    }

    public function getFirstName() : string
    {
        return $this->getName('first');
    }

    public function getLastName() : string
    {
        return $this->getName('last');
    }

    public function getCountry() : string
    {
        return $this->getAttribute('location.country');
    }

    public function getCity() : string
    {
        return $this->getAttribute('location.city');
    }

    public function getUserName() : string
    {
        return $this->getAttribute('login.username');
    }

    public function isGender($gender) : bool
    {
        if (in_array($gender, [self::GENDER_FEMALE, self::GENDER_MALE], true)) {
            return $this->getAttribute('gender') === $gender;
        }

        return false;
    }

    public function isFemale() : bool
    {
        return $this->isGender(self::GENDER_FEMALE);
    }

    public function isMale() : bool
    {
        return $this->isGender(self::GENDER_MALE);
    }

    public function getPassword($type = self::PASSWORD_TYPE_RAW) : string
    {
        if (in_array($type, $this->getPasswordTypes(), true)) {
            return $this->getAttribute("login.${type}");
        }

        return $this->getAttribute('login.password');
    }

    protected function getPasswordTypes() : array
    {
        return [self::PASSWORD_TYPE_RAW, self::PASSWORD_TYPE_MD5, self::PASSWORD_TYPE_SHA1, self::PASSWORD_TYPE_SHA256];
    }
}
