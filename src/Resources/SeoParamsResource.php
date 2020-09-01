<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SeoParamsResource extends JsonResource
{
    protected $title;

    protected $description;

    protected $openGraphImage;

    protected $openGraphTitle;

    protected $openGraphDescription;

    public function __construct($title, $description = null)
    {
        parent::__construct([]);

        $this->title = $title;
        $this->description = $description;
    }

    public function setOpenGraph($imageUrl, $title = null, $description = null)
    {
        $this->openGraphImage = $imageUrl;
        $this->openGraphTitle = $title;
        $this->openGraphDescription = $description;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'openGraph' => [
                'title' => !empty($this->openGraphTitle) ? $this->openGraphTitle : $this->title,
                'description' => !empty($this->openGraphDescription) ? $this->openGraphDescription : $this->description,
                'image' => $this->openGraphImage
            ]
        ];
    }
}
