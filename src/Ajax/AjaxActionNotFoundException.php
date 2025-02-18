<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents\Ajax;

final class AjaxActionNotFoundException extends \RuntimeException
{
    public static function byAction(string $action, AjaxManager $ajaxManager): self
    {
        return new self(sprintf('Action %s not found in %s class', $action, $ajaxManager::class));
    }
}
