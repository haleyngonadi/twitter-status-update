<?php

namespace NotificationChannels\Twitter;

use Illuminate\Support\Collection;
use Kylewm\Brevity\Brevity;
use NotificationChannels\Twitter\Exceptions\CouldNotSendNotification;

class TwitterStatusUpdate extends TwitterMessage
{
    public ?Collection $imageIds = null;
    private ?array $images = null;

    /**
     * @throws CouldNotSendNotification
     */
    public function __construct(string $content)
    {
        parent::__construct($content);

        if ($exceededLength = $this->messageIsTooLong(new Brevity())) {
            throw CouldNotSendNotification::statusUpdateTooLong($exceededLength);
        }
    }

    public function getApiEndpoint(): string
    {
        return 'statuses/update';
    }

    /**
     * Set Twitter media files.
     *
     * @return $this
     */
    public function withImage(array|string $images): static
    {
        $images = is_array($images) ? $images : [$images];

        collect($images)->each(function ($image) {
            $this->images[] = new TwitterImage($image);
        });

        return $this;
    }

    /**
     * Set Twitter media files with alt text.
     *
     * @return $this
     */
    public function withImageAndAlt(array|string $images, array|string $alts): static
    {
        $images = is_array($images) ? $images : [$images];
        $alts = is_array($alts) ? $alts : [$alts];

        collect($images)->each(function ($image, $key) use ($alts) {
            $this->images[] = new TwitterImage($image, $alts[$key]);
        });

        return $this;
    }

    /**
     * Get Twitter images list.
     */
    public function getImages(): ?array
    {
        return $this->images;
    }

    /**
     * Build Twitter request body.
     */
    public function getRequestBody(): array
    {
        $body = ['status' => $this->getContent()];

        if ($this->imageIds instanceof Collection) {
            $body['media_ids'] = $this->imageIds->implode(',');
        }

        return $body;
    }

    /**
     * Check if the message length is too long.
     *
     * @return int How many characters the max length is exceeded or 0 when it isn't.
     */
    private function messageIsTooLong(Brevity $brevity): int
    {
        $tweetLength = $brevity->tweetLength($this->content);
        $exceededLength = $tweetLength - 280;

        return max($exceededLength, 0);
    }
}
