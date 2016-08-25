<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Gmulti
{
    public $css = array();
    public $js = array();
    public $output = array();
    public $grids = array();

    public function grid_add($id)
    {
        $this->grids[$id] = new Grocery_crud($id);
        $this->grids[$id]->set_theme('flexiajax');
    }

    public function render()
    {
        $ci = &get_instance();
        $seg = $ci->uri->segments;
        $f = $ci->uri->segment(count($seg)-1);
        $fv = $ci->uri->segment(count($seg));

        if($ci->router->fetch_class() != $f AND ($fv != 'success' AND $f != 'success'))
        {
            $temp = $this->grids[$fv]->render();
            return array('output'=>$temp->output,'css_files'=>$temp->css_files,'js_files'=>$temp->js_files);
        }
        else
        {
            foreach($this->grids as $k => $v)
            {
                $temp = $v->render();
                $this->output[$k] = $temp->output;
                $this->css = array_merge($this->css,(array)$temp->css_files);
                $this->js = array_merge($this->js,(array)$temp->js_files);
            }
            return array('output'=>$this->output,'css_files'=>$this->css,'js_files'=>$this->js);
        }
    }

}