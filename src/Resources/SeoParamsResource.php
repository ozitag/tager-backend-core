<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SeoParamsResource extends JsonResource
{
    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $openGraphImage = null;

    protected ?string $openGraphTitle = null;

    protected ?string $openGraphDescription = null;

    public function __construct($title, $description = null, $keywords = null)
    {
        parent::__construct([]);

        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
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
            'keywords' => $this->keywords,
            'openGraph' => [
                'title' => !empty($this->openGraphTitle) ? $this->openGraphTitle : $this->title,
                'description' => !empty($this->openGraphDescription) ? $this->openGraphDescription : $this->description,
                'image' => $this->openGraphImage
            ],
        ];
    }
}
