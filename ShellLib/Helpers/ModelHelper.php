<?php
/** Helps with stuff all models will use but that should be included in the class as it really doesn't really belong to the actual model
 * */

function GetModelFilePath($core, $modelPath)
{
    // Remove the file ending
    $modelPath = str_replace(PHP_FILE_ENDING, '', $modelPath);
    $localPath = MODEL_CACHE_FOLDER . $modelPath . MODEL_CACHE_FILE_ENDING;
    $cacheFilePath = Directory($localPath);
    return $cacheFilePath;
}

function CacheModelFromModel($core, $modelName, $filePath)
{
    $core->GetModelFolder() . $modelName;
    $modelPath = Directory($core->GetModelFolder() . $modelName);

    require_once($modelPath);

    // Find the name of the model
    $modelName = str_replace(PHP_FILE_ENDING, '', $modelName);

    $modelInstance = new $modelName(null);
    $tableName = $modelInstance->TableName;

    $db = Core::$Instance->GetDatabase();

    $response = $db->DescribeTable($tableName);
    if($response == null){
        die("Missing table " . $tableName);
    }

    // Save the data to cache
    $saveResult = file_put_contents($filePath, json_encode($response));

    if($saveResult == false){
        die("Failed to save model " . $modelName);
    }

    $modelCache = &Core::$Instance->GetModelCache();
    $modelCache[$modelName] = $response;
}

function ReadModelCache($core, $modelName, $filePath)
{
    $modelPath = Directory($core->GetModelFolder() . $modelName);
    require_once($modelPath);

    $modelName = str_replace(PHP_FILE_ENDING, '', $modelName);
    $buffer = file_get_contents($filePath);

    $modelCache = &Core::$Instance->GetModelCache();
    $modelCache[$modelName] = json_decode($buffer, true);
}