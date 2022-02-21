<?php


namespace OZiTAG\Tager\Backend\Core\Dto;


class Dto
{
    protected array $_data;

    protected function setFromDataObject(stdClass|array $data): void {
        foreach ($data as $key => $value) {
            property_exists($this, $key) && $this->$key = $value;
        }
        $this->_data = $data;
    }
}
