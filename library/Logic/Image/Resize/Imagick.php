<?php

class Logic_Image_Resize_Imagick extends Logic_Image_Resize_Abstract
{
	/**
     * create image resource
     *
     * @return resource
     */
    protected function _createImage()
    {
    	if (!$this->_inputImage) {
            try {
        	    $this->_inputImage = new Imagick($this->getInputFilename());
        	} catch (Exception $e) {
        	    throw $e;
        	}
    	}
        return $this->_inputImage;
    }

    /**
     * get width, height
     *
     * @return array
     */
    public function getImageSize()
    {
        if (!$this->_inputImage) {
            $this->_createImage();
        }

        return $this->_inputImage->getimagegeometry();
    }

    /**
     * get image format
     *
     * @return string
     */
    public function getImageFormat()
    {
        if (!$this->_inputImage) {
            $this->_createImage();
        }

        return $this->_inputImage->getimageformat();
    }

    /**
     * resize image
     *
     * @return void
     */
    public function resize()
    {
        //get source image resource
        $this->_createImage();

        //resize
        $this->_inputImage->thumbnailImage($this->_outputWidth, $this->_outputHeight, true);

        //Create a canvas with the desired color
        $canvas = new Imagick();
        $canvas->newImage(
            $this->_outputWidth,
            $this->_outputHeight,
            new ImagickPixel($this->getBackgroundColor()),
            $this->getImageFormat()
        );

        //The overlay x and y coordinates
        $geometry = $this->getImageSize();
        $x = ($this->_outputWidth - $geometry['width']) / 2;
        $y = ($this->_outputHeight - $geometry['height']) / 2;

        // Composite on the canvas
        $canvas->compositeImage($this->_inputImage, imagick::COMPOSITE_OVER, $x, $y);
        $canvas->writeImage($this->getOutputFilename());
    }
}