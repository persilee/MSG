<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/6
 * Time: 16:12
 */

namespace Common\Common;


class ExRateSpreadCheckMail
{
    const WARN_MAILTPL_TYPE = 'RATEWARN';
    private $_warningSpreadConf = null;
    private $_errorMessage = "";

    public function check($ci_no){
        //读取备份配置
        $this->_warningSpreadConf = C('INT_EXRATE_WARNING_SPREAD');
        //创建备份文件
        $warningArr = array();   //key:Client no.   ,  value:array['name']-客户名称   array['desc']-数组(描述)
        $Client  = M('Client');
        $clientResult = $Client->field(array('ci_no','name','create_emp','ex_rate_float'))->find($ci_no);
        if(is_array($clientResult) && "" != $clientResult['ex_rate_float']){
            $ExRateSpreadArr = json_decode($clientResult['ex_rate_float'],true);
            foreach ($ExRateSpreadArr as $exchangeCcyKey => $targetCcyItem){
                foreach ($targetCcyItem as $targetCcyKey => $exRateValue){
                    if(empty($exRateValue['is_exRate']) && $this->spreadCheck($targetCcyKey,$exRateValue['value'])){
                        $warningArr[$ci_no]['name'] = $clientResult['name'];
                        $warningArr[$ci_no]['desc'][] = "Exchange Currency : ".$exchangeCcyKey." , Target Currency : ".$targetCcyKey." , Spread value : ".$exRateValue['value'];
                    }
                }
            }
        }
        //如果存在利差大于参数设置值的情况下则发送邮件
        if(count($warningArr) > 0){
            //取得客户经理所在组及其父层所有组的主管Mail
            $Usergroup = M('Usergroup');
            $UsergroupTotArr = $Usergroup->field('id,pid')->select();
            $Usergroup_user = M('Usergroup_user');
            $usergroupArr = $Usergroup_user->where(array('user_id'=>$clientResult['create_emp']))->getField('usergroup_id',true);
            $parentUserGroupArr = array();
            foreach ($usergroupArr as $value){
                $tempArr = getParentTree($UsergroupTotArr,$value);
                $ids = array_column($tempArr, 'id');
                $parentUserGroupArr = array_merge($parentUserGroupArr,$ids);
                $parentUserGroupArr[] = $value;
            }
            //去除重复项
            $parentUserGroupArr = array_unique($parentUserGroupArr);
            //取得当前分组下的所有用户
            $userIDArr = $Usergroup_user->where(array('usergroup_id'=>array('IN',$parentUserGroupArr)))->getField('user_id',true);
            $userIDArr = array_unique($userIDArr);
            $Emp = M('Emp');
            $MailArr = $Emp->where(array('id'=>array('IN',$userIDArr),'is_director'=>1))->getField('email',true);
            //如果存在收件主管,则发送邮件
            if($MailArr){
                //给收件人添加后缀
                $mail_suffix = C('MAIL_SUFFIX');
                foreach ($MailArr as $key => $value){
                    $MailArr[$key] .= $mail_suffix;
                }
                //组装邮件数据
                //写表格头
                $errTableStr = "<table width='100%' border='1'><thead><tr><th>Client No.</th><th>Client Name</th><th>Description</th></tr></thead><tbody>";
                //写表格体
                foreach ($warningArr as $ci_no => $warningItem){
                    $errTableStr .= "<tr><td align='center'>".$ci_no."</td>";
                    $errTableStr .= "<td align='center'>".$warningItem['name']."</td>";
                    $errTableStr .= "<td align='center'>";
                    foreach ($warningItem['desc'] as $descItem){
                        $errTableStr .= $descItem;
                        $errTableStr .= "\n";
                    }
                    $auth = session('user_auth');
                    $errTableStr .= "</td><td align='center'>update user : ".$auth['name']."</td></tr>";
                }
                $errTableStr .= "</tbody></table>";
                //组装邮件内容
                $content = array(
                    'content'      => $errTableStr,
                );
                //取得模板ID
                $Mailtpl = M('Mailtpl');
                $mailtpl_id = $Mailtpl->where(array('type'=>self::WARN_MAILTPL_TYPE))->getField('id');
                $mailTools = new MailTools();
                $mailTools->prepareMail($mailtpl_id,$content);
                if(false === $mailTools->sendMail($MailArr,MailTools::MAIL_SEND_ROUTE_TERMINAL)){
                    $this->_errorMessage = $mailTools->getError();
                    return false;
                }
            }
        }
        return true;
    }

    //利率值检查
    private function spreadCheck($targetCcy,$exRateValue){
        //以$targetCcy取得配置值
        if(array_key_exists($targetCcy,$this->_warningSpreadConf)){
            $warningValue = floatval($this->_warningSpreadConf[$targetCcy]);
        }elseif(array_key_exists('ALL',$this->_warningSpreadConf)){
            $warningValue = floatval($this->_warningSpreadConf['ALL']);
        }
        //对比实际值与配置值
        $exRateValue = abs(floatval($exRateValue));
        if($exRateValue >= $warningValue){
            return true;
        }else{
            return false;
        }
    }

    public function getError(){
        return $this->_errorMessage;
    }
}
