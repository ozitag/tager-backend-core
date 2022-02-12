<?php

namespace OZiTAG\Tager\Backend\Core\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use JetBrains\PhpStorm\Pure;

class SeoParamsResource extends JsonResource
{
    protected ?string $title = null;

    protected ?string $description = null;

    protected ?string $keywords = null;

    protected ?string $openGraphImage = null;

    #[Pure]
    public function __construct(?string $title, ?string $description = null, ?string $keywords = null)
    {
        parent::__construct([]);

        $this->title = $title;
        $this->description = $description;
        $this->keywords = $keywords;
    }

    public function setOpenGraphImage(?string $imageUrl): self
    {
        $this->openGraphImage = $imageUrl;
        return $this;
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
            'openGraphImage' => $this->openGraphImage,
        ];
    }
}
