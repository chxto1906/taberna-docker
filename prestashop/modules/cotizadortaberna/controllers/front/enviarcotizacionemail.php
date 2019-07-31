<?php

require_once _PS_MODULE_DIR_ . 'cotizadortaberna/models/cargar_licores_para_escoger.php';

class cotizadortabernaenviarcotizacionemailModuleFrontController extends ModuleFrontController{
	public function initContent()
	{
		
		parent::initContent();
		$email = Tools::getValue('email');
		$base = Tools::getValue('base');
		$data = array('{base}' => $base, '{nombre}' => 'Henry');
		$para = "Cliente Taberna";

		if ($base) {
			if (Validate::isEmail($email) )
			{
				$hoy = date("m_d_Y");
				$file_name = "cotizacion_taberna_".$hoy.".jpg";
				$file = $this->base64_to_jpeg($base,"upload/".$file_name);
				$attach=array();
				$content = file_get_contents(_PS_ROOT_DIR_.'/upload/'.$file_name);
				$attach['content'] = $content;
				$attach['name'] =$file_name;
				$attach['mime'] = 'image/png';

				if (Mail::Send(1,'cotizacion','Cotización',$data,$email,$para,null,null,$attach,null, _PS_MAIL_DIR_, false, null)) {
					$jsondata['resultado'] = '<h5 style="color: green;">Su cotización fue enviada exitosamente.</h5>';
					unlink(_PS_ROOT_DIR_.'/upload/'.$file_name);
				}else{
					$jsondata['resultado'] = '<h5 style="color: red;">Ha ocurrido un problema, su cotización no pudo ser enviada.</h5>';
				}
			}
			else
				$jsondata['resultado'] = '<h5 style="color: red;">Se ha intentado enviar la cotización pero resulta un email inválido.</h5>';
		} else {
			$jsondata['resultado'] = '<h5 style="color: red;">Ha ocurrido un problema, su cotización no pudo ser enviada.</h5>';
		}

		echo json_encode($jsondata);

		exit;
	}


	public function base64_to_jpeg($base64_string, $output_file) {
	    // open the output file for writing
	    $ifp = fopen( $output_file, 'wb' ); 
	    // split the string on commas
	    // $data[ 0 ] == "data:image/png;base64"
	    // $data[ 1 ] == <actual base64 string>
	    $data = explode( ',', $base64_string );
	    // we could add validation here with ensuring count( $data ) > 1
	    fwrite( $ifp, base64_decode( $data[1] ) );
	    // clean up the file resource
	    fclose( $ifp ); 
	    return $output_file; 
	}

}
?>