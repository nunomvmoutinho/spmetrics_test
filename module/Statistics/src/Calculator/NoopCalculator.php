<?php

declare(strict_types = 1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class NoopCalculator extends AbstractCalculator
{
    protected const UNITS = 'posts';

    /**
     * @var int
     */
    private $postCount = 0;

    /**
     * @var array
     */
    private $usersIds = [];

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $this->postCount++;
        
        if(!in_array($postTo->getAuthorId(), $this->usersIds, true)){
            array_push($this->usersIds, $postTo->getAuthorId());
        }
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $value = $this->postCount > 0
            ? $this->postCount / sizeof($this->usersIds)
            : 0;

        return (new StatisticsTo())->setValue(round($value,2))->setUnits(self::UNITS);
    }
}
