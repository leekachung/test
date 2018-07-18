<?php

/**
 * 
 * 下载类
 * 
 * @param $filename [文件名] 
 * @param $filedir [文件的相对路径]
 * 
 */
class Download{

	protected $filename;
	protected $filedir;

	const BUFFER = 10; //设置每次输出的数据大小 防止服务器瞬时流量激增

	/**
	 * 下载文件
	 * @param [type] $filename [description]
	 * @param [type] $filedir  [description]
	 */
	public function Down($filename, $filedir){

		//PHP低版本需添加转码 防止中文乱码
		//本机生产环境为PHP7.2.1  中文不会乱码 所以不使用转码 
		//$filename = iconv("utf-8", "gb2312", $filename);

		$filePath = $_SERVER['DOCUMENT_ROOT'] . $filedir . $filename; //拼接文件的绝对路径

		file_exists($filePath) or die('文件不存在'); //判断是否存在文件

		$fp = fopen($filePath, 'r'); //以只读权限打开文件

		$file_size = filesize($filePath); //计算文件字节大小

		// 设置返回的文件形式 （二进制流）
		header('Content-type: application/octet-stream');
		// 设置返回的文件大小按字节输出
		header('Accept-Ranges: bytes');
		// 设置返回文件大小
		header('Accept-Length: ' . $file_size);
		// 设置返回浏览器弹出下载框，并显示相对应的文件名
		header('Content-Disposition: attachment; filename=' . $filename);

		// 设置下载的字节计数器
		$fileCount = 0;

		// 清楚缓存
		ob_clean();
        flush();

        // 判断指针是否到达文件内容末尾
		while(!feof($fp) && ($file_size - $fileCount > 0)){
			$data = fread($fp, self::BUFFER);
			$fileCount += self::BUFFER;
			echo $data; // 输出
		}

		fclose($fp); // 关闭文件
	}

}

$d = new Download;
$d->Down('顺平.wmv', '/');

