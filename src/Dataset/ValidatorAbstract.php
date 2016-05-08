<?php
namespace Sjoerdmaessen\MachineLearning\Dataset;

abstract class ValidatorAbstract
{
    protected $errors = [];

    /**
     * @param $pathToFile
     * @return mixed
     */
    abstract public function validate($pathToFile);

    /**
     * Add validation error
     *
     * @param integer $line
     * @param string $message
     */
    protected function addError($line, $message) {
        $this->errors[$line][] = $message;
    }

    /**
     * Clear errors before validation
     */
    protected function clearErrors() {
        $this->errors = [];
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return empty($this->getErrors());
    }

    /**
     * @return int
     */
    public function getNumberOfErrors()
    {
        return count($this->getErrors());
    }

    /**
     * Returns an array with errors per line
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}