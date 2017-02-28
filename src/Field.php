<?php

namespace Radowoj\Yaah;

class Field
{
    const VALUE_IMAGE = 'fvalueImage';

    /**
     * Allegro WebAPI fid
     * @var integer
     */
    protected $fid = null;

    /**
     * String value of given field
     * @var string
     */
    protected $fvalueString = '';

    /**
     * Integer value of given field
     * @var integer
     */
    protected $fvalueInt = 0;


    /**
     * Float value of given field
     * @var float
     */
    protected $fvalueFloat = 0;

    /**
     * Image (image file content)
     * @var mixed
     */
    protected $fvalueImage = 0;

    /**
     * Unix time
     * @var float
     */
    protected $fvalueDatetime = 0;

    /**
     * Date (dd-mm-yyyy)
     * @var string
     */
    protected $fvalueDate = '';

    /**
     * Integer range
     * @var array
     */
    protected $fvalueRangeInt = [
        'fvalueRangeIntMin' => 0,
        'fvalueRangeIntMax' => 0,
    ];

    /**
     * Float range
     * @var array
     */
    protected $fvalueRangeFloat = [
        'fvalueRangeFloatMin' => 0,
        'fvalueRangeFloatMax' => 0,
    ];

    /**
     * Date range
     * @var array
     */
    protected $fvalueRangeDate = [
        'fvalueRangeDateMin' => '',
        'fvalueRangeDateMax' => '',
    ];

    /**
     * @param integer $fid WebAPI fid for given field
     * @param mixed $value value for given field
     * @param string $forceValueType value type to force (i.e. fvalueImage)
     */
    public function __construct($fid, $value = null, $forceValueType = '')
    {
        $this->fid = $fid;

        //if value type was specified (useful for fvalueImage, fvalueDatetime etc.)
        if ($forceValueType) {
            $this->setValueForced($forceValueType, $value);
            return;
        }

        //if no forced value type is given, autodetect it
        if (is_integer($value)) {
            $this->fvalueInt = $value;
        } elseif (is_float($value)) {
            $this->fvalueFloat = $value;
        } elseif (is_string($value)) {
            $this->setValueStringAutodetect($value);
        } elseif (is_array($value) && count($value) == 2) {
            $this->setValueRangeAutodetect($value);
        } else {
            throw new Exception('Not supported value type: ' . gettype($value) . "; fid={$fid}");
        }

    }


    /**
     * Detect type of string value (date or normal string)
     * @param string $value value to detect type
     */
    protected function setValueStringAutodetect($value)
    {
        if (preg_match('/^\d{2}\-\d{2}\-\d{4}$/', $value)) {
            $this->fvalueDate = $value;
        } else {
            $this->fvalueString = $value;
        }
    }

    /**
     * Detect type of range passed as argument (int, float, date)
     * @param array $value value to detect type
     */
    protected function setValueRangeAutodetect(array $value)
    {

    }


    protected function setValueForced($forceValueType, $value)
    {
        if (!property_exists($this, $forceValueType)) {
            throw new Exception("Class " . get_class($this) . " does not have property: {$forceValueType}");
        }

        $this->{$forceValueType} = $value;
    }

    /**
     * Returns WebAPI representation of Field
     * @return array field
     */
    public function toArray()
    {
        return [
            'fid' => $this->fid,
            'fvalueString' => $this->fvalueString,
            'fvalueInt' => $this->fvalueInt,
            'fvalueFloat' => $this->fvalueFloat,
            'fvalueImage' => $this->fvalueImage,
            'fvalueDatetime' => $this->fvalueDatetime,
            'fvalueDate' => $this->fvalueDate,
            'fvalueRangeInt' => $this->fvalueRangeInt,
            'fvalueRangeFloat' => $this->fvalueRangeFloat,
            'fvalueRangeDate' => $this->fvalueRangeDate,
        ];
    }


}
