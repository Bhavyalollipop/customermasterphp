<?php
namespace Acc\Models;
class CusAddr extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $cus_addr_id;

    /**
     *
     * @var integer
     */
    public $cus_id;

    /**
     *
     * @var string
     */
    public $cus_addr_line_1;

    /**
     *
     * @var string
     */
    public $cus_addr_line_2;

    /**
     *
     * @var string
     */
    public $cus_landmark;

    /**
     *
     * @var string
     */
    public $cus_city;

    /**
     *
     * @var string
     */
    public $cus_state;

    /**
     *
     * @var integer
     */
    public $cus_country;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var string
     */
    public $created_by;

    /**
     *
     * @var string
     */
    public $updated_by;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setConnectionService("db");
        $this->setSource("cus_addr");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CusAddr[]|CusAddr|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CusAddr|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
