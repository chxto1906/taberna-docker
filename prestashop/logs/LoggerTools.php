<?php


class LoggerTools extends Tools {

	 //0 == nivel de debug. Sin esto logDebug() no funciona.
	
	public $logger = null;

	function __construct() {
		$this->logger = new FileLogger(0);
		$this->logger->setFilename(_PS_ROOT_DIR_."/logs/debug.log");
    }

    public function add($log) {
    	
	    $this->logger->logDebug($log);
    }

	

}