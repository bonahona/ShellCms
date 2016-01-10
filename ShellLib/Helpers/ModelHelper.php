<?php
/** Helps with stuff all models will use but that should be included in the class as it really doesn't really belong to the actual model
 * */

class ModelHelper
{
    protected $ReferenceUpdatesMade;
    protected $TableReferences;

    function __construct()
    {
        $this->TableReferences = array();
    }

    public function GetModelFilePath($modelPath) {
        // Remove the file ending
        $modelPath     = str_replace(PHP_FILE_ENDING, '', $modelPath);
        $localPath     = MODEL_CACHE_FOLDER . $modelPath . MODEL_CACHE_FILE_ENDING;
        $cacheFilePath = Directory($localPath);
        return $cacheFilePath;
    }

    public function CacheModelFromModel($core, $modelName, $filePath, $dontCacheModels)
    {
        $core->GetModelFolder() . $modelName;
        $modelPath = Directory($core->GetModelFolder() . $modelName);

        require_once($modelPath);

        // Find the name of the model
        $modelName = str_replace(PHP_FILE_ENDING, '', $modelName);

        $modelInstance = new $modelName(NULL);
        $tableName     = $modelInstance->TableName;

        $db = Core::$Instance->GetDatabase();

        $response = $db->DescribeTable($tableName);
        if ($response == NULL) {
            die("Missing table " . $tableName);
        }

        // Find references to other tables
        foreach ($response['References'] as $reference) {
            $this->ReferenceUpdatesMade = true;                     // Flag for updates in some references. This will cause the program the to update already cached models be checked for new references
            $foreignTableName = $reference['TableName'];
            $this->AddTableReference($modelName, $foreignTableName, $reference);
        }

        if(!$dontCacheModels) {
            // Save the data to cache
            $saveResult = file_put_contents($filePath, json_encode($response));

            if ($saveResult == false) {
                die("Failed to save model " . $modelName);
            }
        }


        $modelCache             = &Core::$Instance->GetModelCache();
        $modelCache[$modelName] = $response;

        return $response;
    }

    public function ReadModelCache($core, $modelName, $filePath)
    {
        $modelPath = Directory($core->GetModelFolder() . $modelName);
        require_once($modelPath);

        $modelName = str_replace(PHP_FILE_ENDING, '', $modelName);
        $buffer    = file_get_contents($filePath);

        $modelCache             = &Core::$Instance->GetModelCache();
        $result                 = json_decode($buffer, true);
        $modelCache[$modelName] = $result;

        return $result;
    }

    public function SaveModelCache($filePath, $modelName, $modelData)
    {
        // Save the data to cache
        $saveResult = file_put_contents($filePath, json_encode($modelData));

        if ($saveResult == false) {
            die("Failed to save model " . $modelName);
        }
    }

    public function ReferencesUpdated()
    {
        return $this->ReferenceUpdatesMade;
    }

    public function CheckForReferences($modelName, &$modelCache, $pluralizer)
    {
        if(array_key_exists($modelName, $this->TableReferences)){
            foreach($this->TableReferences[$modelName] as $reference){
                $referencePluralForm = $pluralizer->Pluralize($reference['ModelName']);
                $modelCache['ReversedReferences'][$referencePluralForm] = $reference;
            }
        }
    }

    protected function AddTableReference($modelName, $tableName, $reference)
    {
        $reference['ModelName'] = $modelName;
        if(!array_key_exists($tableName, $this->TableReferences)){
            $this->TableReferences[$tableName] = array();
        }

        $this->TableReferences[$tableName][] = $reference;
    }
}