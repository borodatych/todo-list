<?php

namespace App\Models;
use Core\Model;

class Task extends Model
{
    public function get($id)
    {
        $ans = [];

        $sql = "SELECT * FROM tasks WHERE id={$id}";
        $ans['item'] = $this->db->query($sql)->fetchOne();
        $ans['status'] = (int) ( !empty($ans['item']) );

        return $ans;
    }
    public function all($params = [])
    {
        $ans = [];
        ///
        /// Тут можно вкрутить фильтр, если ршим делать ajax пагинацию, фильтрацию или сортировку
        $filter = '';
        ///
        $sql = "SELECT * FROM tasks {$filter}";
        $ans['items'] = $this->db->query($sql)->fetchAll();
        $ans['status'] = (int) ( sizeof($ans['items']) > 0 );
        return $ans;
    }
    public function add($name,$email,$note)
    {
        $ans = [];

        $sql = "INSERT INTO tasks (`name`,`email`,`note`) VALUES (?,?,?)";
        $this->db->query($sql, [$name, $email, $note]);

        $ans['status'] = $this->db->affectedRows();
        return $ans;
    }
    public function upd($id,$arr)
    {
        $ans = [];
        $status = 0;
        $arr = (array) $arr;
        if( !empty($arr) )
        {
            $fields = '';
            $completedExist = FALSE;
            $params = [];
            foreach( $arr AS $field=>$value )
            {
                if( !$completedExist && 'completed' === $field ) $completedExist = TRUE;

                $fields .= "`$field`=?,";
                array_push($params, $value);
            }
            ///$fields = rtrim($fields,',');
            if( !$completedExist ) $fields .= "`completed`=1,";
            $fields .= "`update`=NOW()";
            ///
            $sql = "UPDATE tasks SET {$fields} WHERE id={$id}";
            $this->db->query($sql, $params);
            $status = $this->db->affectedRows();
        }
        $ans['status'] = $status;
        return $ans;
    }
    public function del($id)
    {
        $ans = [];
        $sql = "UPDATE tasks SET completed=0 WHERE id=?";
        $this->db->query($sql, $id);
        $ans['status'] = $this->db->affectedRows();
        return $ans;
    }
}
