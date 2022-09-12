<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Mobile;
use App\Models\MobileImage;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Library\PhotoGallery;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    public function index($mobileId)
    {
        $initialPreview = [];
        $initialPreviewConfig = [];

        $photoGallery = new PhotoGallery();
        $fileList = $photoGallery->getFileListFromDir($mobileId);

        if (!empty($fileList)) {
            // get initial preview
            $initialPreview = $photoGallery->getInitialPreview($fileList, $mobileId);

            // get initial preview config
            $initialPreviewConfig = $photoGallery->getInitialPreviewConfig($fileList, $mobileId);
        }
        $mobile = Mobile::findOrFail($mobileId);

        return view('mobiles.gallery', compact('mobile', 'initialPreview', 'initialPreviewConfig'));
    }

    public function upload(Request $request)
    {
        // set config dir
        $IMAGE_DIR = CommonHelper::getMobileImageDir();
        $IMAGE_TEMP_DIR = CommonHelper::getMobileImageTempDir();

        // receive request value
        $itemId = $request->itemId;

        // define photo gallery library
        $photoGallery = new PhotoGallery();

        if (!is_dir($IMAGE_DIR . $itemId)) {
            mkdir($IMAGE_DIR . $itemId );
            chmod($IMAGE_DIR . $itemId, 0777);
        }

        $errorList = array();
        foreach ($_FILES['file']['tmp_name'] as $key => $fileTmpName) {
            // get next uploaded file name according to index
            $fileName = $photoGallery->getNextFileName($itemId);

            // upload file and output json array for preview
            if ($photoGallery->rawUpload($fileTmpName, $fileName)) {
                $isReSized = $photoGallery->resizeImage($fileName, 300, 1000, $itemId);
                if (!$isReSized) {
                    // copy original image when resize failed
                    $sourceUrl = $IMAGE_TEMP_DIR . $fileName;
                    $destinationUrl = $IMAGE_DIR . $fileName;
                    $photoGallery->urlUpload($sourceUrl, $destinationUrl);
                }
            } else {
                $errorList[] = $key . ': Image upload failed';
            }
        }

        // get preview array
        $result = $photoGallery->getImagePreviewFromDirectory($itemId);

        //image upload in db
        $imageUpInDb = $this->imageUploadInDb($itemId);

        // generate error string
        if (!empty($errorList)) {
            $result['error'] = implode(',', $errorList);
        }

        return response()->json($result);
    }

    public function imageUploadInDb($itemId)
    {
        $photoGallery = new PhotoGallery();
        $mobile = Mobile::where('id', $itemId)->first();
        $mobile->mobileImages()->whereIn('mobile_id',[$itemId])->delete();
        $fileList = $photoGallery->getFileListFromDir($itemId);

        $key = 1;
        foreach($fileList as  $image){
            $mobileImages = MobileImage::create(['mobile_id'=>$itemId, 'filename'=>$image, 'sorting'=>$key]);
            $key++;
        }

        return $fileList;
    }

    public function sorting(Request $request)
    {
        $itemId = $request->input('itemId');
        $IMAGE_DIR = CommonHelper::getMobileImageDir() . $itemId . '/';


        $itemId = $request->itemId;
        $fileStack = $request->fileStack;
        if (!empty($fileStack)) {
            // rename to temporary name
            foreach ($fileStack as $key => $fileName) {
                $tempFileName = ($key + 100) . '.jpg';
                $oldFilePath = $IMAGE_DIR . $fileName;
                $newFilePath = $IMAGE_DIR . $tempFileName;
                rename($oldFilePath, $newFilePath);
            }

            // rename to real file name after soring
            foreach ($fileStack as $key => $value) {
                $tempFileName = ($key + 100) . '.jpg';
                $fileName = ($key + 1) . '.jpg';
                $oldFilePath = $IMAGE_DIR . $tempFileName;
                $newFilePath = $IMAGE_DIR . $fileName;
                rename($oldFilePath, $newFilePath);
            }
        }

        //image upload in db
        $imageUpInDb = $this->imageUploadInDb($itemId);
    }



    public function move(Request $request)
    {
        $itemId = $request->itemId;

        // define photo gallery library
        $photoGallery = new PhotoGallery();

        // get total files from dir
        $fileList = $photoGallery->getFileListFromDir();
        $totalImages = count($fileList);

        if ($totalImages > 0) {
            // rename file list before move
            $photoGallery->renameFileListForMismatch($itemId);

            // update gallery image count in mobile table
            Mobile::where('id', $itemId)->update(['images' => $totalImages]);

            // move all images to final dir
            $photoGallery->moveFileList();

            echo 1;
        } else {
            echo 2;
        }
    }

    public function delete(Request $request)
    {
        $itemId = $request->input('itemId');
        $IMAGE_DIR = CommonHelper::getMobileImageDir();

        if (!empty($itemId)) {
            $IMAGE_DIR = CommonHelper::getMobileImageDir() . $itemId . '/';
        } else {
            $IMAGE_DIR = CommonHelper::getMobileImageDir();
        }
        $fileName = $request->fileName;

        // remove image
        $filePath = $IMAGE_DIR . $fileName;
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        // get preview array
        $photoGallery = new PhotoGallery();
        $result = $photoGallery->getImagePreviewFromDirectory($itemId);

        echo json_encode($result);
        //image upload in db
        $imageUpInDb = $this->imageUploadInDb($itemId);
    }

}
