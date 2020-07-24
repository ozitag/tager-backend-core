<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Ozerich\FileStorage\Models\File;

abstract class ModelResource extends JsonResource
{
    abstract protected function fields();

    /**
     * @param File $value
     * @param string $type
     * @return mixed
     */
    private function getFileValue($value, $type)
    {
        switch ($type) {
            case 'url':
                return $value->getUrl();
            case 'model':
                return $value->getShortJson();
            case 'json':
                return $value->getFullJson();
            default:
                return null;
        }
    }

    private function parseField($field)
    {
        $fieldParams = explode(':', $field);
        $value = $this->{$fieldParams[0]};

        if (count($fieldParams) > 1) {
            if ($fieldParams[1] == 'file') {
                return $this->getFileValue($value, $fieldParams[2]);
            }
        }

        return $value;
    }

    public function toArray($request)
    {
        $result = [];

        foreach ($this->fields() as $field => $fieldData) {
            if (is_numeric($field)) {
                $field = $fieldData;
            }

            $result[$field] = $this->parseField($fieldData);
        }

        return $result;
    }
}
