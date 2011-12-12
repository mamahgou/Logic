<?php
abstract class Logic_Image_Resize_Abstract
{
    protected $_inputFilename;

    protected $_outputFilename;

    protected $_inputType;

    protected $_inputImage;

    protected $_outputImage;

    protected $_backgroundColor = '#FFFFFF';

    protected $_inputWidth;

    protected $_inputHeight;

    protected $_outputWidth;

    protected $_outputHeight;

	/**
     * constructor
     *
     * @param string $inputFilename
     */
    public function __construct($inputFilename = null)
    {
        ini_set('memory_limit', '32M');

        if (!is_null($inputFilename)) {
            $this->setInputFilename($inputFilename);
        }
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
        return $this;
    }

    /**
     * get input file name
     *
     * @return string
     */
    public function getInputFilename()
    {
        if (!$this->_inputFilename) {
            throw new Exception('Please set the input filename first');
        }
        return $this->_inputFilename;
    }

	/**
     * set output file name
     *
     * @param string $output
     * @return Logic_Image_Resize
     */
    public function setOutputFilename($output)
    {
        if (file_exists($output)) {
            unlink($output);
        }
        $this->_outputFilename = $output;
        return $this;
    }

    /**
     * get output file name
     *
     * @return string
     */
    public function getOutputFilename()
    {
        if (!$this->_outputFilename) {
            throw new Exception('Please set the output filename first');
        }
        return $this->_outputFilename;
    }

	/**
     * set backgroun color
     *
     * @param mixed $color
     * @return Logic_Image_Resize
     */
    public function setBackgroundColor($color)
    {
        $this->_backgroundColor = $color;
        return $this;
    }

    /**
     * get background color
     *
     * @return mixed
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

    abstract public function resize();
}