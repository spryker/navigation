<?php
namespace SprykerFeature\Zed\Library\Import;

interface ValidatorInterface
{
    /**
     * @param array $data Array of Rows
     * @throws Exception\SourceNotValidException
     */
    public function validate(array $data);
}