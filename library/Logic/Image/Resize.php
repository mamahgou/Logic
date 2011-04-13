<?php

class Logic_Image_Resize
{
    protected $_options;

    protected $_sourceFilename;

    protected $_outputFilename;

    protected $_imageType;

    protected $_imageSource;

    protected $_imageOutput;

    protected $_backgroundColor = array(255, 255, 255);

    protected $_sourceWidth;

    protected $_sourceHeight;

    protected $_outputWidth;

    protected $_outputHeight;

    /**
     * constructor
     */
    public function __construct()
    {
        ini_set('memory_limit', '100M');
    }

    /**
     * set input image file
     *
     * @param string $filename
     * @return Logic_Image_Resize
     */
    public function setFile($sourceFilename, $outputFilename = null)
    {
        if (!file_exists($sourceFilename)) {
            throw new Exception('Image File :' . $sourceFilename . ' doesnt exist');
        }
        $this->_sourceFilename = $sourceFilename;
        $this->_outputFilename = $outputFilename;
        if (null === $outputFilename) {
            $this->_outputFilename = $sourceFilename;
        }
        //output always be jpg
        $parts = pathinfo($this->_outputFilename);
        $this->_outputFilename = $parts['dirname'] . DS . $parts['filename'] . '.jpg';
        $this->getImageType();
        return $this;
    }

    /**
     * get image MIME type
     *
     * @return string
     */
    public function getImageType()
    {
        try{
            list($this->_sourceWidth, $this->_sourceHeight, $this->_imageType) = getimagesize($this->_sourceFilename);
        } catch (Exception $e) {
            throw $e;
        }
        return $this->_imageType;
    }

    /**
     * create image resource
     *
     * @return resource
     */
    protected function _createImage()
    {
    	switch ($this->_imageType) {
    		case 1: //gif
                $this->_imageSource = @imagecreatefromgif($this->_sourceFilename);
                break;
            case 2: //jpeg
                $this->_imageSource = @imagecreatefromjpeg($this->_sourceFilename);
                break;
            case 3: //png
                $this->_imageSource = @imagecreatefrompng($this->_sourceFilename);
                break;
            default:
                throw new Exception('Unknown image type');
        }
        return $this->_imageSource;
    }

    /**
     * get image size
     *
     * @return array
     */
    public function getSize()
    {
        try{
            list($this->_sourceWidth, $this->_sourceHeight, $this->_imageType) = getimagesize($this->_sourceFilename);
        } catch (Exception $e) {
            throw $e;
        }
        return array('width' => $this->_sourceWidth, 'height' => $this->_sourceHeight);
    }

    /**
     * set backgroun color
     *
     * @param array $color
     * @return Logic_Image_Resize
     */
    public function setBackgroundColor(array $color)
    {
        $this->_backgroundColor = $color;
        return $this;
    }

    /**
     * get background color
     *
     * @return array
     */
    public function getBackgroundColor()
    {
        return $this->_backgroundColor;
    }

    /**
     * set output size
     *
     * @param int $width
     * @param int $height
     * @return Logic_Image_Resize
     */
    public function setOutputSize($width, $height)
    {
    	$width = (int) $width;
    	$height = (int) $height;
    	if (empty($width) || empty($height)) {
    		throw new Exception('Output width or height error');
    	}
    	$this->_outputWidth = $width;
    	$this->_outputHeight = $height;
    	return $this;
    }

    /**
     * resize image
     */
    public function resize()
    {
        $ratio = ($this->_outputWidth / $this->_sourceWidth < $this->_outputHeight / $this->_sourceHeight)
               ? $this->_outputWidth / $this->_sourceWidth
               : $this->_outputHeight / $this->_sourceHeight;

        if ($ratio <= 1) {
            $width = $this->_sourceWidth * $ratio;
            $height = $this->_sourceHeight * $ratio;
        } else {
            $width = $this->_sourceWidth;
            $height = $this->_sourceHeight;
        }

        //get source image resource
        $this->_createImage();

        //initial output image resource
        $this->_imageOutput = imagecreatetruecolor($this->_outputWidth, $this->_outputHeight);

        //fill withe background color
        $backgroundColor = imagecolorallocate(
            $this->_imageOutput,
            $this->_backgroundColor[0],
            $this->_backgroundColor[1],
            $this->_backgroundColor[2]
        );
        imagefilledrectangle($this->_imageOutput, 0, 0, $this->_outputWidth, $this->_outputHeight, $backgroundColor);

        //position
        $x = ($this->_outputWidth - $width) / 2;
        $y = ($this->_outputHeight - $height) / 2;

        //resample
        imagecopyresampled($this->_imageOutput, $this->_imageSource, $x, $y, 0, 0,
            $width, $height, $this->_sourceWidth, $this->_sourceHeight);

        //save image
        imagejpeg($this->_imageOutput, $this->_outputFilename, 100);
    }
}