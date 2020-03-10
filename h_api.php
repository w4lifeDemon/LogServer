<?php
    /*
    CR API :
    https://s[SERVER]-[COUNTRY].ogame.gameforge.com/api/v1/combat/report?api_key=[DEV_API]&cr_id=[API_KEY]

    Espi API :
    https://s[SERVER]-[COUNTRY].ogame.gameforge.com/api/v1/spy/report?api_key=[DEV_API]&sr_id=[API_KEY]

    Harverst API :
    https://s[SERVER]-[COUNTRY].ogame.gameforge.com/api/v1/recycle/report?api_key=[DEV_API]&rr_id=[API_KEY]

    Missile API :
    https://s[SERVER]-[COUNTRY].ogame.gameforge.com/api/v1/missile/report?api_key=[DEV_API]&mr_id=[API_KEY]
    */

    function apiBuffer ($varUrlBuffer) {
        if (cFiles::IsFileBuffer($varUrlBuffer)) {
            return unserialize(file_get_contents($varUrlBuffer));
        } else {
            return false;
        }
        return false;
    }
    function apiCR ($strApi, $strSave) {
        $strApi = trim($strApi);
        $varUrlBuffer = "./cache/cr_id/" . $strApi;
        $varResult = apiBuffer ($varUrlBuffer);
        if ($varResult) return $varResult;
        else {
            $data_array = explode("-", $strApi);
            $strDomain = $data_array[1];
            $strServer = $data_array[2];
            $strKeyApi = $data_array[3];
            $url = "https://s" . $strServer. "-" . $strDomain. ".ogame.gameforge.com/api/v1/combat/report?api_key=" . OGAME_API . "&cr_id=" . $strKeyApi. "";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $result = json_decode(curl_exec($ch));
            curl_close($ch);

            if(!isset($result->RESULT_CODE) || $result->RESULT_CODE != 1000 || $result == NULL) {
                return false;
            } else {
                if ($strSave) {
                    $varBuffer = serialize($result->RESULT_DATA);
                    $varFp = fopen($varUrlBuffer, 'w');
                    fwrite($varFp, $varBuffer);
                    fclose($varFp);
                }
                return $result->RESULT_DATA;
            }
        }
    }

    function apiRR ($strApi, $strSave) {
        $strApi = trim($strApi);
        $varUrlBuffer = "./cache/rr_id/" . $strApi;
        $varResult = apiBuffer ($varUrlBuffer);
        if ($varResult) return $varResult;
        else {
            $data_array = explode("-", $strApi);
            $strDomain = $data_array[1];
            $strServer = $data_array[2];
            $strKeyApi = $data_array[3];
            $url = "https://s" . $strServer. "-" . $strDomain. ".ogame.gameforge.com/api/v1/recycle/report?api_key=" . OGAME_API . "&rr_id=" . $strKeyApi. "";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $result = json_decode(curl_exec($ch));
            curl_close($ch);

            if(!isset($result->RESULT_CODE) || $result->RESULT_CODE != 1000 || $result == NULL) {
                return false;
            } else {
                if ($strSave) {
                    $varBuffer = serialize($result->RESULT_DATA);
                    $varFp = fopen($varUrlBuffer, 'w');
                    fwrite($varFp, $varBuffer);
                    fclose($varFp);
                }
                return $result->RESULT_DATA;
            }
        }
    }

    function apiSR ($strApi, $strSave) {
        $strApi = trim($strApi);
        $varUrlBuffer = "./cache/sr_id/" . $strApi;
        $varResult = apiBuffer ($varUrlBuffer);
        if ($varResult) return $varResult;
        else {
            $data_array = explode("-", $strApi);
            $strDomain = $data_array[1];
            $strServer = $data_array[2];
            $strKeyApi = $data_array[3];
            $url = "https://s" . $strServer. "-" . $strDomain. ".ogame.gameforge.com/api/v1/spy/report?api_key=" . OGAME_API . "&sr_id=" . $strKeyApi. "";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $result = json_decode(curl_exec($ch));
            curl_close($ch);

            if(!isset($result->RESULT_CODE) || $result->RESULT_CODE != 1000 || $result == NULL) {
                return false;
            } else {
                if ($strSave) {
                    $varBuffer = serialize($result->RESULT_DATA);
                    $varFp = fopen($varUrlBuffer, 'w');
                    fwrite($varFp, $varBuffer);
                    fclose($varFp);
                }
                return $result->RESULT_DATA;
            }
        }
    }

?>