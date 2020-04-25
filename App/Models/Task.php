<?php

namespace App\Models;
use App\Helpers\Arr;
use Core\Model;

class Task extends Model
{
    public function get($id)
    {
        $ans = [];
        $ans['status'] = 0;
        ///
        $item = [];
        $sql = "SELECT * FROM tasks WHERE id={$id}";
        $res = $this->db->query($sql);
        if( @$res->num_rows )
        {
            $ans['status'] = 1;
            $item = $res->fetch_assoc();
        }
        $ans['item'] = $item;
        return $ans;
    }
    public function all($params = [])
    {
        $ans = [];
        $ans['status'] = 0;
        ///
        $items = [];
        ///
        /// Тут можно вкрутить фильтр, если ршим делать ajax пагинацию, фильтрацию или сортировку
        $filter = '';
        ///
        $sql = "SELECT * FROM tasks {$filter}";
        $res = $this->db->query($sql);
        if( @$res->num_rows )
        {
            $ans['status'] = 1;
            while( $row = $res->fetch_assoc() ) $items[] = $row;
        }
        $ans['items'] = $items;
        return $ans;
    }
    public function add($name,$email,$note)
    {
        $ans = [];

        $name  = $this->db->escape_string($name);
        $email = $this->db->escape_string($email);
        $note  = $this->db->escape_string($note);

        $sql = "INSERT INTO tasks (`name`,`email`,`note`) VALUES ('$name','$email','$note')";
        $this->db->query($sql);

        $ans['status'] = Arr::get($this->db,'affected_rows');
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
            foreach( $arr AS $field=>$value )
            {
                $value = $this->db->escape_string($value);
                $fields .= "`$field`='$value',";
                if( 'completed' === $field ) $completedExist = TRUE;
            }
            ///$fields = rtrim($fields,',');
            if( !$completedExist ) $fields .= "`completed`=0,";
            $fields .= "`update`=NOW()";
            ///
            $sql = "UPDATE tasks SET {$fields} WHERE id={$id}";
            $this->db->query($sql);
            $status = Arr::get($this->db,'affected_rows');
        }
        $ans['status'] = $status;
        return $ans;
    }
    public function del($id)
    {
        $ans = [];
        ///$sql = "DELETE FROM tasks WHERE id={$id}";
        $sql = "UPDATE tasks SET completed=-1 WHERE id={$id}";
        $this->db->query($sql);
        $ans['status'] = Arr::get($this->db,'affected_rows');
        return $ans;
    }
}