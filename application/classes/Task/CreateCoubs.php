<?php defined('SYSPATH') or die('No direct script access.');


class Task_CreateCoubs extends Minion_Task
{
	protected $_options = array(
		'query' => 'cars'
	);

	/**
	 * Generates a help list for all tasks
	 *
	 * @return null
	 */
	protected function _execute(array $params)
	{
        $videos = array();

        for($i = 1; $i < 100; $i ++) {
            $resultCoubs = Coub_Search::method('Coubs')
                ->setQuery($params['query'])
                ->setOrderBy(Coub::ORDER_BY_LIKES_COUNT)
                ->setPage($i)
                ->getResult();



            foreach($resultCoubs->coubs as $coub) {
                $guid = md5(time() . $params['query']);
                $destination = DOCROOT.'uploads/'.$coub->id;
                if(!file_exists($destination)) {
                    mkdir($destination);
                }

                $destinationFinal = DOCROOT.'uploads/'.$guid.'.mp4';
                $destinationFinalTmp = DOCROOT.'uploads/'.$guid.'_tmp.mp4';
                if(empty($coub->file_versions->html5->video->high) || empty($coub->file_versions->html5->audio->high)) {
                    continue;
                }

                $videoFileInfo = pathinfo($coub->file_versions->html5->video->high->url);
                $videoDestination = $destination.'/'.$videoFileInfo['basename'];
                $videoWithAudioDestination = $destination.'/not_muted_'.$videoFileInfo['basename'];

                if(!file_exists($videoDestination)) {
                    try {
                        copy($coub->file_versions->html5->video->high->url, $videoDestination);
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }

                $audioFileInfo = pathinfo($coub->file_versions->html5->audio->high->url);
                $audioDestination = $destination.'/'.$audioFileInfo['basename'];
                $audioTrimedDestination = $destination.'/trimed_'.$audioFileInfo['basename'];

                if(!file_exists($audioDestination)) {
                    try {
                        copy($coub->file_versions->html5->audio->high->url, $audioDestination);
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }

                $videoFileDuration = $this->getDuration($videoDestination);
                $audioFileDuration = $this->getDuration($audioDestination);

                $this->checkLength($videoFileDuration, $audioFileDuration, $audioDestination, $audioTrimedDestination);

                $videoFileDuration = $this->getDuration($videoDestination);
                $audioFileDuration = $this->getDuration($audioDestination);


                $videoDate = date_create($videoFileDuration);
                $audioDate = date_create($audioFileDuration);
                if(!$videoDate || !$audioDate) {
                    continue;
                }
                if($videoDate->getTimestamp() > $audioDate->getTimestamp()) {
                    $this->exec(sprintf('echo "yes" | ffmpeg -i "concat:%s|%s" -vcodec copy -acodec copy %s', $audioDestination, $audioDestination, $audioTrimedDestination));
                }
                $this->checkLength($videoFileDuration, $audioFileDuration, $audioDestination, $audioTrimedDestination);
                $result = $this->applyAudio($audioDestination, $videoDestination, $videoWithAudioDestination);
                $finalDestinition = DOCROOT. 'uploads/'.$guid.'.mp4';
                if(file_exists($videoWithAudioDestination)) {
                    copy($videoWithAudioDestination, $finalDestinition);
                    $videos[] = sprintf('file %s', $guid.'.mp4');
                }

            }

            if($i % 5 == 0) {
                $listPath = DOCROOT.'uploads/list.txt';
                $list = implode(PHP_EOL, $videos);
                file_put_contents($listPath, $list);
                chdir(DOCROOT.'uploads/');
                unset($result);
                $result = $this->exec(sprintf('ffmpeg -f concat -i %s -c copy %s', 'list.txt', 'final.mp4'));
                $this->exec(sprintf('mv %s %s', 'final.mp4', DOCROOT.'compiled/'.md5(time() . $params['query']).'.mp4'));
                $this->exec('rm -fR ./*');
                $videos = array();
            }

        }
	}

	public function exec($command) {
		$result = '';
		$fp = popen($command . ' 2>&1', "r");
		while(!feof($fp))
		{
			// send the current file part to the browser
			$result.= fread($fp, 1024);

		}
		fclose($fp);
		return $result;
	}

	public function getDuration($file) {
		$output = $this->exec(sprintf("ffmpeg -i %s", $file));
		preg_match('/Duration:(.*?),/s', $output, $results);
		if(isset($results[1])) {
			return trim($results[1]);
		}
		return false;
	}

    /**
     * @param $videoFileDuration
     * @param $audioFileDuration
     * @param $audioDestination
     * @param $audioTrimedDestination
     */
    protected function checkLength($videoFileDuration, $audioFileDuration, $audioDestination, $audioTrimedDestination)
    {
        $videoDate = date_create($videoFileDuration);
        $audioDate = date_create($audioFileDuration);
        if ($videoDate->getTimestamp() < $audioDate->getTimestamp()) {
            $cropToPart = explode('.', $videoFileDuration);
            $cropOutput = $this->exec(sprintf("echo \"yes\" | ffmpeg -i %s -acodec copy -ss 00:00:00 -t %s %s", $audioDestination, $cropToPart[0], $audioTrimedDestination));
            if(file_exists($audioDestination)) {
                unlink($audioDestination);
            }
            if(file_exists($audioTrimedDestination)) {
                copy($audioTrimedDestination, $audioDestination);
                unlink($audioTrimedDestination);
            }
        }
    }

    protected function applyAudio($audio, $video, $resultVideo) {
        return $this->exec(sprintf('echo "y" | ffmpeg -i %s -i %s -strict -2 %s', $audio, $video, $resultVideo));
    }

}
