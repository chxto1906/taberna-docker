<?php
        
class Response {

    public $log = null;

    public function json_response($message = null, $code = 200)
    {
        // clear the old headers
        header_remove();
        // set the actual code
        http_response_code($code);
        // set the header to make sure cache is forced
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        // treat this as json
        header('Content-Type: application/json');
        $status = array(
            200 => '200 OK',
            400 => '400 Bad Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
        );
        // ok, validation error, or failure
        //header('Status: '.$status[$code]);
        // return the encoded json
        if (empty($message)) {
            http_response_code(204);
            exit;
        }


        return json_encode($message);
    }

}