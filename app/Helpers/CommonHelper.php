<?php

namespace App\Helpers;

use App\Models\FileType;
use App\Models\Media;
use App\Models\MediaMap;
use DOMDocument;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class CommonHelper
{
    public static function getRole()
    {
        $roleName = '';
        $priority = 0;
        if (!empty(Auth::user()) && !empty(Auth::user()->roles)) {
            foreach (Auth::user()->roles as $role) {
                if ($role['priority'] > $priority) {
                    $priority = $role['priority'];
                    $roleName = $role['name'];
                }
            }
        }
        return $roleName;
    }

    public static function isCapable($permissions)
    {
        // Pass request if admin privilege is disabled
        if (!config('settings.IS_ADMIN_PRIVILEGE_ENABLE')) {
            return true;
        }

        // Pass request if super admin
        if (Auth::user()->hasRole('Admin')) {
            return true;
        }

        return Auth::user()->can($permissions);
    }

    public static function getDays($start, $end)
    {
        $start = strtotime($start);
        $end = strtotime($end);
        return intval(ceil(abs($end - $start) / 86400) + 1);
    }

    public static function setIntFilterQuery(&$query, $field, $operator, $value)
    {
        if ($operator == 1) {
            $query->where($field, '=', $value);
        } else if ($operator == 2) {
            $query->where($field, '>=', $value);
        } else if ($operator == 3) {
            $query->where($field, '<=', $value);
        } else if ($operator == 4) {
            $parts = explode('|', $value);
            if (count($parts) == 2) {
                $query->whereBetween($field, $parts);
            } else if (count($parts) == 1) {
                $query->whereBetween($field, [0, $parts[0]]);
            }
        }
    }

    public static function numberFormatIndia($num)
    {
        return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $num);
    }

    public static function getCurrentTime()
    {
        $current_time = (time() + (3600 * 0));
        return $current_time;
    }

    public static function displayTimeFormat($time, $onlyDate = false)
    {
        $time = strtotime($time);
        $current_time = self::getCurrentTime();
        $time_diff = $current_time - $time;

        if ($time_diff < 60) {
            $display_time = 'Just now';
        } else if ($time_diff < 3600) {
            $display_time = floor($time_diff / 60) . ' minutes ago';
        } else if ($time_diff < 86400 && date('Y-m-d', $time) == date('Y-m-d', $current_time)) {
            if ($onlyDate) {
                $display_time = 'Today';
            } else {
                $display_time = 'Today ' . date('g:i a', $time);
            }
        } else if ($time_diff < 172800) {
            if ($onlyDate) {
                $display_time = 'Yesterday';
            } else {
                $display_time = 'Yesterday ' . date('g:i a', $time);
            }
        } else {
            if ($onlyDate) {
                $display_time = date('M d, Y', $time);
            } else {
                $display_time = date('M d, Y g:i a', $time);
            }
        }
        return $display_time;
    }

    public static function getComparePercent($nCurrentPeriod, $nLastPeriod)
    {
        if ($nLastPeriod > 0) {
            $comparePercent = (($nCurrentPeriod - $nLastPeriod) / $nLastPeriod) * 100;
        } else {
            $comparePercent = 100;
        }
        return round($comparePercent, 2);
    }

    public static function generatePdf($html, $fileName = '', $orientation = 'portrait')
    {
        $domPdf = new Dompdf();
        $domPdf->loadHtml($html);
        $domPdf->setPaper('A4', $orientation); // orientation: (landscape, portrait)
        $domPdf->render();
        $domPdf->stream($fileName);
    }

    public static function setUpExcelSheet($event, $orientation = 'Portrait')
    {
        $highestRow = $event->sheet->getDelegate()->getHighestRow();
        $highestColumn = $event->sheet->getDelegate()->getHighestColumn();

        //  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellRange = 'A' . $row . ':' . $highestColumn . $row;
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(9);

            if ($row % 2 == 0) {
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF6F6F6');
            }
        }

        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D6D6D6']
                )
            )
        );
        $event->sheet->getDelegate()->getStyle('A1:' . $highestColumn . $highestRow)
            ->applyFromArray($styleArray);

        $event->sheet->getDelegate()->getPageSetup()
            ->setOrientation($orientation ? PageSetup::ORIENTATION_PORTRAIT : PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4);
    }

    public static function cleanDescription($content, $size = 0)
    {
        $content = preg_replace("/<br[^<>]*?>/i", "\n", $content);
        $content = preg_replace("/\n/", " ", $content);
        $content = preg_replace("/\r/", " ", $content);
        $content = preg_replace("/\s+/", " ", $content);

        $content = preg_replace("/<script.*?>.*?<\/script>/", "", $content);
        $i = 0;
        while ($i < 5) {
            $i++;
            $content = preg_replace("/<[^<>]*?>/", "", $content);
        }
        $content = preg_replace("/<.*?>/", "", $content);

        $append = '';
        if (mb_strlen($content) > $size) {
            $append = '...';
        }

        if ($size > 0) {
            // trim message to n characters, regardless of where it cuts off
            $msgTrimmed = mb_substr($content, 0, $size);

            // find the index of the last space in the trimmed message
            $lastSpace = strrpos($msgTrimmed, ' ', 0);

            // now trim the message at the last space so we don't cut it off in the middle of a word
            $content = mb_substr($msgTrimmed, 0, $lastSpace);
        }

        return $content . $append;
    }

    public static function getJsDisplayDateFormat()
    {
        if (config('settings.DISPLAY_DATE_FORMAT') == 'j/n/Y') {
            return 'M/D/YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'M j, Y') {
            return 'MMM D, YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'F j, Y') {
            return 'MMMM D, YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'm.d.y') {
            return 'MM.DD.YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'j, n, Y') {
            return 'D, M, YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'Y/m/d') {
            return 'YYYY/MM/DD';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'm/d/Y') {
            return 'MM/DD/YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'Y-m-d') {
            return 'YYYY-MM-DD';
        }

        return 'MMM DD, YYYY';
    }

    public static function getEmbeddedMediaIds($message)
    {
        $mediaIds = [];
        $dom = new domDocument;
        $dom->loadHtml($message, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $images = $dom->getElementsByTagName('img');
        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            $urlPath = parse_url($src, PHP_URL_PATH);
            if (!empty($urlPath)) {
                $filename = basename($urlPath);
                $mediaId = Media::where('filename', $filename)->value('id');
                if ($mediaId) {
                    $mediaIds[] = $mediaId;
                }
            }
        }

        return $mediaIds;
    }

    public static function syncAllMediaInHtmlContent($itemId, $itemField, $htmlContent)
    {
        // Get previous media ids
        $previousMediaIds = MediaMap::where([
            ['item_id', $itemId],
            ['item_field', $itemField],
        ])->pluck('media_id')->toArray();

        // Get current media ids
        $currentMediaIds = self::getEmbeddedMediaIds($htmlContent);
        if (!empty($previousMediaIds)) {
            foreach ($previousMediaIds as $mediaId) {
                if (in_array($mediaId, $currentMediaIds)) {
                    $currentMediaIds = array_diff($currentMediaIds, [$mediaId]);
                } else {
                    // delete map entry
                    MediaMap::where([
                        ['media_id', $mediaId],
                        ['item_id', $itemId],
                        ['item_field', $itemField],
                    ])->delete();

                    // Remove image & media entry
                    $media = Media::find($mediaId);
                    if (!empty($media)) {
                        Storage::delete($media->file_dir . $media->filename);
                        $media->delete();
                    }
                }
            }
        }

        // add new items in media maps
        foreach ($currentMediaIds as $mediaId) {
            MediaMap::create([
                'media_id' => $mediaId,
                'item_id' => $itemId,
                'item_field' => $itemField
            ]);
        }
    }

    public static function getFormattedAverageTime($timeInMinutes)
    {
        $inputSeconds = $timeInMinutes * 60;

        $secondsInAMinute = 60;
        $secondsInAnHour = 60 * $secondsInAMinute;
        $secondsInADay = 24 * $secondsInAnHour;

        // extract days
        $days = floor($inputSeconds / $secondsInADay);

        // extract hours
        $hourSeconds = $inputSeconds % $secondsInADay;
        $hours = floor($hourSeconds / $secondsInAnHour);

        // extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        // extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds = ceil($remainingSeconds);

        if ($days > 0) {
            return str_pad($days, 2, '0') . ':' . str_pad($hours, 2, '0') . ' D';
        } else if ($hours > 0) {
            return str_pad($hours, 2, '0') . ':' . str_pad($minutes, 2, '0') . ' H';
        } else {
            return str_pad($minutes, 2, '0') . ':' . str_pad($seconds, 2, '0') . ' M';
        }
    }

    public static function uploadMedia($request, $field, $imageDir) {
        $allowed_dir = ['products', 'articles', 'users', 'tickets', 'comments', 'bank_transactions'];

        if ($request->hasFile($field)) {
            $image = $request->file($field);

            $originalFileName = $image->getClientOriginalName();
            $fileExtension = $image->getClientOriginalExtension();

            if ($imageDir && in_array($imageDir, $allowed_dir)) {
                $fileDir = 'media/'. $imageDir . '/';
            } else {
                $fileDir = 'media/default/';
            }

            $fileType = FileType::where('name', $fileExtension)->first();
            if (empty($fileType)) {
                $fileTypeId = 1;
            } else {
                $fileTypeId = $fileType->id;
            }

            $uploadedFile = Media::create([
                'original_filename' => $originalFileName,
                'file_dir' => $fileDir,
                'file_type_id' => $fileTypeId,
                'user_id' => Auth::user()->id
            ]);

            $filename = $uploadedFile->id . '.' . $fileExtension;
            $image->storeAs($fileDir, $filename);

            $uploadedFile->update([
                'filename' => $filename
            ]);

            return $uploadedFile;
        }

        return '';
    }


    public static function getImageDirectory($path) {
        return str_replace( '\\', '/', storage_path($path) );
    }

    public static function getMobileImageDir() {
        return self::getImageDirectory(config('constants.MOBILE_IMAGE_DIR'));
    }

    public static function getMobileImageTempDir() {
        return self::getImageDirectory(config('constants.MOBILE_IMAGE_TEMP_DIR'));
    }

    public static function getMobileImageUrl() {
        return asset(config('constants.MOBILE_IMAGE_URL')) . '/';
    }

    public static function round($number, $decimalPlace) {
        return number_format(round($number, $decimalPlace), $decimalPlace);
    }

    public static function getRawQuery($sql){
        $query = str_replace(array('?'), array('\'%s\''), $sql->toSql());
        return vsprintf($query, $sql->getBindings());
    }

    public static function getUserIpAddress()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = '';
        }

        return $ipaddress;
    }
}
