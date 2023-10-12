<?PHP


namespace Vexsoluciones\Facturacionelectronica\Vexfecore;


class callApi{

    /*
        Implementación para llamar conectarse al API
        @Return: Devuelve los datos en String
    */
    public static function getURL($method, $url, $data = false, $json = true)
    {
        $curl = curl_init($url);

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }


        if($json){
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }


}


