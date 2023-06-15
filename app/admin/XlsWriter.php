<?php

namespace LiteApp\admin;

class XlsWriter
{
    public $xlsObj;
    protected $fileObj;
    protected $exportFileName;
    private $exportExt = '.xlsx';

    public function __construct($path = '')
    {
        $xlsx_config = [
            //'path' => realpath(sys_get_temp_dir()),
            'path'  => $path,
        ];
        $this->xlsObj = new \Vtiful\Kernel\Excel($xlsx_config);
    }

    /**
     * 设置导出文件名和方式
     * @param string $fileName
     * @param string $sheetName
     * @param bool $memoryMode 内存模式，true为固定内存，占用更小内存空间
     * @return $this
     */
    public function fileName(string $fileName = '', string $sheetName = 'Sheet1', bool $memoryMode = true): self
    {
        $this->exportFileName = (empty($fileName) ? 'excel_' . date('YmdHis') : $fileName) . $this->exportExt;
        echo $tmpFileName = realpath(sys_get_temp_dir()). '/'. date("Ymd") . '_' . uniqid() . rand(1000, 9999) . '.xlsx';
        if ($memoryMode) {
            $this->fileObj = $this->xlsObj->constMemory($tmpFileName, $sheetName, false);
        } else {
            $this->fileObj = $this->xlsObj->fileName($tmpFileName, $sheetName);
        }
        return $this;
    }

    /**
     * 添加工作区
     * @param string|null $sheet
     * @return $this
     */
    public function addSheet(string $sheet = null): self
    {
        $this->fileObj->addSheet($sheet);
        return $this;
    }

    /**
     * 切换工作区
     * @param string $sheet
     * @return $this
     */
    public function checkoutSheet(string $sheet): self
    {
        $this->fileObj->checkoutSheet($sheet);
        return $this;
    }

    /**
     * 检查工作表是否存在
     * @param string $sheetName
     * @return bool
     */
    public function existSheet(string $sheetName): bool
    {
        return $this->fileObj->existSheet($sheetName);
    }

    /**
     * 设置表头
     * @param array $header
     * @return $this
     */
    public function header(array $header): self
    {
        if(empty($this->fileObj)){
            $this->fileName();
        }
        $this->fileObj->header($header);
        return $this;
    }

    /**
     * 合并单元格
     * @param string $scope 单元格范围 'A1:C1' | 'A1:A3'
     * @param string $string 单元格数据
     * @return $this
     */
    public function mergeCells(string $scope, string $string): self
    {
        $this->fileObj->mergeCells($scope, $string);
        return $this;
    }

    /**
     * 插入数据
     * @param array $data
     * @return $this
     */
    public function data(array $data): self
    {
        if(empty($this->fileObj)){
            $this->fileName();
        }
        $this->fileObj->data($data);
        return $this;
    }

    /**
     * 设置行号可实现从某行开始写入数据
     * @param int $i 行号从0开始递增，0表示第一行，1表示第二行，2表示第三行
     * @return $this
     */
    public function setCurrentLine(int $i): self
    {
        $this->fileObj->setCurrentLine($i);
        return $this;
    }

    /**
     * @return int 获取当前写入行号
     */
    public function getCurrentLine(): int
    {
        return $this->fileObj->getCurrentLine();
    }

    /**
     * 输出至文件
     * @param $path
     * @return false|string 成功返回文件名，失败返回false;
     */
    public function output($path = null)
    {
        $xlsx_filePath = $this->fileObj->output();
        ob_clean();
        flush();
        if(empty($path)){
            $dirdate = date('/Ym/d');
            $path = DT_ROOT . "/runtime/export".$dirdate ;
            $retFile = "runtime/export".$dirdate.'/'.$this->exportFileName;
        }else{
            $path = DT_ROOT . "/" . $path;
            $retFile = $path.'/'.$this->exportFileName;
        }
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
        if (rename($xlsx_filePath, $path.'/'.$this->exportFileName) === false) {
            return false;
        }
        return $retFile;
    }

    /**
     * 直接输入至浏览器下载
     * @param $data
     * @return void
     */
    public function export($data = null)
    {
        if(!empty($data)){
            if(!is_array($data)){
                echo 'No Data!';
                exit(0);
            }
            $this->data($data);
        }
        $xlsx_filePath = $this->fileObj->output();

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="' . $this->exportFileName . '"');
        header('Content-Length: ' . filesize($xlsx_filePath));
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        ob_clean();
        flush();

        if (copy($xlsx_filePath, 'php://output') === false) {
            // Throw exception
            echo 'Export file false!';
        }

        // Delete temporary file
        @unlink($xlsx_filePath);
        exit(0);
    }

    /**
     * 全量读取Excel数据
     * @param $file
     * @return array|false 失败返回false，成功返回数据集
     */
    public function reader($file, $sheet = null)
    {
        if(!file_exists($file)){
            return false;
        }
        return $this->xlsObj
            ->openFile($file)
            ->openSheet($sheet)
            ->getSheetData();
    }

    /**
     * 打开Excel数据表句柄
     * @param $file
     * @param $sheet
     * @return false|\Vtiful\Kernel\Excel
     */
    public function openFile($file, $sheet= null)
    {
        if(!file_exists($file)){
            return false;
        }
        return $this->xlsObj
            ->openFile($file)
            ->openSheet($sheet);
    }

    /**
     * 游标读取一行数据
     * @return array
     */
    public function nextRow()
    {
        return $this->xlsObj->nextRow();
    }

    /**
     * 关闭当前资源
     * @return void
     */
    public function close()
    {
        $this->xlsObj->close();
    }

    /**
     * @return string 获取扩展版本号
     */
    public function version(): string
    {
        return xlswriter_get_version();
    }

}