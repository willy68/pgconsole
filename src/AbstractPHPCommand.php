<?php

namespace Application\Console;

class AbstractPHPCommand extends AbstractCommand
{
    protected $model = null;

    protected $namespace = 'App\Api';

    protected $template = null;

    protected $dir = null;

    /**
     * Saved parsed template in file
     *
     * @param string $modelName
     * @param string $filename
     * @return int
     */
    protected function saveController(
        string $modelName,
        string $filename
    ): int {
        $php = $this->getPHPController($modelName);
        return $this->saveFile($php, $filename);
    }

    /**
     * get parsed controller string
     *
     * @param string $modelName
     * @return string
     */
    protected function getPHPController(string $modelName): string
    {
        $modelClass = $this->getClassName($modelName);

        if ($this->template && file_exists($this->template)) {
            return include $this->template;
        } else {
            return "<?php
namespace " . $this->namespace . "\\$modelClass;
  
use App\Models\\$modelClass;
use GuzzleHttp\Psr7\Response;
use App\Api\AbstractApiController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class {$modelClass}Controller extends AbstractApiController
{
  
    /**
     * Model class
     *
     * @var string
     */
    protected \$model = $modelClass::class;

    /**
     * Default to 'entreprise_id'
     * @var string
     */
    protected \$foreignKey = '';

    /**
     * Get list of record
     *
     * @param ServerRequestInterface \$request
     * @return ResponseInterface
     */
    public function list(ServerRequestInterface \$request): ResponseInterface
    {
        \$options = [];
        \$attributes = \$request->getAttributes();

        if (!empty(\$this->foreignKey) && (isset(\$attributes[\$this->foreignKey]))) {
            \$options['conditions'] = [\$this->foreignKey . ' = ?', \$attributes[\$this->foreignKey]];
        }

        \$options = \$this->getQueryOption(\$request, \$options);
        try {
            if (!empty(\$options)) {
                \$models = \$this->model::all(\$options);
            } else {
                \$models = \$this->model::all();
            }
        } catch (\ActiveRecord\RecordNotFound \$e) {
            return new Response(404);
        }
        if (empty(\$models)) {
            return new Response(404);
        }
        \$json = \$this->jsonArray(
            \$models,
            isset(\$options['include']) ? ['include' => \$options['include']] : []
        );
        return new Response(200, [], \$json);
    }
}";
        }
    }
}
