<?php

declare(strict_types=1);

namespace OnePix\WordPressComponents;

final class TemplatesManager implements \OnePix\WordPressContracts\TemplatesManager
{
    public function __construct(
        private readonly string $templatesPath,
        private readonly bool $isDev = false
    )
    {
    }

    public function printTemplate(string $templateName, array $data = []): void
    {
        echo $this->getTemplate($templateName, $data);
    }

    public function getTemplate(string $templateName, array $data = []): string
    {
        if (! str_ends_with($templateName, '.php')) {
            $templateName .= '.php';
        }

        $templatePath = $this->templatesPath . $templateName;

        if (file_exists($templatePath)) {
            ob_start();

            extract($data); //phpcs:ignore WordPress.PHP.DontExtract.extract_extract

            include $templatePath;

            $content = ob_get_clean();
        } elseif ($this->isDev) {
            $content = sprintf("Template '%s' not found", $templatePath);
        } else {
            $content = '';
        }

        return $content;
    }

    public function templateExists(string $templateName): bool
    {
        if (! str_ends_with($templateName, '.php')) {
            $templateName .= '.php';
        }

        return file_exists($this->templatesPath . $templateName);
    }
}
