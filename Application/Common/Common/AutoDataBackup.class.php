<?php
/**
 * Created by PhpStorm.
 * User: Hipr
 * Date: 16/8/6
 * Time: 16:12
 */

namespace Common\Common;
use Think\Db;


class AutoDataBackup extends AutoTaskTools
{
    public function exec(){
        if($this->checkTask('AutoDataBackup')){
            $this->echoMsg('H');
            //读取备份配置
            $config = array(
                'path'     => realpath(C('DATA_BACKUP_PATH')) . DIRECTORY_SEPARATOR,
                'part'     => C('DATA_BACKUP_PART_SIZE'),
                'compress' => C('DATA_BACKUP_COMPRESS'),
                'level'    => C('DATA_BACKUP_COMPRESS_LEVEL'),
            );
            //生成备份文件信息,并创建备份文件
            $file = array(
                'name' => date('Ymd-His', NOW_TIME),
                'part' => 1,
            );
            //创建备份文件
            $Database = new Database($file, $config);
            if(false !== $Database->create()){
                $Db    = Db::getInstance();
                $tables  = $Db->query('SHOW TABLES');
                $status = true;
                foreach($tables as $table){
                    foreach ($table as $tableName){
                        //备份指定表
                        $status  = $Database->backup($tableName, 0);
                    }
                    if(false === $status){
                        $this->echoMsg('C',"Backup table error ( table : ".$tableName." )",false);
                        $Database->delete();
                        break;
                    }else{
                        $this->echoMsg('C',"Backup table success ( table : ".$tableName." )");
                    }
                }
            }else{
                $this->echoMsg('C',"Create backup file error ",false);
            }
            $this->echoMsg('E');
            return true;
        }
    }
}