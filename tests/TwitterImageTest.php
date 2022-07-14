<?php

namespace NotificationChannels\Twitter\Test;

use NotificationChannels\Twitter\TwitterImage;

class TwitterImageTest extends TestCase
{
    /** @test */
    public function it_accepts_an_image_path_when_constructing_a_twitter_image(): void
    {
        $image = new TwitterImage('/foo/bar/baz.png');
        $this->assertEquals('/foo/bar/baz.png', $image->getPath());
    }

    public function it_accepts_an_image_path_and_alt_when_constructing_a_twitter_image(): void
    {
        $image = new TwitterImage('/foo/bar/baz.png', 'Baz');
        $this->assertEquals('/foo/bar/baz.png', $image->getPath(), $image->getAlt());
    }
}
