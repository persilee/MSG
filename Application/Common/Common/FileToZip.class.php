<?php
/**
 * 遍历目录，打包成zip格式
 */
namespace Common\Common;

class FileToZip{
    private $zipdir;  //需要压缩的目录
    private $savepath; //压缩文件保存目录
    private $fileArr;  //需要压缩的目录下所有元素集合
    private $zipname ; //压缩完成后的文件
    private $zipFile ; //压缩文件完整路径文件名
    public function __construct($zipdir,$savepath){
        $this->zipdir = $zipdir;
        $this->savepath = $savepath;
    }

    /**
     * 压缩文件(zip格式)
     */
    public function tozip(){ 
        //遍历目录
        if (is_dir($this->zipdir)){
            $this->fileArr = scandir($this->zipdir);
        }else{
            return false;
        }
        // 压缩文件
        $ZipArchive = new \ZipArchive();
        $this->zipname = date('YmdHis',time()).rand().".zip";
        $this->zipFile = $this->savepath.$this->zipname;
        if(true !== $ZipArchive->open($this->zipFile,\ZipArchive::CREATE)){
            return false;
        }
        foreach ($this->fileArr as $value) {
            if($value != "." && $value != ".."){
                if(!$ZipArchive->addFile($this->zipdir.$value,$value)){
                    return false;
                }
            }
        }
        $ZipArchive->close();
        return $this->zipname;
    }

    //下载并删除ZIP文件
    public function downloadZip(){
        $downloadTools = new DownloadTools();
        return $downloadTools->downloadFile($this->zipFile,true,$this->zipname);
    }
}