<?php

namespace OZiTAG\Tager\Backend\Core\Models\Contracts;

interface IPublicWebModel
{
    public function getWebPageUrl(): string;

    public function getWebPageTitle(): string;

    public function getWebPageDescription(): ?string;

    public function getWebOpenGraphImageUrl(): ?string;

    public function getPanelType(): ?string;

    public function getPanelTitle(): ?string;
}
