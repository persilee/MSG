<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/1
 * Time: 14:59
 */

namespace Common\Common;


class MaillogTools
{
    const EMAIL_INSIDE = 0;
    const EMAIL_OUTSIDE = 1;
    /**
     *  记录邮件历史
     * @param string $content
     */
    static function mailLog($outside_flag,$mailtpl_id,$ci_no,$receiver,$status,$send_time,$content,$error_msg){
        if(self::EMAIL_INSIDE != $outside_flag && self::EMAIL_OUTSIDE != $outside_flag){
            return false;
        }
        $maillog = M('Maillog');
        $date = date('Y-m-d');
        $dateInt = date_to_int('-',$date);
        $maxSeq = $maillog->where(array('date'=>$dateInt))->max('seq');
        $maxSeq++;
        if(is_array($receiver)){
            //foreach ($receiver as $value){
            //    $receiverStr .= ";".$value;
            //}
            $receiverStr = implode(';',$receiver);
        }else{
            $receiverStr = $receiver;
        }
        if(empty(UID)){
            $emp_id = 0;
        }else{
            $emp_id = UID;
        }
        //生成文件
        $filename   = "./Data/mailFileBackup/".$date."_".$maxSeq.".html";
        $file = fopen($filename, 'a');
        fwrite($file, $content);
        fclose($file);
        $data = array(
            'date'          => $dateInt,
            'seq'           => $maxSeq,
            'outside_flag'  => intval($outside_flag),
            'mailtpl_id'    => $mailtpl_id,
            'ci_no'         => $ci_no,
            'receiver'      => $receiverStr,
            'emp_id'        => $emp_id,
            'status'        => $status,
            'send_time'     => $send_time,
            'comp_time'     => time(),
            'error_msg'     => $error_msg,
        );
        $maillog->add($data);
        return true;
    }
}