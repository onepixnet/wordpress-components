<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

final class RewriteRulesManager implements \OnePix\WordPressContracts\RewriteRulesManager
{
    public function __construct(
        private readonly string $optionPrefix
    )
    {
    }

    public function flushRewriteRules(bool $disablingPlugin = false): void
    {
        if ($disablingPlugin) {
            flush_rewrite_rules(false);
            $this->setRewriteRulesFlashed(false);
        } elseif (! $this->wasRewriteRulesFlashed()) {
            flush_rewrite_rules(false);
            $this->setRewriteRulesFlashed(true);
        }
    }

    private function setRewriteRulesFlashed(bool $value): void
    {
        update_option(
            $this->getRewriteRulesFlushedOptionKey(),
            $value ? '1' : '0'
        );
    }

    private function wasRewriteRulesFlashed(): bool
    {
        return get_option($this->getRewriteRulesFlushedOptionKey()) === '1';
    }

    private function getRewriteRulesFlushedOptionKey(): string
    {
        return $this->optionPrefix . '_rewrite_rules_flushed';
    }
}
