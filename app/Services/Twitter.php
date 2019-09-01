<?php

namespace App\Services;

class Twitter
{
    private $twitter;

    /**
     * @var int
     */
    private $loadMode = \DG\Twitter\Twitter::ME;

    public function __construct()
    {
        $this->twitter = new \DG\Twitter\Twitter(
            config('twitter.consumer_key'),
            config('twitter.consumer_secret'),
            config('twitter.access_token'),
            config('twitter.access_token_secret')
        );
    }

    /**
     * @return bool
     * @throws \DG\Twitter\Exception
     */
    public function auth(): bool
    {
        return $this->twitter->authenticate();
    }

    /**
     * @param string $tweet
     * @return \stdClass
     * @throws \DG\Twitter\Exception
     */
    public function send(string $tweet)
    {
        return $this->twitter->send($tweet);
    }

    /**
     * @param string $tweet
     * @param array $options
     * @return \stdClass
     * @throws \DG\Twitter\Exception
     */
    public function reply(string $tweet, array $options = [])
    {
        abort_if(empty($options['tweet_id']), 500, __('You must provide tweet_id key from params'));

        return $this->twitter->send($tweet, null, [
            'in_reply_to_status_id' => $options['tweet_id'],
        ]);
    }

    /**
     * @return Twitter
     */
    public function me(): self
    {
        $this->loadMode = \DG\Twitter\Twitter::ME;

        return $this;
    }

    /**
     * @return Twitter
     */
    public function friends(): self
    {
        $this->loadMode = \DG\Twitter\Twitter::ME_AND_FRIENDS;

        return $this;
    }

    /**
     * @return Twitter
     */
    public function replies(): self
    {
        $this->loadMode = \DG\Twitter\Twitter::REPLIES;

        return $this;
    }

    /**
     * @return array|\stdClass[]
     * @throws \DG\Twitter\Exception
     */
    public function load()
    {
        return $this->twitter->load($this->loadMode);
    }

    /**
     * @param $query
     * @return array|null
     * @throws \DG\Twitter\Exception
     */
    public function search($query): ?array
    {
        $results = $this->twitter->request('search/tweets', 'GET', is_array($query) ? $query : ['q' => $query]);

        return $results->statuses ?? null;
    }
}
