<?php
namespace zcy\helper;
class Regular{

    /** 检查邮件是否合法
     * @param $userName
     * @return bool
     */
    public static function checkEmail($userName){
        $email_match = preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/', $userName);
        if($email_match){
            return true;
        }else{
            return false;
        }
    }

    /** 检查手机号是否合法
     * @param $userName
     * @return bool
     */
    public static function checkMobile($userName)
    {
        $mobile_match = preg_match('/^(0|86|17951)?(1[3-9])[0-9]{9}$/', $userName);
        if ($mobile_match) {
            return true;
        } else {
            return false;
        }
    }

    /** 验证是身份证法是否合法
     * @param $userName
     * @return bool
     */
    public static function checkIdCard($userName)
    {
        $idcard_match = preg_match('/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/', $userName);
        if ($idcard_match) {
            return true;
        } else {
            return false;
        }
    }

    /*********php验证身份证号码是否正确函数*********/
    public static function isRightCard($id){
        $id = strtoupper($id);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if (!preg_match($regx, $id)) {
            return FALSE;
        }
        if (15 == strlen($id)) //检查15位
        {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
            @preg_match($regx, $id, $arr_split);     //检查生日日期是否正确
            $dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
            if (!strtotime($dtm_birth)) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else      //检查18位
        {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
            if (!strtotime($dtm_birth)) //检查生日日期是否正确
            {
                return FALSE;
            } else {       //检验18位身份证的校验码是否正确。       //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ($i = 0; $i < 17; $i++) {
                    $b = (int)$id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id, 17, 1)) {
                    return FALSE;
                } //phpfensi.com
                else {
                    return TRUE;
                }
            }
        }
    }
}