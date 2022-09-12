<?php

namespace App\Helpers;

class ScrapHelper
{
    public static function logData($title, $data)
    {
        $fp = fopen(config('constants.BASE_DIR') . "/storage/logs/scrap_log.txt", "a+") or die("Unable to open file!");
        fwrite($fp, "Log Time: " . date('Y-m-d H:i:s'));
        fwrite($fp, "\n------------------------------" . $title . "------------------------------\n");
        fwrite($fp, print_r($data, true));
        fwrite($fp, "\n------------------------------END------------------------------\n\n");
        fclose($fp);
    }

    public static function getContent($url, $header = false, $show_error = true)
    {
        sleep(rand(5, 10));

        $ch = curl_init();
        if (config('constants.IS_PROXY_ENABLE')) {
            curl_setopt($ch, CURLOPT_URL, 'https://api.proxycrawl.com/?token=Uqg2HJOtd4WrqAXiM6uhmQ&url=' . urlencode($url));
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_HEADER, $header);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_TIMEOUT, 25);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);

        if ($show_error && curl_errno($ch)) {
            $data = array(
                'url' => $url,
                'error' => curl_error($ch),
            );
            self::logData('CURL ERROR', $data);
        }

        if (empty($content)) {
            $data = array(
                'content' => $content,
                'url' => $url
            );
            self::logData('NO CONTENT', $data);
        }

        curl_close($ch);

        return $content;
    }

    public static function reformatContent($content)
    {
        $content = preg_replace("/&amp;/", "&", $content);
        $content = preg_replace("/&nbsp;/", " ", $content);
        $content = preg_replace("/&lt;/", "<", $content);
        $content = preg_replace("/&gt;/", ">", $content);
        $content = preg_replace("/\n/", " ", $content);
        $content = preg_replace("/\r/", " ", $content);
        return preg_replace("/\s+/", " ", $content);
    }

    public static function getSegment($content, $match1, $match2)
    {
        $segment = '';
        $pos1 = strpos($content, $match1);
        if ($pos1 !== false) {
            $pos2 = strpos($content, $match2, $pos1);
            $len = $pos2 - $pos1;
            $segment = substr($content, $pos1, $len);
        }
        return $segment;
    }

    public static function cleanText($text)
    {
        $text = trim($text);
        $text = preg_replace("/<br[^<>]*?>/i", "|", $text);
        $text = preg_replace("/\n/", " ", $text);
        $text = preg_replace("/\r/", " ", $text);
        $text = preg_replace("/\s+/", " ", $text);

        $text = preg_replace("/<script.*?>.*?<\/script>/", "", $text);
        $i = 0;
        while ($i < 5) {
            $i++;
            $text = preg_replace("/<[^<>]*?>/", "", $text);
        }
        return preg_replace("/<.*?>/", "", $text);
    }

    public static function getOriginId($field)
    {
        $originId = 0;
        preg_match("/([\d]+)\s*.php/", $field, $matches);
        if (!empty($matches[1])) {
            $originId = trim($matches[1]);
        }
        return $originId;
    }

    public static function getKey($title)
    {
        return str_replace(' ', '_', strtolower($title));
    }

    public static function getFileExtension($url)
    {
        return strtolower(strrchr($url, '.'));
    }

    public static function cleanNumber($number)
    {
        $number = preg_replace("/[^0-9\.]/", "", $number);
        if (empty($number)) {
            return 0;
        }
        return $number;
    }

    public static function cleanScreenSize($screenSize)
    {
        $screenSize = preg_replace('/inch(.*)[^n]/i', '', $screenSize);
        $screenSize = str_replace('-', '', $screenSize);
        return intval($screenSize);
    }

    public static function cleanWeight($weight)
    {
        $weight = self::cleanFirstBracketContent($weight);
        $weight = preg_replace('/g(.*)[^n]/i', '', $weight);
        if (stripos($weight, '-') !== false) {
            $weight_parts = explode('-', $weight);
            if (count($weight_parts) > 1) {
                $weight = $weight_parts[1];
            }
        }
        $weight = str_replace('-', '', $weight);
        return floatval($weight);
    }

    public static function getStorage($internal)
    {
        $storageList = array();
        $internal = str_replace('DDR2', '', $internal);
        $internal = str_replace('DDR3', '', $internal);
        $internal = str_replace('ROM', '', $internal);
        $internal = self::cleanFirstBracketContent($internal);

        $orParts = explode(',', $internal);
        foreach ($orParts as $segment) {
            $segmentParts = explode(' ', trim($segment), 2);
            $storageList[] = $segmentParts[0];
        }

        usort($storageList, function ($a, $b) {
            if (strlen($a) > strlen($b)) {
                return 1;
            } else if (strlen($a) < strlen($b)) {
                return -1;
            } else {
                return 0;
            }
        });

        return array_unique($storageList);
    }

    public static function getRam($internal)
    {
        $internal = str_replace('DDR2', '', $internal);
        $internal = str_replace('DDR3', '', $internal);
        $internal = str_replace('ROM', '', $internal);
        $internal = str_replace('RAM', '', $internal);
        $internal = self::cleanFirstBracketContent($internal);

        $ramList = array();
        $orParts = explode(',', $internal);
        foreach ($orParts as $segment) {
            $segmentParts = explode(' ', trim($segment), 2);
            if (count($segmentParts) >= 2) {
                $ramList[] = $segmentParts[1];
            }
        }

        usort($ramList, function ($a, $b) {
            if (strlen($a) > strlen($b)) {
                return 1;
            } else if (strlen($a) < strlen($b)) {
                return -1;
            } else {
                return 0;
            }
        });

        return array_unique($ramList);
    }

    public static function cleanFirstBracketContent($string)
    {
        return preg_replace("/\(([^()]*+|(?R))*\)/", "", $string);
    }

    public static function getCameraResolutions($field)
    {
        // get camera
        $cameraResolutions = [];
        preg_match_all("/([\d\.]+)\s*MP/", $field, $matches);
        if (!empty($matches[0])) {
            $cameraResolutions = $matches[0];
        }
        return $cameraResolutions;
    }

    public static function getBatteryCapacity($field)
    {
        $field = str_replace(',', '', $field);

        $batteryCapacity = 0;
        preg_match("/([\d\.]+)\s*mAh/", $field, $matches);
        if (!empty($matches[1])) {
            $batteryCapacity = trim($matches[1]);
        }
        return $batteryCapacity;
    }

    public static function getPxDensity($field)
    {
        $pxDensity = 0;
        preg_match("/([\d\.]+)\s*ppi/", $field, $matches);
        if (!empty($matches[1])) {
            $pxDensity = trim($matches[1]);
        }
        return $pxDensity;
    }

    public static function getPxResolution($field)
    {
        $pxResolution = 0;
        $field = preg_replace('/pixels(.*)[^n]/i', '', $field);
        if (stripos($field, 'chars') === false) {
            $field = preg_replace('/char(.*)[^n]/i', '', $field);
            $field = preg_replace('/to(.*)[^n]/i', '', $field);
            $resolutions = array_map('trim', explode('x', $field));
            if (!empty($resolutions) && count($resolutions) == 2) {
                $pxResolution = max($resolutions);
            }
        }

        return $pxResolution;
    }

    public static function getHzRefreshRate($field)
    {
        $hzRefreshRate = 0;
        preg_match("/([\d\.]+)\s*Hz/", $field, $matches);
        if (!empty($matches[1])) {
            $hzRefreshRate = trim($matches[1]);
        }
        return $hzRefreshRate;
    }

    public static function getMhzProcessorSpeed($field)
    {
        $mhzProcessorSpeed = 0;
        preg_match_all("/([\d\.]+)\s*GHz/", $field, $matches);
        if (!empty($matches[1])) {
            $mhzProcessorSpeed = max($matches[1]) * 1000;
        } else {
            preg_match_all("/([\d\.]+)\s*MHz/", $field, $matches);
            if (!empty($matches[1])) {
                $mhzProcessorSpeed = max($matches[1]);
            }
        }
        return $mhzProcessorSpeed;
    }

    public static function cleanCardSlot($cardSlot)
    {
        $cardSlot = preg_replace('/GB(.*)[^n]/i', 'GB', $cardSlot);
        return trim($cardSlot);
    }

    public static function pr($msg)
    {
        echo '<pre>';
        print_r($msg);
        echo '</pre>';
    }

    public static function tr($res)
    {
        echo '<textarea cols = "130" rows = "350">';
        print_r($res);
        echo '</textarea>';
    }

    public static function upload($source, $folder, $fileName)
    {
        if (!is_dir($folder)) {
            mkdir($folder);
            chmod($folder, 0777);
        }

        if (is_writable($folder)) {
            $filePath = $folder . '/' . $fileName;

            if (@copy($source, $filePath) === false) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    public static function prepareDataSet($data, $allowAllUpdate = true)
    {
        $preparedData = array();
        if (empty($data)) {
            return $preparedData;
        }

        foreach ($data as $key => $value) {
            if ($key == 'size') {
                $preparedData['inch_size'] = self::cleanScreenSize($value);
            } else if ($key == 'weight') {
                $preparedData['gm_weight'] = self::cleanWeight($value);
            } else if ($key == 'resolution') {
                $preparedData['px_density'] = self::getPxDensity($value);
                $preparedData['px_resolution'] = self::getPxResolution($value);
            } else if ($key == 'display_type') {
                $preparedData['hz_refresh_rate'] = self::getHzRefreshRate($value);
            } else if ($key == 'cpu') {
                $preparedData['mhz_processor_speed'] = self::getMhzProcessorSpeed($value);
            } else if ($key == 'internal') {
                $preparedData['storage'] = self::getStorage($value);
                $preparedData['ram'] = self::getRam($value);
            } else if ($key == 'mc_resolutions') {
                $preparedData[$key] = self::getCameraResolutions($value);
            } else if ($key == 'sc_resolutions') {
                $preparedData[$key] = self::getCameraResolutions($value);
            } else if ($key == 'battery_type') {
                $preparedData['mah_battery'] = self::getBatteryCapacity($value);
            } else if ($allowAllUpdate) {
                $preparedData[$key] = $value;
            }
        }

        return $preparedData;
    }
}
