<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Database\Eloquent\Collection;
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

    private function getRelationValue($field)
    {
        if (!isset($field['relation'])) {
            throw new \Exception('Relation is not set');
        }

        $relation = $field['relation'];
        $value = $this->{$relation};

        if (!isset($field['as'])) {
            throw new \Exception('As not set');
        }
        $as = $field['as'];

        if (!is_array($as)) {
            if ($value instanceof Collection) {
                $result = [];
                foreach ($value as $item) {
                    $result[] = $this->parseField($field['as'], $item);
                }
                return $result;
            } else {
                return $this->parseField($field['as'], $value);
            }
        }

        if ($value instanceof Collection) {
            $result = [];
            foreach ($value as $item) {
                $result[] = $this->parseArray($as, $item);;
            }
            return $result;
        }

        return $this->parseArray($as, $value);
    }

    private function parseField($field, $model = null)
    {
        $model = is_null($model) ? $this : $model;

        if (is_array($field)) {
            return $this->getRelationValue($field);
        }

        $fieldParams = explode(':', $field);
        $value = $model->{$fieldParams[0]};

        if (count($fieldParams) > 1) {
            if ($fieldParams[1] == 'file') {
                return $this->getFileValue($value, $fieldParams[2]);
            }
        }

        return $value;
    }

    private function parseArray($fields, $model)
    {
        $result = [];

        foreach ($fields as $field => $fieldData) {
            if (is_numeric($field)) {
                $field = $fieldData;
            }

            if (mb_strpos($field, ':') !== false) {
                $field = mb_substr($field, 0, mb_strpos($field, ':'));
            }

            $result[$field] = $this->parseField($fieldData, $model);
        }

        return $result;
    }

    public function toArray($request)
    {
        return $this->parseArray($this->fields(), $this);
    }
}