<?php
/**
*  @author    RV Templates
*  @copyright 2017-2018 RV Templates. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

// Image_upload class is use for image uploading
class RvCustomSettingImageUpload extends Module
{
    public function imageUploading($image_src_1, $old_file)
    {
        $returnData = array();
        $errorMessage = "";
        $successUpload = false;
        $imgName = $image_src_1['name'];

        /************resize settings***************/
        // $savePath = _PS_BASE_URL_._MODULE_DIR_.'rvmultibanner/views/img/';
        $savePath = _PS_MODULE_DIR_.'rvcustomsetting/views/img/';
        $resultType = $this->imageConditions($image_src_1);
        if ($resultType) {
            $imgName = $image_src_1['name'];
            if (file_exists($savePath.$imgName)) {
                $new_img_name = explode(".", $imgName);
                $imgName = $new_img_name[0]."_".date("YmdHis").".".$new_img_name[1];
            }

            $save_destination = $savePath.$imgName;
            $resultUpload = move_uploaded_file($image_src_1['tmp_name'], $save_destination);

            if ($resultUpload) {//success
                $res = preg_match('/^demo_img_.*$/', $old_file);
                $res_2 = preg_match('/^pattern.*$/', $old_file);
                $res_3 = preg_match('/^no_pattern.*$/', $old_file);

                if (file_exists(dirname(__FILE__).'./../views/img/'.$old_file)
                    && $res != '1'
                    && $res_2 != '1'
                    && $res_3 != '1') {
                    unlink(dirname(__FILE__).'./../views/img/'.$old_file);
                }

                $successUpload = true;
            } else {
                $errorMessage .= $this->displayError($this->l("Image Upload Problem"));
            }
        } else {
            $errorMessage .= $this->displayError($this->l("Please Select Valid Image File."));
        }
        $returnData['error'] = $errorMessage;
        $returnData['success'] = $successUpload;
        $returnData['name'] = $imgName;

        return $returnData;
    }

    // Image_conditions
    public function imageConditions($image_src)
    {
        if ($image_src['type'] == "image/jpeg" ||
            $image_src['type'] == "image/jpg" ||
            $image_src['type'] == "image/png" ||
            $image_src['type'] == "image/gif" ) {
            return true;
        } else {
            return false;
        }
    }
}
