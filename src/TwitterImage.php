<?php

namespace NotificationChannels\Twitter;

class TwitterImage
{
    public function __construct(private string $imagePath, private string $imageAlt = '')
    {
    }

    public function getPath(): string
    {
        return $this->imagePath;
    }

    public function getAlt(): string
    {
        return $this->imageAlt;
    }
}
