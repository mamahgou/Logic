<?php

class Logic_Image_Movement
{
	/**
	 * file movement
	 *
	 * @param string $inputPath
	 * @param string $outputPath
	 * @param string $inputName input name or pattern
	 * @param string $outputName output filename
	 * @param boolean $unlink
	 * @return string
	 */
	public static function move($inputPath, $outputPath, $inputName = 'original.*', $outputName = 'original', $unlink = false)
	{
		//find file in input path
		$inputPath = rtrim($inputPath, DS);
		$outputPath = rtrim($outputPath, DS);
		if (empty($inputPath) || empty($outputPath)) {
			throw new Exception('Input or output path could not be empty');
		}
		if (!file_exists($inputPath)) {
			throw new Exception('Input path does not exist(' . $inputPath . ')');
		}
		if (!file_exists($outputPath)) {
			throw new Exception('Output path does not exist(' . $outputPath . ')');
		}
		if (!is_string($inputName) || !is_string($outputName)) {
			throw new Exception('Input or output name does not appear as string');
		}
		if (empty($inputName) || empty($outputName)) {
			throw new Exception('Input or output name could not be empty');
		}
		$file = current(glob($inputPath . DS . $inputName));
        $parts = pathinfo($file);
        try {
        	@unlink($outputPath . DS . $outputName . '.' . $parts['extension']);
        	copy($file, $outputPath . DS . $outputName . '.' . $parts['extension']);
        } catch (Exception $e) {
        	throw $e;
        }
        if ($unlink) {
        	self::recursiveDelete($inputPath);
        }
        return $outputName . '.' . $parts['extension'];
	}

	/**
	 * recursive delete
	 *
	 * @param string $str
	 */
	public static function recursiveDelete($str)
    {
        if (is_file($str)) {
            return @unlink($str);
        } elseif (is_dir($str)) {
            $scan = glob(rtrim($str, '/') . '/*');
            foreach ($scan as $index => $path) {
                self::recursiveDelete($path);
            }
            return @rmdir($str);
        }
    }
}