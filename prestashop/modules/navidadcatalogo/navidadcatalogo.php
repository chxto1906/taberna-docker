<?php


	class Navidadcatalogo extends Module
	{
		private $_html = '';
		public function __construct()
		{
			$this->name = 'navidadcatalogo';
			$this->tab = 'lataberna';
			$this->version = 1.0;
			$this->author = 'Henry Campoverde';
			$this->need_instance = 0;
			$this->secure_key = Tools::encrypt($this->name);
			
			parent::__construct();
			
			$this->displayName = $this->l('Catálogo promocional navideño.');
			$this->description = $this->l('Catálogo promocional por navidad.');
			$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		}
			public function install()
			{
				return parent::install() && $this->registerHook('header') && $this->registerHook('displayTopColumn');
			}
			public function uninstall()
			{
				/*if (!parent::uninstall())
					Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'cotizaciones_mails`');*/
				parent::uninstall();	
			}

			/*public function hookHome($params)
            {
                if ($this->context->customer->isLogged())
					$email = $this->context->customer->email;
				else
					$email = "none";

                    $this->smarty->assign(array('email'=> $email));
                    return $this->display(__FILE__,'cotizadortaberna.tpl');
            }*/


			public function getContent(){
				$this->displayForm();
				return $this->_html;
			}

			public function displayForm(){
				
				$this->_html .= '<div></div>';
	            
			}

			
			public function hookHeader($params)
			{
				$this->context->controller->registerJavascript('module-compare2', 'modules/'.$this->name.'/views/js/turn.min.js', ['position' => 'bottom', 'priority' => 154]);
				$this->context->controller->registerJavascript('module-compare3', 'modules/'.$this->name.'/views/js/front.js', ['position' => 'bottom', 'priority' => 155]);

				$this->context->controller->addCSS(($this->_path).'css/style.css','all');
				
			}

	}
?>