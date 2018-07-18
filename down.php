<?php

/**
 * 
 * 下载类
 * 
 * @param $filename [文件名] 
 * @param $filedir [文件的相对路径]
 * 
 * @author leekachung <[leekachung17@gmail.com]>
 * 
 */
class Download{

	protected $filename;
	protected $filedir;

	protected $filePath;

	const BUFFER = 10; //设置每次输出的数据大小 防止服务器瞬时流量激增

	/**
	 * 初始化变量
	 * @param $filename [文件名] 
 	 * @param $filedir [文件的相对路径]
	 */
	public function __construct($filename, $filedir){

		$this->filename  = $filename;
		$this->filedir = $filedir;

		//PHP低版本需添加转码 防止中文乱码
		//本机生产环境配置好编码格式  中文不会乱码 所以不使用转码 
		//$filename = iconv("utf-8", "gb2312", $this->$filename);

		$this->filePath = $_SERVER['DOCUMENT_ROOT'] . $this->filedir . $this->filename; //拼接文件的绝对路径
	}

	/**
	 * 下载文件
	 */
	public function Down(){

		file_exists($this->filePath) or die('文件不存在'); //判断是否存在文件

		$fp = fopen($this->filePath, 'r'); //以只读权限打开文件

		$file_size = filesize($this->filePath); //计算文件字节大小

		// 设置返回的文件形式 （二进制流）
		header('Content-type: application/octet-stream');
		// 设置返回的文件大小按字节输出
		header('Accept-Ranges: bytes');
		// 设置返回文件大小
		header('Accept-Length: ' . $file_size);
		// 设置返回浏览器弹出下载框，并显示相对应的文件名
		header('Content-Disposition: attachment; filename=' . $this->filename);

		// 设置下载的字节计数器
		$fileCount = 0;

		// 清楚缓存
		ob_clean();
        flush();

        // 判断指针是否到达文件内容末尾
		while(!feof($fp) && ($file_size - $fileCount > 0)){
			$data = fread($fp, self::BUFFER);
			$fileCount += self::BUFFER; //字节计数
			echo $data; // 输出
		}

		fclose($fp); // 关闭文件
	}

}

$d = new Download('下载的文件名', '下载文件的相对路径');
$d->Down();

