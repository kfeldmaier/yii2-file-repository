<?php

namespace kfeldmaier\filerepository\console;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use yii\helpers\Url;
use kfeldmaier\filerepository\models\Repository;
use kfeldmaier\filerepository\models\DirectoryLink;
use kfeldmaier\filerepository\models\FileLink;

/**
 * Repository controller
 */
class RepositoryController extends Controller
{

    public function actionUpdate($reposId=null)
    {
        // if auto, do not prompt and/or confirm
        $auto = true;

    	if (empty($reposId))
        {
            $reposId = $this->prompt('ID of Repository to update:');
            $auto = false;
        }
    		

    	if (($model = Repository::findOne($reposId)) !== null) 
    	{
            if ($this->isBlocked($reposId))
            {
                if (!$this->confirm('Repository currently blocked! Do you wish to unblock (not recommended) "'.$model['path'].'"?', false))
                {
                    return Controller::EXIT_CODE_ERROR;
                } else {
                    $this->blockProgress($reposId, false);
                }
            }

	        if ($auto || (!$auto && $this->confirm('Start synchronizing "'.$model['path'].'"?', false)))
	        {
	        	$this->blockProgress($reposId, true);

                $success = $this->createDirectoryList($model['id'], $model['path']);

                /*
				Console::startProgress(0, 1000, 'Counting objects: ', false);
				for ($n = 1; $n <= 1000; $n++) {
				    usleep(1000);
				    Console::updateProgress($n, 1000);
				}
				Console::endProgress("done." . PHP_EOL);
                */

				$this->blockProgress($reposId, false);

	        	return $success ? Controller::EXIT_CODE_NORMAL : Controller::EXIT_CODE_ERROR;
	        }

        } else {
            $this->stdout("Repository not found!\n", Console::BOLD); 
        }

        return Controller::EXIT_CODE_ERROR;
    }

    protected function createDirectoryList($reposId, $path, $level=0)
    {
        if (!$this->isBlocked($reposId))
            return false;

        $dirListFile = 'repos-'.$reposId.'.tmp';

        if (!$level && file_exists($dirListFile))
            unlink($dirListFile);

        foreach(scandir($path) as $folder)
        {
            if ($folder == '.' || $folder == '..')
                continue;

            $tmpPath = $path.DIRECTORY_SEPARATOR.$folder;

            if (is_dir($tmpPath) && is_readable($tmpPath))
            {
                $line = $tmpPath.PHP_EOL;
                $this->stdout(str_repeat('·', ($level*2)).$line);

                file_put_contents($dirListFile, $line, FILE_APPEND | LOCK_EX);

                if (!$this->createDirectoryList($reposId, $tmpPath, $level+1))
                    return false;
            }
        }

        return true;
    }

    protected function blockProgress($reposId, $flag=false)
    {
    	$filename = 'repos-'.$reposId.'.lock';
    	if ($flag && !file_exists($filename))
    	{
    		@file_put_contents($filename, '---');
    	} else if (!$flag && file_exists($filename))
		{
			unlink($filename);
		}
    }

    protected function isBlocked($reposId)
    {
    	return file_exists('repos-'.$reposId.'.lock');
    }

}