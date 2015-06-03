<?php

class MainModel
{

    /**
     * host
     */
    const HOST = 'http://www.4jawaly.net/';

    /**
     * get settings from file
     * @return boolean | object
     */
    function getSettings()
    {
        $config = json_decode(file_get_contents(__DIR__ . "/settings.txt"));
        if ($config == null) {
            return false;
        }
        return $config;
    }

    /**
     * get special folder
     * @return boolean | string
     */
    function getSpecialFolder()
    {
        $sets = $this->getSettings();
        if ($sets) {
            if (isset($sets->sp_folder)) {
                return $sets->sp_folder;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * set settings to file
     * @param array $settings
     * @return type
     */
    function setSettings($settings)
    {
        return file_put_contents(__DIR__ . "/settings.txt", json_encode($settings));
    }

    /**
     * add log to logs file
     * @param string $s
     */
    function log($s)
    {
        $handle = fopen(__DIR__ . "/log.txt", "a");
        fwrite($handle, $s . "\r\n");
        fclose($handle);
    }

    /**
     * get language strings
     * @param chars $pre
     * @return type
     */
    function getStrings($pre)
    {
        include('languages.php');
        return (object) $lang[$pre];
    }

    /**
     * get Balance
     * @param type $username
     * @param type $password
     * @param type $return_type
     * @return type
     */
    function getBalance($username, $password)
    {
        $request = $this->getResponse(self::HOST . 'api/getbalance.php', array(
            'username' => $username,
            'password' => $password,
            'return' => 'json'
                ), 'json');
        if ($request->Code == 117) {
            return $request->currentuserpoints;
        } else {
            return false;
        }
    }

    /**
     * get user senders 
     * @param type $username
     * @param type $password
     * @return boolean
     */
    function getSenders($username, $password)
    {
        $sp_folder = $this->getSpecialFolder();
        if (!$sp_folder) {
            return false;
        }
        $request = $this->getResponse(self::HOST . $sp_folder . '/GetAllSenders.php', array(
            'username' => $username,
            'password' => $password,
            'return' => 'json'
                ), 'json');
        if ($request->Code == 117) {
            return $request->SenderArchive;
        } else {
            return false;
        }
    }

    /**
     * request new sender
     * @param type $username
     * @param type $password
     * @param type $sender
     * @return boolean
     */
    function requestSender($username, $password, $sender)
    {
        $sp_folder = $this->getSpecialFolder();
        if (!$sp_folder) {
            return false;
        }
        $request = $this->getResponse(self::HOST . $sp_folder . '/addSender.php', array(
            'username' => $username,
            'password' => $password,
            'Sendername' => $sender,
            'return' => 'json'
                ), 'json');
        return $request->Code;
    }

    /**
     * verify numeric sender
     * @param type $username
     * @param type $password
     * @param type $mobile
     * @param type $code
     * @return boolean
     */
    function verifyMobile($username, $password, $mobile, $code)
    {
        $sp_folder = $this->getSpecialFolder();
        if (!$sp_folder) {
            return false;
        }
        $request = $this->getResponse(self::HOST . $sp_folder . '/ActiveSendername.php', array(
            'username' => $username,
            'password' => $password,
            'sender_name' => $mobile,
            'Activecode' => $code,
            'return' => 'json'
                ), 'json');
        return $request->Code;
    }

    /**
     * get curl respond
     * @param type $url
     * @param type $params
     * @param type $return_type
     * @return \SimpleXMLElement
     */
    public function getResponse($url, $params, $return_type)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($return_type == 'json') {
            return json_decode($response);
        } elseif ($return_type == 'xml') {
            $xml = new SimpleXMLElement($response);
            return $xml;
        } else {
            return $response;
        }
    }

    /**
     * send short message
     * @param type $username
     * @param type $password
     * @param type $sender
     * @param type $message
     * @param type $numbers
     * @return type
     */
    function sendMessage($username, $password, $sender, $message, $numbers)
    {
        $UC = 'E';
        $Msg = $message;
//        if ($this->IsItUnicode($message)) {
//            $Msg = $this->ToUnicode($message);
//            $UC = 'U';
//        }
        $params = array(
            'username' => $username,
            'password' => $password,
            'numbers' => $numbers,
            'message' => ($Msg),
            'sender' => $sender,
            'unicode' => $UC,
            'Rmduplicated' => 1,
            'return' => 'json'
        );
        $request = $this->getResponse(self::HOST . 'api/sendsms.php', $params, 'json');
        return $request->Code;
    }

    /**
     * convert string to unicode
     * @param type $Text
     * @return string
     */
    private function ToUnicode($Text)
    {

        $Backslash = "\ ";
        $Backslash = trim($Backslash);

        $UniCode = Array
            (
            "¡" => "060C",
            "º" => "061B",
            "¿" => "061F",
            "Á" => "0621",
            "Â" => "0622",
            "Ã" => "0623",
            "Ä" => "0624",
            "Å" => "0625",
            "Æ" => "0626",
            "Ç" => "0627",
            "È" => "0628",
            "É" => "0629",
            "Ê" => "062A",
            "Ë" => "062B",
            "Ì" => "062C",
            "Í" => "062D",
            "Î" => "062E",
            "Ï" => "062F",
            "Ð" => "0630",
            "Ñ" => "0631",
            "Ò" => "0632",
            "Ó" => "0633",
            "Ô" => "0634",
            "Õ" => "0635",
            "Ö" => "0636",
            "Ø" => "0637",
            "Ù" => "0638",
            "Ú" => "0639",
            "Û" => "063A",
            "Ý" => "0641",
            "Þ" => "0642",
            "ß" => "0643",
            "á" => "0644",
            "ã" => "0645",
            "ä" => "0646",
            "å" => "0647",
            "æ" => "0648",
            "ì" => "0649",
            "í" => "064A",
            "Ü" => "0640",
            "ð" => "064B",
            "ñ" => "064C",
            "ò" => "064D",
            "ó" => "064E",
            "õ" => "064F",
            "ö" => "0650",
            "ø" => "0651",
            "ú" => "0652",
            "!" => "0021",
            '"' => "0022",
            "#" => "0023",
            "$" => "0024",
            "%" => "0025",
            "&" => "0026",
            "'" => "0027",
            "(" => "0028",
            ")" => "0029",
            "*" => "002A",
            "+" => "002B",
            "," => "002C",
            "-" => "002D",
            "." => "002E",
            "/" => "002F",
            "0" => "0030",
            "1" => "0031",
            "2" => "0032",
            "3" => "0033",
            "4" => "0034",
            "5" => "0035",
            "6" => "0036",
            "7" => "0037",
            "8" => "0038",
            "9" => "0039",
            ":" => "003A",
            ";" => "003B",
            "<" => "003C",
            "=" => "003D",
            ">" => "003E",
            "?" => "003F",
            "@" => "0040",
            "A" => "0041",
            "B" => "0042",
            "C" => "0043",
            "D" => "0044",
            "E" => "0045",
            "F" => "0046",
            "G" => "0047",
            "H" => "0048",
            "I" => "0049",
            "J" => "004A",
            "K" => "004B",
            "L" => "004C",
            "M" => "004D",
            "N" => "004E",
            "O" => "004F",
            "P" => "0050",
            "Q" => "0051",
            "R" => "0052",
            "S" => "0053",
            "T" => "0054",
            "U" => "0055",
            "V" => "0056",
            "W" => "0057",
            "X" => "0058",
            "Y" => "0059",
            "Z" => "005A",
            "[" => "005B",
            $Backslash => "005C",
            "]" => "005D",
            "^" => "005E",
            "_" => "005F",
            "`" => "0060",
            "a" => "0061",
            "b" => "0062",
            "c" => "0063",
            "d" => "0064",
            "e" => "0065",
            "f" => "0066",
            "g" => "0067",
            "h" => "0068",
            "i" => "0069",
            "j" => "006A",
            "k" => "006B",
            "l" => "006C",
            "m" => "006D",
            "n" => "006E",
            "o" => "006F",
            "p" => "0070",
            "q" => "0071",
            "r" => "0072",
            "s" => "0073",
            "t" => "0074",
            "u" => "0075",
            "v" => "0076",
            "w" => "0077",
            "x" => "0078",
            "y" => "0079",
            "z" => "007A",
            "{" => "007B",
            "|" => "007C",
            "}" => "007D",
            "~" => "007E",
            "©" => "00A9",
            "®" => "00AE",
            "÷" => "00F7",
            "×" => "00F7",
            "§" => "00A7",
            " " => "0020",
            "\n" => "000D",
            "\r" => "000A",
            "\t" => "0009",
            "é" => "00E9",
            "ç" => "00E7",
            "à" => "00E0",
            "ù" => "00F9",
            "µ" => "00B5",
            "è" => "00E8"
        );

        $Result = "";
        $StrLen = strlen($Text);
        for ($i = 0; $i < $StrLen; $i++) {

            $currect_char = substr($Text, $i, 1);

            if (array_key_exists($currect_char, $UniCode)) {
                $Result .= $UniCode[$currect_char];
            }
        }

        return $Result;
    }

    /**
     * check string if containing unicode characters
     * @param type $Text
     * @return boolean
     */
    public function IsItUnicode($Text)
    {

        $unicode = false;
        $str = "ÏÌÍÎåÚÛÝÞËÕÖØßãäÊÇáÈíÓÔÙÒæÉìáÇÑÄÁÆÅáÅÃáÃÂáÂ¡º¿ÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÝÞßáãäå'©®÷×§æìíÜðñòóõöøú";

        for ($i = 0; $i <= strlen($str); $i++) {
            $strResult = substr($str, $i, 1);

            for ($R = 0; $R <= strlen($Text); $R++) {
                $msgResult = substr($Text, $R, 1);

                if ($strResult == $msgResult && $strResult)
                    $unicode = true;
            }
        }

        return $unicode;
    }

    function vd($s)
    {
        echo "<pre>";
        exit(var_dump($s));
    }

}
