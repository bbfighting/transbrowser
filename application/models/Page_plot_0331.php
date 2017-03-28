<?php
//if ( ! defined('AUTHENTICATION')) exit('No direct script access allowed');

/* -------- -------- -------- --------
 * Class: IRESModel
 *
 * Methods:
 *   initialize($query_string)
 *   get_output()
 *   get_tf_list()
 *
 * Handle all query tasks in this website
 * -------- -------- -------- --------*/
class Page_plot extends CI_Model {


    private $_species;
    private $_asname;
    private $_cor1;
    private $_cor2;
    private $_issearch;
    private $_time;
    private $_mapcor;


    public function initialize($species, $target_name, $cor1, $cor2, $method, $time)
    {
        $this->_species = $species;
        $this->_asname = $target_name;
        $this->_cor1 = $cor1;
        $this->_cor2 = $cor2;
        if ($method == "search")
            $this->_issearch = true;
        else
            $this->_issearch = false;
        $this->_time = $time;
        $this->_mapcor = array("mapdirect" => "<map name=\"direct\">", "mapseq" => "<map name=\"seq\">", "mapcor" => "<map name=\"cor\">");
    }

    public function run($ri_track)
    {
        /* constant */
        // Create a 1170 x 2000 image 
        $gd_x = 1110;
        $gd_h = 2000;
        $im = imagecreate($gd_x, $gd_h);
        $diff_gd = 40;
        $temp_y = 25; //start cor y
        $py_path = ($this->_species == "hs") ? "pre" : "mouse";
        
        // -7 ~ -1 : 長度(width) 6, 有7個點(seq_len) 
        /* constant */
        if ($this->_issearch)
        {
            $sql = sprintf("SELECT * FROM saa_IRES_IREZone_uorf_%s WHERE accession_number LIKE '%s'", $this->_species, $this->_asname);
            $query = $this->db->query($sql);
            $row = $query->row();
            $row_seq_len = $row->seq_len;
            $IRES_all_str = $row->IRES;
            $IREZone_all_str = $row->IREZone;

            $path = sprintf("/home/testuser/public_html/temp_web/other/%s_py/IRESdataset/%s_five/xmlfile/%s.xml", $py_path, $this->_species, $this->_asname);
            $dom = new DOMDocument();
            $dom->load($path);
            $IRESearch = simplexml_import_dom($dom);
            $GeneName = $IRESearch->InputInfo->GeneName;
            $Sequence = $IRESearch->InputInfo->Sequence;
        }
        else
        {
            $path = sprintf("/home/testuser/public_html/transbrowser/other/IRESxml/%s_IRES_str.xml", $this->_asname);
            $dom = new DOMDocument();
            $dom->load($path);
            $IRESearch = simplexml_import_dom($dom);
            $GeneName = $IRESearch->InputInfo->GeneName;
            $Sequence = $IRESearch->InputInfo->Sequence;
            $row_seq_len = strlen($Sequence);
            $IRES_all_str = $IRESearch->InputInfo->IRES_str;
            $IREZone_all_str = $IRESearch->InputInfo->IREZone_str;            
        }

        if ($this->_cor1 == 0)
            $this->_cor1 = -$row_seq_len;
        
        $seq_len = $this->_cor2 - $this->_cor1 + 1;

        $cor_width = $gd_x - $diff_gd * 2;
        $range = ($cor_width) / ($seq_len - 1); // seq_width = seq_len - 1
        $diff_seq = ((int)($seq_len / 500) + 1) * 25;


        $font_file = "/home/testuser/public_html/fonts/calibri.ttf";
        $font_size = 10;

        // len_word_width --------------------------------------------------------
        $len_word_width= 6; // int, cha
        $len_word_height = 6;
        $len_stright = 5;
        $len_word_space = 10;
        $len_bara_height = 8; // 8
        $len_barb_height = 3; // 3
        $diff_barab_space = 2;
        $len_tail_space = 100;

        $len_word_up_height = 9;

        $bar_x = $diff_gd - 15;
        $diff_bar_space = 10;

        // Color definition
        $background = imagecolorallocate($im, 255, 255, 255);
        $white = imagecolorallocate($im, 255, 255, 255);
        $color_Text = imagecolorallocate($im, 0, 0, 0);
        $color_Line = imagecolorallocate($im, 120, 0, 20);
        $color_bar3a = imagecolorallocate($im, 76, 184, 249);
        $color_bar3b = imagecolorallocate($im, 11, 153, 238);        
        $color_bar2a = imagecolorallocate($im, 249, 76, 146);
        $color_bar2b = imagecolorallocate($im, 231, 18, 104);
        $color_bar1a = imagecolorallocate($im, 141,2,178);
        $color_bar1b = imagecolorallocate($im, 141,2,178);        
        $color_background = imagecolorallocate($im, 255, 245, 245);
        $color_TSS = imagecolorallocate($im, 15,141,118);


        // coordinate_line_len --------------------------------------------------------
        // head_tail --------------------------------------------------------
        $head_tail_x1 = $diff_gd; //start
        $head_tail_x2 = $gd_x - $diff_gd; //end
        $head_tail_y1 = $temp_y;
        $head_tail_y2 = $head_tail_y1 + $len_stright * 2;
        $head_tail_middle_x1 = $head_tail_x1 + $cor_width / 2 - strlen($seq_len) * $len_word_width / 2 - $len_word_space;
        $head_tail_middle_x2 = $head_tail_x2 - $cor_width / 2 + strlen($seq_len) * $len_word_width / 2 + $len_word_space;
        $head_tail_middle_y = $head_tail_y1 + $len_stright;


        // Lines of len of start/end
        imageline($im, $head_tail_x1, $head_tail_y1, $head_tail_x1, $head_tail_y2, $color_Line);
        imageline($im, $head_tail_x2, $head_tail_y1, $head_tail_x2, $head_tail_y2, $color_Line);
        
        // Lines of len of start/end of arrow
        imageline($im, $head_tail_x1, $head_tail_middle_y, $head_tail_x1 + $len_stright, $head_tail_y1, $color_Line);
        imageline($im, $head_tail_x1, $head_tail_middle_y, $head_tail_x1 + $len_stright, $head_tail_y2, $color_Line);
        imageline($im, $head_tail_x2, $head_tail_middle_y, $head_tail_x2 - $len_stright, $head_tail_y1, $color_Line);
        imageline($im, $head_tail_x2, $head_tail_middle_y, $head_tail_x2 - $len_stright, $head_tail_y2, $color_Line);

        // Lines of len
        imageline($im, $head_tail_x1, $head_tail_middle_y, $head_tail_middle_x1, $head_tail_middle_y, $color_Line);
        imageline($im, $head_tail_middle_x2, $head_tail_middle_y, $head_tail_x2, $head_tail_middle_y, $color_Line);
        imagefttext($im, $font_size, 0, $head_tail_middle_x1 + $len_word_space, $head_tail_y2, $color_Text, $font_file, $seq_len);

        // // Lines of cor
        $cor_x1 = $head_tail_x1; //cor of start
        $cor_x2 = $head_tail_x2; //cor of end
        $cor_y1 = $head_tail_y2 + $len_stright;
        $cor_y2 = $cor_y1 + $len_stright;
        $cor_word_y = $cor_y2 + $len_word_height + $len_word_space;
        $i_seqdiff = (int)(-$this->_cor2 / $diff_seq) + 1;
        $temp_cor_x = $cor_x2;
        $temp_y = $cor_word_y;

        imageline($im, $cor_x1, $cor_y1, $cor_x2, $cor_y1, $color_Line);
        imageline($im, $cor_x1, $cor_y1, $cor_x1, $cor_y2, $color_Line);
        imageline($im, $cor_x2, $cor_y1, $cor_x2, $cor_y2, $color_Line);
        imagefttext($im, $font_size, 0, $cor_x2, $cor_word_y, $color_Text, $font_file, $this->_cor2);

        imageline($im, $temp_cor_x, $cor_y1, $temp_cor_x, $cor_y2, $color_Line);


        if (!(-$diff_seq * $i_seqdiff <= $this->_cor1))
        {
            $temp_cor_x -= ($diff_seq * $i_seqdiff + $this->_cor2) * $range;
            imageline($im, $temp_cor_x, $cor_y1, $temp_cor_x, $cor_y2, $color_Line);
            if ($cor_x2 - $temp_cor_x > (strlen(-$diff_seq * $i_seqdiff) * $len_word_width))
                imagefttext($im, $font_size, 0, $temp_cor_x, $cor_word_y, $color_Text, $font_file, -$diff_seq * $i_seqdiff);
            else
            {
                imagefttext($im, $font_size, 0, $temp_cor_x, $cor_word_y + $len_word_height + $len_word_space, $color_Text, $font_file, -$diff_seq * $i_seqdiff);
                $temp_y += $len_word_height + $len_word_space;
            }
        }

        for ($i_seqdiff += 1; $i_seqdiff < -$this->_cor1 / $diff_seq; $i_seqdiff++) {
            $temp_cor_x -= $diff_seq * $range;
            imageline($im, $temp_cor_x, $cor_y1, $temp_cor_x, $cor_y2, $color_Line);
            imagefttext($im, $font_size, 0, $temp_cor_x, $cor_word_y, $color_Text, $font_file, -($diff_seq * $i_seqdiff));
        }

        if ($temp_cor_x - $cor_x1 > (strlen($this->_cor1) * $len_word_width))
            imagefttext($im, $font_size, 0, $cor_x1, $cor_word_y, $color_Text, $font_file, $this->_cor1);
        else
        {
            imagefttext($im, $font_size, 0, $cor_x1, $cor_word_y + $len_word_height + $len_word_space, $color_Text, $font_file, $this->_cor1);
            $temp_y += $len_word_height + $len_word_space;
        }

        if ($this->_issearch && $this->_cor1 == -$row_seq_len)
        {
            $word_TSS_x = $head_tail_x1 - 5;
            $word_TSS_y = $head_tail_y1 - strlen("TSS") * $len_word_width / 2;
            imagefttext($im, $font_size, 0, $word_TSS_x, $word_TSS_y, $color_TSS, $font_file, "TSS");
        }

        if ($this->_cor2 == -1)
        {
            $word_AUG_space = $len_word_width;
            $bar_AUG_x1 = $cor_x2;
            $bar_AUG_X2 = $cor_x2 + $word_AUG_space * 2 + strlen("AUG") * $len_word_width;
            $word_AUG_x = $head_tail_x2 + $word_AUG_space;
            $word_AUG_y = $cor_y1;
            imagefilledrectangle($im, $bar_AUG_x1, $head_tail_y1, $bar_AUG_X2, $cor_y2, $color_Text);
            imagefttext($im, $font_size, 0, $word_AUG_x, $word_AUG_y, $background, $font_file, "AUG");
        }
        // coordinate_line_len end--------------------------------------------------------

        $diff_space_bar = 10;
        $diff_bar_content = 10;
        $start_line_IREZone = $temp_y + $diff_space_bar;

        // uorf --------------------------------------------------------
////////////////////////////////////////////////////////////////////////////////////////
        $uorf_level = array();
////////////////////////////////////////////////////////////////////////////////////////



        if ($this->_issearch)
        {
            $bar_uorf_y1 = $temp_y + $diff_space_bar;
            $uorf_content_y = $bar_uorf_y1 + $diff_bar_content;
            $bar_uorf_word_y = $uorf_content_y + strlen("uorf") * $len_word_up_height;

            if ($row->uorf != NULL)
            {
                $temp_uorf_level = array(1 => 0);
                $uorf_array = explode(';', $row->uorf);
                $compare_line = 0;
                $uorf_count = 0;
                $level = 0;

                foreach ($uorf_array as $sub_uorf)
                {
                    $is_change = false;
                    $uorf_count += 1;
                    $temp_uorf = explode('~', $sub_uorf);
                    $uorf_start = $temp_uorf[0];
                    $uorf_end = $temp_uorf[1];
                    
                    if ($uorf_start <= $this->_cor2 && $uorf_end > $this->_cor1)
                    {
                        if ($uorf_end > $this->_cor2)
                            $uorf_end = $this->_cor2;
                        if ($uorf_start < $this->_cor1)
                            $uorf_start = $this->_cor1;            

                        foreach ($temp_uorf_level as $key => $value)
                        {
                            if ($uorf_end < $value || $value == 0)
                            {
                                $temp_uorf_level[$key] = $uorf_start;
                                $level = $key;
                                $is_change = true;
                                break;
                            }
                        }

                        if (!$is_change)
                        {
                            $level = count($temp_uorf_level) + 1;
                            $temp_uorf_level[$level] = $uorf_start;
                        }  

                        $uorf_bara_y1 = $uorf_content_y + ($len_bara_height + $diff_barab_space) * ($level - 1);
                        $uorf_x1 = $cor_x2 + (($uorf_start - $this->_cor2) * $range);
                        $uorf_x2 = $cor_x2 + (($uorf_end - $this->_cor2) * $range);
                        $uorf_bara_y2 = $uorf_bara_y1 + $len_bara_height;
                        $uorf_barb_y1 = $uorf_bara_y2 - $len_barb_height;

                        
                        $uorf_count_x = $uorf_x1 - strlen($uorf_count) * $len_word_width - 2;
                        if ($uorf_count_x < $cor_x1)
                            $uorf_count_x = $uorf_x1;

/***************************************************************************************/
            $this->_mapcor['mapdirect'] .= sprintf("<area alt=\"%s~%s\" class=\"tfbs_tip\" coords=\"%s,%s,%s,%s\" title=\"%s~%s\">", $uorf_start, $uorf_end, $uorf_x1, $uorf_bara_y1, $uorf_x2, $uorf_bara_y2, $uorf_start, $uorf_end);
/***************************************************************************************/ 

                        imagefilledrectangle($im, $uorf_x1, $uorf_bara_y1, $uorf_x2, $uorf_bara_y2, $color_bar3a);
                        imagefilledrectangle($im, $uorf_x1, $uorf_barb_y1, $uorf_x2, $uorf_bara_y2, $color_bar3b);
                        imagefttext($im, $font_size, 0, $uorf_count_x, $uorf_bara_y2, $color_Text, $font_file, $uorf_count);

                        // $uorf_bara_y1 = $uorf_bara_y2 + $diff_barab_space;
                        if ($uorf_bara_y2 > $temp_y)
                            $temp_y = $uorf_bara_y2;

////////////////////////////////////////////////////////////////////////////////////////
                        $temp_uorf_start = $uorf_start - $this->_cor1 + 1;
                        $temp_uorf_end = $uorf_end - $this->_cor1 + 1;

                        $temp_key1 = (int)($temp_uorf_start / 100);
                        $temp_key2 = (int)($temp_uorf_end / 100);

                        $temp_key2_mod = $temp_uorf_end % 100;
                        
                        if ($temp_key2_mod == 0)
                            $temp_key2 = $temp_key2 - 1;

                        for ($i = $temp_key1; $i <= $temp_key2; $i++)
                        {
                            $level_key = sprintf("%d-%d", $i * 100, ($i + 1) * 100); 

                            if ($temp_uorf_start - ($i * 100) > 0)
                                $start_value =  $temp_uorf_start;
                            else
                                $start_value = $i * 100;
                            if ($temp_uorf_end - (($i + 1) * 100) < 0)
                                $end_value = $temp_uorf_end;
                            else
                                $end_value = ($i + 1) * 100;
                            if (! array_key_exists($level_key, $uorf_level))
                                $uorf_level[$level_key] = array($start_value . ";" . $end_value . ";" . $level . ";" . $uorf_count);
                            else
                                array_push($uorf_level[$level_key], $start_value . ";" . $end_value . ";" . $level . ";" . $uorf_count);
                        }
////////////////////////////////////////////////////////////////////////////////////////



                    }

                }
            }

            $bar_uorf_y2 = $temp_y + $diff_bar_content;

            if ($bar_uorf_y2 < $bar_uorf_word_y + $diff_bar_content)
                $bar_uorf_y2 = $bar_uorf_word_y + $diff_bar_content; 

            imagefilledrectangle($im, $bar_x, $bar_uorf_y1, $cor_x1, $bar_uorf_y2, $color_bar3b); 
            imageStringUp($im, 5, $bar_x, $bar_uorf_word_y, "UORF", $background);
            $temp_y = $bar_uorf_y2;
        }
        // uorf end --------------------------------------------------------

        // IRES --------------------------------------------------------
        $IRES_array = explode(';', $IRES_all_str);
        $compare_line = 0;
        $bar_IRES_y1 = $temp_y + $diff_space_bar;
        $IRES_bara_y1 = $bar_IRES_y1 + $diff_bar_content;
        $bar_IRES_word_y = $IRES_bara_y1 + strlen("irezone") * $len_word_up_height;
        $IRES_count = 0;

////////////////////////////////////////////////////////////////////////////////////////
        $IRES_level = array();
        $level = 0;        
////////////////////////////////////////////////////////////////////////////////////////


        if ($IRES_all_str != NULL && $IRES_all_str != "")
        {
            foreach ($IRES_array as $sub_IRES)
            {
                $IRES_count += 1;
                $temp_IRES = explode(':', $sub_IRES);
                $IRES_start = $temp_IRES[0];
                $IRES_end = $temp_IRES[1];

                if ($IRES_start <= $this->_cor2 && $IRES_end > $this->_cor1)
                {
                    if ($IRES_end > $this->_cor2)
                        $IRES_end = $this->_cor2;
                    if ($IRES_start < $this->_cor1)
                        $IRES_start = $this->_cor1;            

                    if ($IRES_end < $compare_line - 100 || $compare_line == 0)
                    {
                        $compare_line = $IRES_start;
////////////////////////////////////////////////////////////////////////////////////////
                        $level = 0;
////////////////////////////////////////////////////////////////////////////////////////
                        $IRES_bara_y1 = $bar_IRES_y1 + $diff_bar_content;
                    }

////////////////////////////////////////////////////////////////////////////////////////
                        $level += 1;
////////////////////////////////////////////////////////////////////////////////////////

                    $IRES_x1 = $cor_x2 + (($IRES_start - $this->_cor2) * $range);
                    $IRES_x2 = $cor_x2 + (($IRES_end - $this->_cor2) * $range);
                    $IRES_bara_y2 = $IRES_bara_y1 + $len_bara_height;
                    $IRES_barb_y1 = $IRES_bara_y2 - $len_barb_height;

                    $IRES_count_x = $IRES_x1 - strlen($IRES_count) * $len_word_width - 2; 
                    if ($IRES_count_x < $cor_x1)
                        $IRES_count_x = $IRES_x1;

/***************************************************************************************/
                    $this->_mapcor['mapdirect'] .= sprintf("<area alt=\"%s~%s\" class=\"tfbs_tip\" coords=\"%s,%s,%s,%s\" title=\"%s~%s\">", $IRES_start, $IRES_end, $IRES_x1, $IRES_bara_y1, $IRES_x2, $IRES_bara_y2, $IRES_start, $IRES_end);
/***************************************************************************************/                   

                    imagefilledrectangle($im, $IRES_x1, $IRES_bara_y1, $IRES_x2, $IRES_bara_y2, $color_bar2a);
                    imagefilledrectangle($im, $IRES_x1, $IRES_barb_y1, $IRES_x2, $IRES_bara_y2, $color_bar2b);
                    imagefttext($im, $font_size, 0, $IRES_count_x, $IRES_bara_y2, $color_Text, $font_file, $IRES_count);

                    $IRES_bara_y1 = $IRES_bara_y2 + $diff_barab_space;
                    if ($IRES_bara_y2 > $temp_y)
                        $temp_y = $IRES_bara_y2;


////////////////////////////////////////////////////////////////////////////////////////
                    $temp_IRES_start = $IRES_start - $this->_cor1 + 1;
                    $temp_IRES_end = $IRES_end - $this->_cor1 + 1;

                    $temp_key1 = (int)($temp_IRES_start / 100);
                    $temp_key2 = (int)($temp_IRES_end / 100);

                    $temp_key2_mod = $temp_IRES_end % 100;
                    
                    if ($temp_key2_mod == 0)
                        $temp_key2 = $temp_key2 - 1;
                    for ($i = $temp_key1; $i <= $temp_key2; $i++)
                    {
                        $level_key = sprintf("%d-%d", $i * 100, ($i + 1) * 100); 

                        if ($temp_IRES_start - ($i * 100) > 0)
                            $start_value =  $temp_IRES_start;
                        else
                            $start_value = $i * 100;
                        if ($temp_IRES_end - (($i + 1) * 100) < 0)
                            $end_value = $temp_IRES_end;
                        else
                            $end_value = ($i + 1) * 100;
                        if (! array_key_exists($level_key, $IRES_level))
                            $IRES_level[$level_key] = array($start_value . ";" . $end_value . ";" . $level . ";" . $IRES_count);
                        else
                            array_push($IRES_level[$level_key], $start_value . ";" . $end_value . ";" . $level . ";" . $IRES_count);
                    }
////////////////////////////////////////////////////////////////////////////////////////
                }
            }
        }

        $IREZone_middle_y = $temp_y + $len_word_space * 2;
        $bar_IRES_y2 =  $IREZone_middle_y + $len_word_height + $len_word_space;;

        if ($bar_IRES_y2 < $bar_IRES_word_y + $diff_bar_content)
            $bar_IRES_y2 = $bar_IRES_word_y + $diff_bar_content;
        imagefilledrectangle($im, $bar_x, $bar_IRES_y1, $cor_x1, $bar_IRES_y2, $color_bar2b); 
        imageStringUp($im, 5, $bar_x, $bar_IRES_word_y, "IREZone", $background);   
        $temp_y = $bar_IRES_y2;
        // IRES end --------------------------------------------------------


        // ribosome --------------------------------------------------------
        if ($this->_issearch)
        {
            $bar_ri_y1 = $temp_y + $diff_space_bar;
            $space_track = 40;
            $space_ri_max = 30;
            $ri_depth_y1 = $bar_ri_y1 + $diff_bar_content + $space_track;
            $bar_ri_word_y = $ri_depth_y1 + strlen("ribosome") * $len_word_up_height;

            if ($ri_track != "no")
            {
                $track_notNone_array = array();
                $trans_array = explode("_", $ri_track);
                $trans_word_x = $cor_x2 + $len_word_space;

                $ri_depth_array_start = -$this->_cor2 - 1 + 1; // cor_x1 remove ex:-1043~-1 dot-1043 remove
                $ri_depth_array_end = $seq_len - 1;

                $sql = sprintf("SELECT * FROM saa_ri_dep_%s WHERE ass LIKE '%s%%'", $this->_species, $this->_asname);
                $query = $this->db->query($sql);

                $temp_depth_array = array();

                foreach ($trans_array as $sub_trans)
                {
                    $temp_depth_str = "";

                    foreach ($query->result() as $row_dep)
                    {
                        $temp_depth_str .= $row_dep->$sub_trans; 
                    }
                    if ($temp_depth_str != "")
                    {
                        $temp_depth_array = array_merge($temp_depth_array, array_slice(explode(';', $temp_depth_str), $ri_depth_array_start, $ri_depth_array_end));
                        array_push($track_notNone_array, $sub_trans);
                    }
                }

                $ri_max = (count($temp_depth_array) == 0) ? 0 : max(array_map('floatval', $temp_depth_array));
                $range_dep = ($ri_max == 0) ? 0 : $space_ri_max / $ri_max; // space 20 max 16

                foreach ($trans_array as $sub_trans)
                {
                    $ri_depth_x1 = $cor_x1;
                    $temp_depth_str = "";
                    

                    // ri_dep_cor_start
                    if ($ri_max != 0)
                    {
                        for ($h = 0; $h <= 10; $h ++)
                        {
                            $sep_line = ($h % 5 == 0) ? 10 : 5;
                            imageline($im, $cor_x1, $ri_depth_y1 - $space_ri_max * $h / 10, $cor_x1 + $sep_line, $ri_depth_y1 - $space_ri_max * $h / 10, $color_Text);
                        }
                        imagefttext($im, $font_size, 0, $cor_x1 + 12, $ri_depth_y1 - $space_ri_max + $len_word_height / 2, $color_Text, $font_file, number_format($ri_max, 2));
                    }
                    // ri_dep_cor_end

                    if (in_array($sub_trans, $track_notNone_array))
                    {
                        foreach ($query->result() as $row_dep)
                        {
                            $temp_depth_str .= $row_dep->$sub_trans; 
                        }

                        $ri_depth_array = explode(';', $temp_depth_str);

                        foreach (array_slice($ri_depth_array, $ri_depth_array_start, $ri_depth_array_end) as $sub_ri_depth)
                        {
                            preg_replace('[^0-9]', '', $sub_ri_depth);
               
                            $ri_depth_x2 = $ri_depth_x1 + $range;
                            imagefilledrectangle($im, $ri_depth_x1, $ri_depth_y1, $ri_depth_x2, $ri_depth_y1 - $sub_ri_depth * $range_dep, $color_bar1b);
                            $ri_depth_x1 = $ri_depth_x2;
                        }
                    }
                    else
                        imageline($im, $cor_x1, $ri_depth_y1, $cor_x2, $ri_depth_y1, $color_bar1b);

                    imagefttext($im, $font_size, 0, $trans_word_x, $ri_depth_y1, $color_Text, $font_file, $sub_trans);
                    imagefttext($im, $font_size, 0, $trans_word_x, $ri_depth_y1 + 50, $color_Text, $font_file, $ri_max);
                    imagefttext($im, $font_size, 0, $trans_word_x  - 100, $ri_depth_y1 + 50, $color_Text, $font_file, $query->num_rows());


                    $temp_y = $ri_depth_y1;
                    $ri_depth_y1 += $space_track;
                }
            }
                

            $bar_ri_y2 = $temp_y + $diff_bar_content;

            if ($bar_ri_y2 < $bar_ri_word_y)
                $bar_ri_y2 = $bar_ri_word_y + $diff_bar_content;
            imagefilledrectangle($im, $bar_x, $bar_ri_y1, $cor_x1, $bar_ri_y2, $color_bar1b); 
            imageStringUp($im, 5, $bar_x, $bar_ri_word_y, "Ribosome", $background);
            $temp_y = $bar_ri_y2;
        }
        // ribosome end --------------------------------------------------------

        // IREZone --------------------------------------------------------
        $start_y = $start_line_IREZone;
        $IREZone_array = explode(';', $IREZone_all_str);
        $IREZone_count = 1;
        $bar_IREZone_y1 = $temp_y + $diff_space_bar;     
        // $IREZone_y1 = $start_y;
        $IREZone_y2 = $temp_y; 
        $left = true;  
        $right = true;  
        $IREZone_y1 = ($this->_issearch) ? $start_y : $bar_IRES_y1;   

        if ($IREZone_all_str != "" && $IREZone_all_str != NULL)
        {
            foreach ($IREZone_array as $sub_IREZone)
            {
                $IREZone_y1 = ($this->_issearch) ? $start_y : $bar_IRES_y1;   

                $temp_IREZone = explode(':', $sub_IREZone);
                $IREZone_start = $temp_IREZone[0];
                $IREZone_end = $temp_IREZone[1];

                if ($IREZone_start <= $this->_cor2 && $IREZone_end > $this->_cor1)
                {

                    $IREZone_x1 = $cor_x2 + (($IREZone_start - $this->_cor2) * $range);
                    $IREZone_x2 = $cor_x2 + (($IREZone_end - $this->_cor2) * $range);

                    if ($IREZone_start < $this->_cor1)
                    {
                        $IREZone_x1 = $cor_x1;
                        $left = false;
                    }
                    if ($IREZone_end > $this->_cor2)
                    {
                        $IREZone_x2 = $cor_x2;
                        $right = false;           
                    }

                    for (;$IREZone_y1 + 15 < $IREZone_y2; $IREZone_y1++)
                    {
                        if ($left)
                            imageline($im, $IREZone_x1, $IREZone_y1, $IREZone_x1, $IREZone_y1 + 12, $color_Line); // right
                        if ($right)
                            imageline($im, $IREZone_x2, $IREZone_y1, $IREZone_x2, $IREZone_y1 + 12, $color_Line); // left
                        $IREZone_y1 += 15;
                    }
                    if ($left)    
                        imageline($im, $IREZone_x1, $IREZone_y1, $IREZone_x1, $IREZone_y2, $color_Line); //right
                    if ($right)
                        imageline($im, $IREZone_x2, $IREZone_y1, $IREZone_x2, $IREZone_y2, $color_Line); //left
                    $left = true;  
                    $right = true;                      
                    
                    $IREZone_width = $IREZone_x2 - $IREZone_x1;
                    $len_IREZone_word_width = (strlen("IREZone") + strlen($IREZone_count)) * $len_word_width + $len_word_space * 2;
                    $IREZone_middle_x1 =  $IREZone_x1 + $IREZone_width / 2 - $len_IREZone_word_width / 2;
                    $IREZone_middle_x2 =  $IREZone_x2 - $IREZone_width / 2 + $len_IREZone_word_width / 2;

                    // Lines of len of start/end of arrow
                    if ($len_IREZone_word_width < $IREZone_width)
                    {
                        imageline($im, $IREZone_x1, $IREZone_middle_y, $IREZone_x1 + $len_stright, $IREZone_middle_y + $len_stright, $color_Line);
                        imageline($im, $IREZone_x1, $IREZone_middle_y, $IREZone_x1 + $len_stright, $IREZone_middle_y - $len_stright, $color_Line);
                        imageline($im, $IREZone_x2, $IREZone_middle_y, $IREZone_x2 - $len_stright, $IREZone_middle_y + $len_stright, $color_Line);
                        imageline($im, $IREZone_x2, $IREZone_middle_y, $IREZone_x2 - $len_stright, $IREZone_middle_y - $len_stright, $color_Line);

                        imageline($im, $IREZone_x1, $IREZone_middle_y, $IREZone_middle_x1, $IREZone_middle_y, $color_Line);
                        imageline($im, $IREZone_middle_x2, $IREZone_middle_y,  $IREZone_x2, $IREZone_middle_y, $color_Line);
                    }
                    imagefttext($im, $font_size, 0, $IREZone_middle_x1 + $len_word_space, $IREZone_middle_y + $len_stright, $color_Text, $font_file, "IREZone" . $IREZone_count);               
                }
                $IREZone_count++;
            }
        }
        // IREZone end --------------------------------------------------------
    
        $this->db->close();


        $temp_y += $len_tail_space;
        $dest = imagecreate($gd_x, $temp_y);
        imagecopy($dest, $im, 0, 0, 0, 0, $gd_x, $temp_y);
        imagepng($dest, 'textures/plot/' . $this->_asname . $this->_time . '.png');
        imagedestroy($im);    
        imagedestroy($dest);

        for ($k = $this->_cor1; $k <= $this->_cor2; $k++)
        {
            $this->_mapcor['mapdirect'] .= sprintf("<area class=\"cor_mouse\" coords=\"%s,%s,%s,%s\" id=\"%s\" style=\"cursor:default\">", $cor_x1, 25, $cor_x1 + $range, $temp_y - 80, $k);
            $cor_x1 += $range;
        }
        $this->_mapcor['mapdirect'] .=  "</map>";

////////////////////////////////////////////////////////////////////////////////////////
         /* constant */
        // Create a 1170 x 2000 image 
        $gd_h = 6000;
        $im = imagecreate($gd_x, $gd_h);
        $temp_y = 25;
        $cor_x1 = $diff_gd - 15;
        
        // -7 ~ -1 : 長度(width) 6, 有7個點(seq_len) 
        $font_size = 10;    
        $font_file = "/home/testuser/public_html/temp_final/fonts/NotCourierSans.otf";

        // len_word_width --------------------------------------------------------
        $len_word_width = 8; // int, cha
        $len_word_height = 10;
        $len_stright = 5;
        $len_word_space = 10;
        $len_bara_height = 12; // 8

        // Color definition
        $background = imagecolorallocate($im, 255, 255, 255);
        $white = imagecolorallocate($im, 255, 255, 255);
        $color_Text = imagecolorallocate($im, 0, 0, 0);
        $color_Line = imagecolorallocate($im, 120, 0, 20);
        $color_bar1a = imagecolorallocate($im, 249, 76, 146);
        $color_bar1b = imagecolorallocate($im, 231, 18, 104);
        $color_bar3a = imagecolorallocate($im, 153,206,255);
        $color_bar3b = imagecolorallocate($im, 59,33,227);
        $color_bar2a = imagecolorallocate($im, 255,192,195);
        $color_bar2b = imagecolorallocate($im, 255,42,53);
        $color_background = imagecolorallocate($im, 255, 245, 245);
        $color_TSS = imagecolorallocate($im, 15,141,118);
        $sub_seq = substr($Sequence, $this->_cor1 + strlen($Sequence), $seq_len);
        // sequence start -------------------------------------------------------- // sequence = $sub_seq
        $seq_len_full_i = ((int)($seq_len % 100) != 0) ? (int)($seq_len / 100) + 1 : (int)($seq_len / 100);
        $seq_len_x_space = 10;
        $seq_len_y_space = $len_word_height * 3;

        $diff_seq_bar_height_space = 3;

        $seq_x1 = $cor_x1 + 10 * $len_word_width;
        $cor_x2 = $seq_x1 + 105 * $len_word_width + 9 * $seq_len_x_space;
           
        $max_level = $temp_y;

        if ($this->_issearch && $this->_cor1 == -$row_seq_len)
        {
            $bar_TSS_x = 2;
            $bar_TSS_y1 = $temp_y - $len_word_height * 2 - 2;
            imagefttext($im, $font_size, 0, $seq_x1, $bar_TSS_y1 + $len_word_height, $color_TSS, $font_file, "TSS");
            imagefilledrectangle($im, $seq_x1 - $bar_TSS_x, $bar_TSS_y1, $seq_x1, $temp_y, $color_TSS);
        }

        $past_y = $temp_y;

        for ($i = 0; $i < $seq_len_full_i; $i++)
        {
            $temp_seq_x1 = $seq_x1;

            for ($j = 0; $j < 10; $j++)
            {
                imagefttext($im, $font_size, 0, $temp_seq_x1, $temp_y, $color_Text, $font_file, substr($sub_seq, $i * 100 + $j * 10, 10));
                $temp_seq_x1 += $len_word_width * 10 + $seq_len_x_space;
            }
            imagefttext($im, $font_size, 0, $cor_x1, $temp_y, $color_Text, $font_file, sprintf("%s", $this->_cor1 + $i * 100));
            imagefttext($im, $font_size, 0, $cor_x2, $temp_y, $color_Text, $font_file, sprintf("%s", (($seq_len - ($i + 1) * 100 + 1) > 1) ? ($this->_cor1 + ($i + 1) * 100 - 1) : ""));

            $temp_key = sprintf("%d-%d", $i * 100, ($i + 1) * 100);
            if (array_key_exists($temp_key, $IRES_level))
            {            
                foreach ($IRES_level[$temp_key] as $IRES)
                {
                    $sub_IRES = explode(";", $IRES);
                    $IRES_start = $sub_IRES[0];
                    $IRES_start_mod100 = $IRES_start % 100;
                    $IRES_start_mod10 = $IRES_start % 10;
                    $IRES_start_di10 = (int)($IRES_start_mod100 / 10);
                    $IRES_end = $sub_IRES[1];
                    $IRES_end_mod100 = ($IRES_end % 100 != 0) ? ($IRES_end % 100) : 100;
                    $IRES_end_mod10 = $IRES_end % 10;
                    $IRES_end_di10 = (int)($IRES_end_mod100 / 10);
                    $IRES_height = $sub_IRES[2];
                    $IRES_count = $sub_IRES[3];

                    $bar_IRES_X1 = ($IRES_start_mod10 != 0) ? $seq_x1 + ($IRES_start_mod100 - 1) * $len_word_width + $IRES_start_di10 * $seq_len_x_space : $seq_x1 + $IRES_start_mod100 * $len_word_width + $IRES_start_di10 * $seq_len_x_space;
                    $bar_IRES_X2 = ($IRES_end_mod10 != 0) ? $seq_x1 + $IRES_end_mod100 * $len_word_width + $IRES_end_di10 * $seq_len_x_space : $seq_x1 + $IRES_end_mod100 * $len_word_width + ($IRES_end_di10 - 1) * $seq_len_x_space;
                    $bar_IRES_y1 = $temp_y + $diff_seq_bar_height_space + ($len_bara_height + $diff_seq_bar_height_space) * ($IRES_height - 1);
                    $bar_IRES_y2 = $bar_IRES_y1 + $len_bara_height;


/***************************************************************************************/
                    $temp_IRES = explode(':', $IRES_array[$IRES_count - 1]);
                    $IRES_start = $temp_IRES[0];
                    $IRES_end = $temp_IRES[1];
                    $this->_mapcor['mapseq'] .= sprintf("<area alt=\"%s~%s\" class=\"tfbs_tip\" coords=\"%s,%s,%s,%s\" title=\"%s~%s\">", $IRES_start, $IRES_end, $bar_IRES_X1, $bar_IRES_y1, $bar_IRES_X2, $bar_IRES_y2, $IRES_start, $IRES_end);
/***************************************************************************************/                   


                    imagefilledrectangle($im, $bar_IRES_X1, $bar_IRES_y1, $bar_IRES_X2, $bar_IRES_y2, $color_bar2a);
                    imageline($im, $bar_IRES_X1, $bar_IRES_y1, $bar_IRES_X2, $bar_IRES_y1, $color_bar2b);
                    imageline($im, $bar_IRES_X1, $bar_IRES_y2, $bar_IRES_X2, $bar_IRES_y2, $color_bar2b);
                    if ($IRES_start_mod100 != 0)
                       imageline($im, $bar_IRES_X1, $bar_IRES_y1, $bar_IRES_X1, $bar_IRES_y2, $color_bar2b);
                    if ($IRES_end_mod100 != 100)
                       imageline($im, $bar_IRES_X2, $bar_IRES_y1, $bar_IRES_X2, $bar_IRES_y2, $color_bar2b);
                    imagefttext($im, $font_size, 0,$bar_IRES_X1 + 2, $bar_IRES_y2, $color_Text, $font_file, $IRES_count);
                    if ($bar_IRES_y2  > $max_level)
                        $max_level = $bar_IRES_y2;
                }
            }

            $past_y = $temp_y;
            $temp_y = $max_level;
            if (array_key_exists($temp_key, $uorf_level))
            {
                foreach ($uorf_level[$temp_key] as $uorf)
                {
                    $sub_uorf = explode(";", $uorf);
                    $uorf_start = $sub_uorf[0];
                    $uorf_start_mod100 = $uorf_start % 100;
                    $uorf_start_mod10 = $uorf_start % 10;
                    $uorf_start_di10 = (int)($uorf_start_mod100 / 10);
                    $uorf_end = $sub_uorf[1];
                    $uorf_end_mod100 = ($uorf_end % 100 != 0) ? ($uorf_end % 100) : 100;
                    $uorf_end_mod10 = $uorf_end % 10;
                    $uorf_end_di10 = (int)($uorf_end_mod100 / 10);
                    $uorf_height = $sub_uorf[2];
                    $uorf_count = $sub_uorf[3];

                    $bar_uorf_X1 = ($uorf_start_mod10 != 0) ? $seq_x1 + ($uorf_start_mod100 - 1) * $len_word_width + $uorf_start_di10 * $seq_len_x_space : $seq_x1 + $uorf_start_mod100 * $len_word_width + $uorf_start_di10 * $seq_len_x_space;
                    $bar_uorf_X2 = ($uorf_end_mod10 != 0) ? $seq_x1 + $uorf_end_mod100 * $len_word_width + $uorf_end_di10 * $seq_len_x_space : $seq_x1 + $uorf_end_mod100 * $len_word_width + ($uorf_end_di10 - 1) * $seq_len_x_space;
                    $bar_uorf_y1 = $temp_y + $diff_seq_bar_height_space + ($len_bara_height + $diff_seq_bar_height_space) * ($uorf_height - 1);
                    $bar_uorf_y2 = $bar_uorf_y1 + $len_bara_height;

/***************************************************************************************/
                    $temp_uorf = explode('~', $uorf_array[$uorf_count - 1]);
                    $uorf_start = $temp_uorf[0];
                    $uorf_end = $temp_uorf[1];
                    $this->_mapcor['mapseq'] .= sprintf("<area alt=\"%s~%s\" class=\"tfbs_tip\" coords=\"%s,%s,%s,%s\" title=\"%s~%s\">", $uorf_start, $uorf_end, $bar_uorf_X1, $bar_uorf_y1, $bar_uorf_X2, $bar_uorf_y2, $uorf_start, $uorf_end);
/***************************************************************************************/  

                    imagefilledrectangle($im, $bar_uorf_X1, $bar_uorf_y1, $bar_uorf_X2, $bar_uorf_y2, $color_bar3a);
                    imageline($im, $bar_uorf_X1, $bar_uorf_y1, $bar_uorf_X2, $bar_uorf_y1, $color_bar3b);
                    imageline($im, $bar_uorf_X1, $bar_uorf_y2, $bar_uorf_X2, $bar_uorf_y2, $color_bar3b);
                    if ($uorf_start_mod100 != 0)
                        imageline($im, $bar_uorf_X1, $bar_uorf_y1, $bar_uorf_X1, $bar_uorf_y2, $color_bar3b);
                    if ($uorf_end_mod100 != 100)
                        imageline($im, $bar_uorf_X2, $bar_uorf_y1, $bar_uorf_X2, $bar_uorf_y2, $color_bar3b);
                    imagefttext($im, $font_size, 0,$bar_uorf_X1 + 2, $bar_uorf_y2, $color_Text, $font_file, $uorf_count);
                    if ($bar_uorf_y2  > $max_level)
                        $max_level = $bar_uorf_y2;
                }
            }
            $temp_y = $max_level + $seq_len_y_space;
            $max_level = $temp_y;
        }
        $this->db->close();


        $bar_AUG_sep_x = 2;
        $bar_AUG_sep_y = $past_y - $len_word_height * 2;
        $AUG_start = $seq_len; 
        $AUG_start_mod100 = ($AUG_start % 100 != 0) ? ($AUG_start % 100) : 100;
        $AUG_start_mod10 = $AUG_start % 10;
        $AUG_start_di10 = (int)($AUG_start_mod100 / 10);

        $bar_AUG_x1 = ($AUG_start_mod10 != 0) ? $seq_x1 + $AUG_start_mod100 * $len_word_width + $AUG_start_di10 * $seq_len_x_space : $seq_x1 + $AUG_start_mod100 * $len_word_width + ($AUG_start_di10 - 1) * $seq_len_x_space;
        $bar_AUG_x2 = $bar_AUG_x1 + strlen("AUG") * $len_word_width;

        if ($this->_cor2 == -1)
        {
            imagefttext($im, $font_size, 0, $bar_AUG_x1 - $len_word_width, $past_y, $color_TSS, $font_file, substr($Sequence, -1));
            imagefilledrectangle($im, $bar_AUG_x1 + $bar_AUG_sep_x, $past_y - $len_word_height, $bar_AUG_x2, $past_y, $color_Text);
            imagefilledrectangle($im, $bar_AUG_x1, $bar_AUG_sep_y, $bar_AUG_x1 + $bar_AUG_sep_x, $past_y, $color_Text);
            imagefttext($im, $font_size, 0, $bar_AUG_x1 - $len_word_width * 2, $past_y - $len_word_height - 2, $color_TSS, $font_file, "-1");
            imagefttext($im, $font_size, 0, $bar_AUG_x1 + $bar_AUG_sep_x, $past_y, $background, $font_file, "AUG");
        }
        $this->db->close();

/***************************************************************************************/
            $this->_mapcor['mapseq'] .= "</map>";
/***************************************************************************************/


        $dest = imagecreate($gd_x, $temp_y);
        imagecopy($dest, $im, 0, 0, 0, 0, $gd_x, $temp_y);

        // Output and free from memory
        imagepng($dest, 'textures/plot/seq_' . $this->_asname . $this->_time . '.png');
        imagedestroy($im);    
        imagedestroy($dest);
////////////////////////////////////////////////////////////////////////////////////////
        return $this->_mapcor;
    }
}