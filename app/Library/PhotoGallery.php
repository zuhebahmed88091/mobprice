<?php

namespace App\Library;
use App\Helpers\CommonHelper;

class PhotoGallery
{
    function getFileListFromDir($itemId)
    {
        $IMAGE_DIR = CommonHelper::getMobileImageDir();
       
        if (!is_dir($IMAGE_DIR . $itemId )) {
            mkdir($IMAGE_DIR . $itemId );
            chmod($IMAGE_DIR . $itemId , 0777);
        }
        $IMAGE_DIR = CommonHelper::getMobileImageDir() . $itemId ;
    
        return array_diff(scandir($IMAGE_DIR), array('.', '..'));
    }

    function getImagePreviewFromDirectory($itemId)
    {
        $result = array();

        // read all files from a directory
        $fileList = $this->getFileListFromDir($itemId);
        if (!empty($fileList)) {
            // set initial preview
            $result['initialPreview'] = $this->getInitialPreview($fileList, $itemId);

            // set initial preview config
            $result['initialPreviewConfig'] = $this->getInitialPreviewConfig($fileList, $itemId);

            // set override preview
            $result['append'] = false;
        }
        return $result;
    }

    function renameFileListForMismatch($itemId)
    {
        $IMAGE_DIR = CommonHelper::getMobileImageDir() . $itemId . '/';
        

        // read all files from a directory
        $fileList = $this->getFileListFromDir($itemId);

        $totalFiles = count($fileList);
        if ($totalFiles > 0) {
            // sort file list for checking rename
            sort($fileList);

            // rename file if index mismatch
            $index = 1;
            foreach ($fileList as $fileName) {
                $expectedFileName = $index . '.jpg';
                if ($fileName != $expectedFileName) {
                    $oldFilePath = $IMAGE_DIR . $fileName;
                    $newFilePath = $IMAGE_DIR . $expectedFileName;
                    rename($oldFilePath, $newFilePath);
                }
                $index++;
            }
        }
        return $totalFiles;
    }

    function getNextFileName($itemId)
    {
        // rename file list if any mismatch of index
        $totalFiles = $this->renameFileListForMismatch($itemId);

        // return $itemId . '_' . ($totalFiles + 1) . '.jpg';
         return ($totalFiles + 1) . '.jpg';
    }

    function getInitialPreview($fileList, $itemId)
    {

        if (!empty($itemId)) {
            $IMAGE_URL = CommonHelper::getMobileImageUrl() . $itemId . '/';
        } else {
            $IMAGE_URL = CommonHelper::getMobileImageUrl();
        }
        

        $urlList = array();
        if (!empty($fileList)) {
            // sort file list
            sort($fileList);

            // create preview array
            foreach ($fileList as $key => $fileName) {
                $urlList[] = $IMAGE_URL . $fileName . '?nocache=' . time();
            }
        }
        return $urlList;
    }

    function getInitialPreviewConfig($fileList, $itemId)
    {
        $configList = array();
        if (!empty($fileList)) {
            // sort file list
            sort($fileList);

            // create preview array
            foreach ($fileList as $key => $fileName) {
                $configList[] = array(
                    'caption' => $fileName,
                    'width' => "120px",
                    'url' => url('admin/gallery/delete'),
                    'key' => $fileName,
                    'extra' => array(
                        'itemId' => $itemId,
                        'fileName' => $fileName,
                        '_token' => csrf_token()
                    )
                );
            }
        }
        return $configList;
    }

