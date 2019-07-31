<?php
	

	require_once 'models/cargar_licores_para_escoger.php';

	class Cotizadortaberna extends Module
	{
		private $_html = '';
		public function __construct()
		{
			$this->name = 'cotizadortaberna';
			$this->tab = 'lataberna';
			$this->version = 1.0;
			$this->author = 'Henry Campoverde';
			$this->need_instance = 0;
			$this->secure_key = Tools::encrypt($this->name);
			
			parent::__construct();
			
			$this->displayName = $this->l('Cotizador, productos recomendados y de interés de la Taberna Liquor Store.');
			$this->description = $this->l('Cotizador de licores. Configurable para agregar emails a los que se les enviará la cotización que los clientes hagan.');
			$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		}
			public function install()
			{
				return parent::install() && $this->registerHook('header') && $this->registerHook('displayTopColumn') && $this->_installDb();
			}
			public function uninstall()
			{
				if (!parent::uninstall())
					Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'cotizaciones_mails`');
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


			private function _installDb() {
		        return Db::getInstance()->Execute('
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'cotizaciones_mails` (
                `id` INT(11) unsigned NOT NULL auto_increment,
                `email` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`))');
		    }


			public function getContent(){
				$this->displayForm();
				return $this->_html;
			}

			public function displayForm(){
				$listado_mails = $this->getListEmails();
				$this->_html .= '<style>
									.emails{
										width: 300px;
										font-size: 16px;
										height: 30px;
									}
									.boton_guardar{
										width: 200px;
										font-size: 20px;
										background-color: #490;
									}
									.agregar_nuevo{

									}
									.guardado_estado{
										text-align: center;
										background-color: #eee;
										font-size: 14px;
									}
								</style>';
				//$this->_html .= '<script type="text/javascript" src="'.__PS_BASE_URI__.'js/jquery/plugins/fancybox/jquery.fancybox.js"></script>';

				$this->_html .= '<div class="guardado_estado">
								<div id="estado_ok" style="display: none;">
									<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/images/ok.png" />
								</div>
								<div id="estado_bad" style="display: none;">
									<img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/images/bad.png" />
								</div>
								<div id="legend_estado" style="visibility: hidden;"></div>
								</div>
								<hr>
								<form id="form_mails_cotizaciones" method="post">
									<div style="display:block; margin:auto; overflow:hidden; ">
					                    <div style="clear:both; display:block; ">
					                        <fieldset>
					            				<legend><h3>'.$this->l('Emails que recibirán cotizaciones de clientes').'</h3></legend>
					            				<div>
					            					<table id="tabla_emails">';
					            	foreach ($listado_mails as $mail)
									{
										$this->_html .= '<tr id="registro_mail_'.$mail['id_email'].'"><td><input class="emails" id="email_'.$mail['id_email'].'" type="text" value="'.$mail['email'].'"></td>
														<td><a style="cursor: pointer;" class="eliminar_mails" id_email="email_'.$mail['id_email'].'" title="Click para eliminar email">X</a></td></tr>';
									}

				$this->_html .= '</table><br>';
				$this->_html .= '<div style="display: none;">
									<div id="contenedor_nuevo_mail">
										<label>Ingrese nuevo email:</label>
										<input type="email" placeholder="Email válido" id="valor_nuevo_mail" style="width: 200px;"/>
										<br>
										<br>
										<input style="margin-left: 84%;" type="button" value="Agregar" id="boton_agregar_nuevo_mail">
									</div>
								</div>';
	            $this->_html .= '<input class="boton_guardar" type="submit" value="GUARDAR" />
	            				<a href="#contenedor_nuevo_mail" class="agregar_nuevo nuevo_mail">+ Agregar nuevo</a>
	            				</div>
	            			</fieldset>
	            		</div>
	            	</div>
	            </form>';
	            $this->_html .= '<script type="text/javascript">

	            					$(".eliminar_mails").live("click",function(){
	            						var idOriginal = $(this).attr("id_email");
	            						var id = idOriginal.replace("email_","");
	            						var datos = {id_email_eliminar: id};
	            						$.ajax({
						                  type: "GET",
						                  url: "../modules/'.$this->name.'/actualizar_emails_cotizaciones.php?secure_key='.$this->secure_key.'",
						                  dataType: "json",
						                  data: datos,
						                  beforeSend: function(){
						                  	
						                  },
						                  success: function(data) { 
						                   	var respuesta = data.respuesta;
						                   	
						                   	if (respuesta == 1)
						                   	{
						                   		$("#registro_mail_"+id).fadeOut(500);
						                   	}
						                   	else
						                   	{
						                   		alert("No se pudo eliminar email debido a un error");
						                   	}
						                  }
						                });
	            					});
	            					$("#boton_agregar_nuevo_mail").live("click",function(){
	            						var mail_new = $("#valor_nuevo_mail").val();
	            						var datos = {mail_new: mail_new};
	            						$.ajax({
						                  type: "GET",
						                  url: "../modules/'.$this->name.'/actualizar_emails_cotizaciones.php?secure_key='.$this->secure_key.'",
						                  dataType: "json",
						                  data: datos,
						                  beforeSend: function(){
						                  	
						                  },
						                  success: function(data) { 
						                   	var respuesta = data.respuesta;
						                   	var id = data.id;
						                   	if (respuesta == 1)
						                   	{
						                   		$("#tabla_emails").append("<tr id=\'registro_mail_"+id+"\'><td><input class=\'emails\' id=\'email_"+id+"\' type=\'text\' value=\'"+mail_new+"\'></td><td><a style=\'cursor: pointer;\' class=\'eliminar_mails\' id_email=\'email_"+id+"\' title=\'Click para eliminar email\'>X</a></td></tr>");
						                   		$.fancybox.close();
						                   	}
						                   	else
						                   	{
						                   		alert("Error al agregar, intente de nuevo.");
						                   	}
						                  }
						                });
	            					});
	            					$("#form_mails_cotizaciones").live("submit",function(e){
	            						e.preventDefault();
	            						var datos = {registros:[]};
	            						$(".emails").each(function() {
	            							var idOriginal = this.id;
	            							var id = idOriginal.replace("email_","");
	            							var email = $("#"+idOriginal).val();
	            							datos.registros.push(
											    {id: id, email: email}
											);
										});
										$.ajax({
						                  type: "GET",
						                  url: "../modules/'.$this->name.'/actualizar_emails_cotizaciones.php?secure_key='.$this->secure_key.'",
						                  dataType: "json",
						                  data: datos,
						                  beforeSend: function(){
						                  	$("#estado_ok").hide();
						                    $("#estado_bad").hide();
						                  	$("#legend_estado").html("");
						                  },
						                  success: function(data) { 
						                    var estado = data.respuesta;
						                    var estado_img = data.estado;
						                    $("#legend_estado").html(estado);
						                    $("#legend_estado").css("visibility","visible");
						                    if (estado_img == 1)
						                    	$("#estado_ok").fadeIn(500);
						                    else
						                    	$("#estado_bad").fadeIn(500);
						                  }
						                });
	            					});
	            				</script>';

	            				 $this->_html .= '<script type="text/javascript">
		            				 				$(document).ready(function() {
												        $(".nuevo_mail").fancybox({
												            "transitionIn"     : "elastic",
												            "transitionOut"     : "elastic"
												    	});
													});
												</script>';
	            
			}
			public function getListEmails()
			{
				
				return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
					SELECT cm.`id` as id_email,cm.`email` as email
					FROM '._DB_PREFIX_.'cotizaciones_mails cm');
			}

			
			/*public function hookHome($params)
			{
				$this->context->controller->registerJavascript('module-compare1', 'modules/'.$this->name.'/views/js/jquery.tiny.min.js', ['position' => 'bottom', 'priority' => 151]);
				$this->context->controller->registerJavascript('module-compare2', 'modules/'.$this->name.'/views/js/front.js', ['position' => 'bottom', 'priority' => 150]);

				$this->context->controller->addCSS(($this->_path).'css/tinycarousel.css','all');
				$this->context->controller->addCSS(($this->_path).'css/style.css','all');
			}*/
			public function hookHeader($params)
			{
				$this->context->controller->registerJavascript('module-compare11', 'modules/'.$this->name.'/views/js/canvas.min.js', ['position' => 'bottom', 'priority' => 150]);
				$this->context->controller->registerJavascript('module-compare1', 'modules/'.$this->name.'/views/js/jquery.tiny.min.js', ['position' => 'bottom', 'priority' => 151]);
				$this->context->controller->registerJavascript('module-compare2', 'modules/'.$this->name.'/views/js/jsPdf.min.js', ['position' => 'bottom', 'priority' => 152]);
				$this->context->controller->registerJavascript('module-compare3', 'modules/'.$this->name.'/views/js/front.js', ['position' => 'bottom', 'priority' => 153]);

				$this->context->controller->addCSS(($this->_path).'css/tinycarousel.css','all');
				$this->context->controller->addCSS(($this->_path).'css/style.css','all');
				
			}
			public function buscar_licores($name_category)
			{
				//echo "category: ".$name_category;
				$this->smarty->assign(array('cargar_licores_para_escoger'=> Cargar_licores_para_escoger::find($name_category)));	
				return $this->display(__FILE__, 'cargar_licores_para_escoger.tpl');	
			}
	}
?>