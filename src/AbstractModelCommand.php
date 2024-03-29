<?php

namespace Application\Console;

class AbstractModelCommand extends AbstractCommand
{
    public function saveModel(
        string $modelName,
        string $filename
    ): int {
        $model = $this->getActiveRecordPHP($modelName);
        return $this->saveFile($model, $filename);
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

class $modelClass extends ActiveRecord\Model
{
    public static \$table_name = '$modelName';
}";
    }
}