    function rawUpload($fileTmpName, $fileName)
    {
        $IMAGE_TEMP_DIR = CommonHelper::getMobileImageTempDir();
        if (!is_dir($IMAGE_TEMP_DIR)) {
            mkdir($IMAGE_TEMP_DIR, 0777);
            chmod($IMAGE_TEMP_DIR, 0777);
        }

        if (is_writable($IMAGE_TEMP_DIR)) {
            $originalFile = $IMAGE_TEMP_DIR . $fileName;
            if (@move_uploaded_file($fileTmpName, $originalFile) === false) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    function resizeImage($fileName, $newWidth, $newHeight = 10000, $dirName = '')
    {
        
        if (!empty($dirName)) {
            $IMAGE_DIR = CommonHelper::getMobileImageDir() . $dirName . '/';
        } else {
            $IMAGE_DIR = CommonHelper::getMobileImageDir();
        }

        $IMAGE_TEMP_DIR = CommonHelper::getMobileImageTempDir();

        $originalFile = $IMAGE_TEMP_DIR . $fileName;
        if (!file_exists($originalFile)) {
            return false;
        }

        if (!is_dir($IMAGE_DIR)) {
            mkdir($IMAGE_DIR, 0777);
            chmod($IMAGE_DIR, 0777);
        }

        if (is_writable($IMAGE_DIR)) {
            $imageInfo = getimagesize($originalFile);

            if (!empty($imageInfo)) {
                $mime = $imageInfo['mime'];
                $width = $imageInfo[0];
                $height = $imageInfo[1];

                if ($mime == 'image/jpeg' || $mime == 'image/jpg') {
                    $img = imagecreatefromjpeg($originalFile);
                } else if ($mime == 'image/png') {
                    $img = imagecreatefrompng($originalFile);
                } else if ($mime == 'image/gif') {
                    $img = imagecreatefromgif($originalFile);
                } else {
                    unlink($originalFile);
                    return false;
                }

                if ($width > $newWidth || $height > $newHeight || $mime == 'image/png') {
                    if ($width > $newWidth) {
                        $percentage = $newWidth / $width;
                        $width *= $percentage;
                        $height *= $percentage;
                    }

                    if ($height > $newHeight) {
                        $percentage = $newHeight / $height;
                        $width *= $percentage;
                        $height *= $percentage;
                    }

                    $imageResource = imagecreatetruecolor($width, $height);

                    // if png image then add white background
                    if ($mime == 'image/png') {
                        $backgroundColor = imagecolorallocate($imageResource, 255, 255, 255);
                        imagefill($imageResource, 0, 0, $backgroundColor);
                    }

                    imagecopyresampled($imageResource, $img, 0, 0, 0, 0, $width, $height, $imageInfo[0], $imageInfo[1]);
                } else {
                    $imageResource = $img;
                }

                @unlink($originalFile);
                $outputFileUrl = $IMAGE_DIR . $fileName;

                return imagejpeg($imageResource, $outputFileUrl, 100);
            } else {
                unlink($originalFile);
                return false;
            }

        } else {
            return false;
        }
    }

    function urlUpload($sourceUrl, $destinationUrl)
    {
        $baseName = basename($sourceUrl);
        $sourceDir = str_replace($baseName, '', $sourceUrl);
        if (!is_dir($sourceDir)) {
            mkdir($sourceDir, 0777);
            chmod($sourceDir, 0777);
        }

        $baseName = basename($destinationUrl);
        $destinationDir = str_replace($baseName, '', $destinationUrl);
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0777);
            chmod($destinationDir, 0777);
        }

        if (is_writable($sourceDir) && is_writable($destinationDir)) {
            if (@copy($sourceUrl, $destinationUrl) === false) {
                return false;
            } else {
                unlink($sourceUrl);
            }
            return true;
        } else {
            return false;
        }
    }

    function moveFileList($itemId=0)
    {
        $IMAGE_DIR = config('constants.IMAGE_DIR');
        $IMAGE_MOVE_DIR = config('constants.IMAGE_MOVE_DIR');

        // read all files from a directory
        $fileList = $this->getFileListFromDir($itemId);
        if (!empty($fileList)) {
            foreach ($fileList as $fileName) {
                $sourcePath = $IMAGE_DIR . $fileName;
                $destinationPath = $IMAGE_MOVE_DIR . $fileName;
                if (@copy($sourcePath, $destinationPath)) {
                    @unlink($sourcePath);
                }
            }
        }
    }

    function getDefaultImage($mobileId, $totalImages)
    {
        $IMAGE_MOVE_URL = config('constants.IMAGE_MOVE_URL');
        if ($totalImages == 0) {
            return $IMAGE_MOVE_URL . $mobileId . '.jpg';
        } else {
            return $IMAGE_MOVE_URL . $mobileId . '_1.jpg';
        }
    }
}
