<?php
namespace Acc\Models;
class CusMain extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $cus_id;

    /**
     *
     * @var string
     */
    public $cus_fname;

    /**
     *
     * @var string
     */
    public $cus_lname;

    /**
     *
     * @var string
     */
    public $cus_mail;

    /**
     *
     * @var string
     */
    public $cus_ph_no;

    /**
     *
     * @var string
     */
    public $cus_website;

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
        $this->setSource("cus_main");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CusMain[]|CusMain|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null): \Phalcon\Mvc\Model\ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CusMain|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
