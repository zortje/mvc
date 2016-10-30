<?php
declare(strict_types = 1);

namespace Zortje\MVC\View\Render;

/**
 * Class HtmlRender
 *
 * @package Zortje\View\Render
 */
class HtmlRender
{

    /**
     * @var array View variables
     */
    protected $variables;

    /**
     * @param array $variables
     */
    public function __construct(array $variables)
    {
        $this->variables = $variables;
    }

    /**
     * Render files
     *
     * First file in array is rendered first and key is set in variables array for use by following files
     *
     * @param array $files Files to render
     *
     * @return string Output
     */
    public function render(array $files): string
    {
        $output = '';

        foreach ($files as $outputName => $file) {
            $output = $this->renderFile($file);

            $this->variables[$outputName] = $output;
        }

        return $output;
    }

    /**
     * Render file
     *
     * @param string $file File to render
     *
     * @return string Output
     */
    protected function renderFile(string $file): string
    {
        if (!is_readable($file)) {
            throw new \InvalidArgumentException(sprintf('File "%s" is nonexistent (Working directory: %s)', $file, getcwd()));
        }

        /**
         * Start the output buffer and require file
         */
        ob_start();

        /**
         * Prepare set variables for the view
         *
         * @todo Helpers, `$this->loadHelpers();`
         */
        foreach ($this->variables as $variable => $value) {
            $$variable = $value;
        }

        require $file;

        /**
         * Clean the output buffer and return
         */
        $output = ob_get_clean();

        return $output;
    }
}
