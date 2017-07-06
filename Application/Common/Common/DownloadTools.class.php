<?php
/**
 * 遍历目录，打包成zip格式
 */
namespace Common\Common;

class DownloadTools{
    private $downloadName = "";
    //下载并删除文件
    public function downloadFile($fileName="",$deleteFlag=true,$downloadName=""){
        if("" == $fileName){
            return false;
        }
        if("" != $downloadName){
            $this->downloadName = $downloadName;
        }else{
            $this->downloadName = strrchr($fileName,'/');
            if("" == $this->downloadName){
                $this->downloadName = $fileName;
            }else{
                $this->downloadName = substr($this->downloadName,1);
            }
        }

        //检查文件是否存在
        if (file_exists($fileName)){
            //打开文件
            $file = fopen($fileName,"r");
            //返回的文件类型
            Header("Content-type: application/octet-stream");
            //按照字节大小返回
            Header("Accept-Ranges: bytes");
            //返回文件的大小
            Header("Accept-Length: ".filesize($fileName));
            //这里对客户端的弹出对话框，对应的文件名
            Header("Content-Disposition: attachment; filename=".$this->downloadName);
            //修改之前，一次性将数据传输给客户端
            echo fread($file, filesize($fileName));
            //修改之后，一次只传输1024个字节的数据给客户端
            //向客户端回送数据
            $buffer = 1024;//
            //判断文件是否读完
            while (!feof($file)) {
                //将文件读入内存
                $file_data=fread($file,$buffer);
                //每次向客户端回送1024个字节的数据
                echo $file_data;
            }

            fclose($file);
            if($deleteFlag){
                unlink($fileName);
            }
            return true;
        }else{
            return false;
        }
    }
}
