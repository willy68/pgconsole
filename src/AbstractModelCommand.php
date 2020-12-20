<?php

namespace Application\Console;

use Symfony\Component\Console\Style\SymfonyStyle;

class AbstractModelCommand extends AbstractCommand
{

    public function saveModel(
        string $modelName,
        string $filename,
        SymfonyStyle $io
    ): int {
        $model = $this->getActiveRecordPHP($modelName);
        return $this->saveFile($model, $filename, $io);
    }

    /**
     *
     *
     * @param string $modelName
     * @return string
     */
    public function getActiveRecordPHP(string $modelName): string
    {
        $modelClass = $this->getclassName($modelName);
        return "<?php

namespace App\Models;

use ActiveRecord;

class {$modelClass} extends ActiveRecord\Model
{
    public static \$table_name = '{$modelName}';
}";
    }
}
