<?php

class Geves_Filter_XmlTrim implements Zend_Filter_Interface
{
    /**
     * XML reader
     * 
     * @var XMLReader
     */
    protected $_reader = null;
    
    /**
     * XML Writer
     * 
     * @var XMLWriter
     */
    protected $_writer = null;
    
    protected $_maxBytes = 0;
    
    public function __construct($maxBytes)
    {
        $this->_maxBytes = (int) $maxBytes;
    }
    
    public function filter($value)
    {
        $this->_initReaderWriter();
        $this->_reader->XML($value);
        
        $this->_doXmlTrim();
        
        $this->_writer->endDocument();

        return $this->_writer->outputMemory(); 
    }
    
    protected function _initReaderWriter()
    {
        $this->_reader = new XMLReader();
        $this->_writer = new XMLWriter();
        $this->_writer->openMemory();
    }
    
    protected function _doXmlTrim()
    {
        $reader = $this->_reader; /* @var $reader XMLReader */
        $writer = $this->_writer; /* @var $writer XMLWriter */
        
        while (($byteCounter = strlen($writer->flush(false))) < $this->_maxBytes) {
            if (! $reader->read()) break;

            if ($reader->nodeType == XMLReader::ELEMENT) { 
                // Attempt to write entire XML subtree as is
                $xml = $reader->readOuterXml();
                $xmlLen = strlen($xml);
                if ($xmlLen + $byteCounter <= $this->_maxBytes) { 
                    $writer->writeRaw($xml);
                    $reader->next();
                    
                // Otherwise, chop tree :)
                } else {
                    $this->_writeCurrentElement();
                    $this->_doXmlTrim();
                }
            } 

            // If subtree is in fact a text node, trim it
            elseif ($reader->nodeType == XMLReader::TEXT) {
                $canRead = $this->_maxBytes - $byteCounter; 
                $str = substr($reader->value, 0, $canRead);
                $writer->text($str);
                break;
            }
        } 
    }
    
    protected function _writeCurrentElement()
    {
        $reader = $this->_reader; /* @var $reader XMLReader */
        $writer = $this->_writer; /* @var $writer XMLWriter */
        
        if ($reader->nodeType != XMLReader::ELEMENT) {
            throw new ErrorException("Expecting to be on an element node, nodeType is $reader->nodeType");
        }
        
        // Write element and all it's attributes
        $writer->startElement($reader->name);
        while ($reader->moveToNextAttribute()) {
            if ($reader->nodeType == XMLReader::ATTRIBUTE) { 
                $writer->writeAttribute($reader->name, $reader->value);
            } else {
                break;
            }
        }
    }
}
