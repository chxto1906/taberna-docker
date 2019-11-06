<?php
        
class Consultas {

    public $log = null;

    public function list($sql, $limit = null) {
        //15 payment payphone

        $cadena = $sql;

        if ($limit) {
            $cadena = $cadena . " LIMIT " . $limit;
        }

        $shops = Db::getInstance()->executeS($cadena) ;

        return $shops;
    }


}

