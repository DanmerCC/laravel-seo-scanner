<?php

namespace Vormkracht10\Seo\Checks\Meta;

use Illuminate\Http\Client\Response;
use Symfony\Component\DomCrawler\Crawler;
use Vormkracht10\Seo\Interfaces\Check;
use Vormkracht10\Seo\Traits\PerformCheck;

class TitleLengthCheck implements Check
{
    use PerformCheck;

    public string $title = 'The page title is not longer than 60 characters';

    public string $priority = 'medium';

    public int $timeToFix = 1;

    public int $scoreWeight = 5;

    public bool $continueAfterFailure = true;

    public ?string $failureReason;

    public mixed $actualValue = null;

    public mixed $expectedValue = 60;

    public function check(Response $response, Crawler $crawler): bool
    {
        if (! $this->validateContent($crawler)) {
            return false;
        }

        return true;
    }

    public function validateContent(Crawler $crawler): bool
    {
        $node = $crawler->filterXPath('//title')->getNode(0);

        if (! $node) {
            $this->failureReason = __('failed.content.no_title');

            return false;
        }

        $content = $crawler->filterXPath('//title')->text();

        if (! $content) {
            $this->failureReason = __('failed.content.no_title');

            return false;
        }

        if (strlen($content) > $this->expectedValue) {
            $this->failureReason = __('failed.content.title_length', [
                'actualValue' => strlen($content),
                'expectedValue' => $this->expectedValue,
            ]);

            return false;
        }

        return true;
    }
}
