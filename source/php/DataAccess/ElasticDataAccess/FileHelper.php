<?php
require 'composer/vendor/autoload.php';
final class FileHelper
{
    //MARK:- Public constructor
    public function __construct()
    {

    }

    //MARK:- Public function to get file's content
    public function getContent($type, $fileName)
    {
        $content = "";
        if ($type == "doc") {
            $content = $this->read_doc($fileName);
        } elseif ($type == "docx") {
            $content = $this->read_docx($fileName);
        } elseif ($type == "xlsx") {
            $content = $this->xlsx_to_text($fileName);
        } elseif ($type == "xls") {
            $content = $this->xls_to_text($fileName);
        } elseif ($type == "pptx") {
            $content = $this->pptx_to_text($fileName);
        } elseif ($type == "pdf") {
            $content = $this->pdf_to_text($fileName);
        }
        return $content;
    }

    //MARK: Private function to support reading file's contents

    //TO-DO: Read DOC file
    private function read_doc($fileName)
    {
        $fileHandle = fopen($fileName, "r");
        $line = @fread($fileHandle, filesize($fileName));
        $lines = explode(chr(0x0D), $line);
        $outtext = "";
        foreach ($lines as $thisline) {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== false) || (strlen($thisline) == 0)) {

            } else {
                $outtext .= $thisline . " ";
            }
        }
        $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $outtext);
        return $outtext;
    }
    //TO-DO: Read DOCX file
    private function read_docx($fileName)
    {

        $striped_content = '';
        $content = '';

        $zip = zip_open($fileName);

        if (!$zip || is_numeric($zip)) {
            return false;
        }

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == false) {
                continue;
            }

            if (zip_entry_name($zip_entry) != "word/document.xml") {
                continue;
            }

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        } // end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);

        return $striped_content;
    }
    //TO-DO: Read XLSX file
    private function xlsx_to_text($input_file)
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($input_file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $content = "";
        foreach ($sheetData as $sheet) {
            foreach ($sheet as $text) {
                $content .= $text;
                $content .= " ";
            }
        }
        return $content;
    }
    //TO-DO: Read XLS file
    private function xls_to_text($input_file)
    {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xls");
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($input_file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $content = "";
        foreach ($sheetData as $sheet) {
            foreach ($sheet as $text) {
                $content .= $text;
                $content .= " ";
            }
        }
        return $content;

    }
    //TO-DO: Read PDF file
    private function pdf_to_text($input_file)
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($input_file);

        $details = $pdf->getDetails();
        $text = "";
        // Loop over each property to extract values (string or array).
        foreach ($details as $property => $value) {
            if (is_array($value)) {
                $value = implode(', ', $value);
            }
            $text .= $property . ' => ' . $value . "\n";
        }
        return $text;
    }

    //TO-DO: Read PPTX file
    private function pptx_to_text($input_file)
    {
        $zip_handle = new ZipArchive;
        $output_text = "";
        if (true === $zip_handle->open($input_file)) {
            $slide_number = 1; //loop through slide files
            while (($xml_index = $zip_handle->locateName("ppt/slides/slide" . $slide_number . ".xml")) !== false) {
                $xml_datas = $zip_handle->getFromIndex($xml_index);
                $xml_handle = DOMDocument::loadXML($xml_datas, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                $output_text .= strip_tags($xml_handle->saveXML());
                $slide_number++;
            }
            if ($slide_number == 1) {
                $output_text .= " ";
            }
            $zip_handle->close();
        } else {
            $output_text .= " ";
        }
        return $output_text;
    }

}
