<?php

class Logic_Image_Resize_Gd extends Logic_Image_Resize_Abstract
{
	/**
     * create image resource
     *
     * @return resource
     */
    protected function _createImage()
    {
    	switch ($this->_inputType) {
    		case 1: //gif
                $this->_inputImage = @imagecreatefromgif($this->getInputFilename());
                break;
            case 2: //jpeg
                $this->_inputImage = @imagecreatefromjpeg($this->getInputFilename());
                break;
            case 3: //png
                $this->_inputImage = @imagecreatefrompng($this->getInputFilename());
                break;
            default:
                throw new Exception('Unknown image type');
        }
        return $this->_inputImage;
    }

	/**
     * set input file name
     *
     * @param string $input
     * @return Logic_Image_Resize
     */
    public function setInputFilename($input)
    {
        if (!file_exists($input)) {
            throw new Exception('Image File :' . $input . ' doesnt exist');
        }

        $this->_inputFilename = $input;

        $this->_getImageSize($input);

        return $this;
    }

    /**
     * determine the image width, height and format
     *
     * @param string $filename
     * @throws Exception
     * @return void
     */
    private function _getImageSize($filename)
    {
        try {
            list($this->_inputWidth, $this->_inputHeight, $this->_inputType) = getimagesize($this->getInputFilename());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * get input width, height
     *
     * @return array
     */
    public function getImageSize()
    {
        $this->_getImageSize($this->getInputFilename());

        return array('width' => $this->_inputWidth, 'height' => $this->_inputHeight);
    }

    /**
     * get image format
     *
     * @return string
     */
    public function getImageFormat()
    {
        $this->_getImageSize($this->getInputFilename());

        return $this->_inputType;
    }

    /**
     * resize image
     */
    public function resize()
    {
        $ratio = ($this->_outputWidth / $this->_inputWidth < $this->_outputHeight / $this->_inputHeight)
               ? $this->_outputWidth / $this->_inputWidth
               : $this->_outputHeight / $this->_inputHeight;

        if ($ratio <= 1) {
            $width = $this->_inputWidth * $ratio;
            $height = $this->_inputHeight * $ratio;
        } else {
            $width = $this->_inputWidth;
            $height = $this->_inputHeight;
        }

        //get source image resource
        $this->_createImage();

        //initial output image resource
        $this->_imageOutput = imagecreatetruecolor($this->_outputWidth, $this->_outputHeight);

        //fill withe background color
        $bgColor = Logic_Image_ColorConvert::rgb($this->_backgroundColor);
        $backgroundColor = imagecolorallocate($this->_imageOutput, $bgColor[0], $bgColor[1], $bgColor[2]);
        imagefilledrectangle($this->_imageOutput, 0, 0, $this->_outputWidth, $this->_outputHeight, $backgroundColor);

        //position
        $x = ($this->_outputWidth - $width) / 2;
        $y = ($this->_outputHeight - $height) / 2;

        //resample
        imagecopyresampled($this->_imageOutput, $this->_inputImage, $x, $y, 0, 0,
            $width, $height, $this->_inputWidth, $this->_inputHeight);

        //save image
        imagejpeg($this->_imageOutput, $this->_outputFilename, 100);
    }
}