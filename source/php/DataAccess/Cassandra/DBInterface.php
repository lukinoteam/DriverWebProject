<?php
interface DataBaseAccess{
    public function insert($table, $dataObject);
    public function deleteRow($table,$dataObject);
    public function truncate($table);
}
?>
