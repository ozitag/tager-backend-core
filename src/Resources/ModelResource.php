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
     * @param array $params
     * @return mixed
     */
    private function getFileValue($value, $params)
    {
        if (!$value || $value instanceof File == false) {
            return null;
        }

        $type = $params[0] ?? 'url';
        $scenario = $params[1] ?? null;
        $thumbnail = $params[2] ?? null;

        switch ($type) {
            case 'url':
                return $value->getUrl($scenario);
            case 'model':
                return $value->getShortJson();
            case 'json':
                return $thumbnail ? $value->getThumbnailJson($thumbnail, $scenario) : $value->getFullJson($scenario);
            default:
                return null;
        }
    }

    private function getLatLngValue($value)
    {
        if (!$value) {
            return null;
        }

        list ($lat, $lng) = explode(';', $value);

        return [
            'latitude' => floatval($lat),
            'longitude' => floatval($lng)
        ];
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
                $result[] = $this->parseArray($as, $item);
            }
            return $result;
        }

        return $this->parseArray($as, $value);
    }

    private function getRelationAttribute($model, $field)
    {
        $path = explode('.', $field);
        if (count($path) == 1) {
            return $model;
        }

        $result = $model;
        for ($i = 0; $i < count($path); $i++) {
            if (!$result) {
                return null;
            }
            $result = $result->{$path[$i]};
        }

        return $result;
    }

    private function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private function parseField($field, $model = null)
    {
        $model = is_null($model) ? $this : $model;

        if (is_array($field) && isset($field['relation'])) {
            return $this->getRelationValue($field);
        }

        if (is_array($field) && $this->isAssoc($field)) {
            return $this->parseArray($field, $model);
        }

        $fieldParams = is_array($field) ? $field : explode(':', $field);
        $attribute = array_shift($fieldParams);
        if (mb_strpos($attribute, '.') !== false) {
            $value = $this->getRelationAttribute($model, $attribute);
        } else {
            $value = $model->{$attribute};
        }

        if (!empty($fieldParams)) {
            $type = array_shift($fieldParams);

            switch (mb_strtolower($type)) {
                case 'file':
                    return $this->getFileValue($value, $fieldParams);
                case 'latlng':
                    return $this->getLatLngValue($value);
                default:
                    throw new \Exception('Invalid type "' . $type . '"');
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

            if (is_array($field)) {
                $field = $field[0];
            } else if (mb_strpos($field, ':') !== false) {
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
