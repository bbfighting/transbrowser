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

    /* -------- -------- -------- --------
     * Constructor
     *
     * Parameters:
     *   $query_string: the query string
     * ------- -------- -------- --------*/
    private $_asname;
    private $_cor1;
    private $_cor2;

    /* -------- -------- -------- --------
     * Public: initialize
     * 
     * Parameters:
     *   $query_string: the query string
     *
     * Returns
     *   $this
     *
     * Initialize the tasks
     * ------- -------- -------- --------*/
    public function initialize($target_name, $cor1, $cor2)
    {
        $this->_asname = $target_name;
        $this->_cor1 = $cor1;
        $this->_cor2 = $cor2;
    }

    public function run()
    {
        /* constant */
        // Create a 1170 x 2000 image 
        $gd_x = 1170;
        $gd_h = 2000;
        $im = imagecreate($gd_x, $gd_h);
        $diff_gd = 40;
        $temp_y = 25; //start cor y
        
        // -7 ~ -1 : 長度(width) 6, 有7個點(seq_len) 

        /* constant */
        $sql = sprintf("SELECT * FROM saa_IRES_IREZone_uorf WHERE accession_number LIKE '%s'", $this->_asname);
        $query = $this->db->query($sql);
        $row = $query->row();



        $path = sprintf("/home/testuser/public_html/temp_web/other/pre_py/IRESdataset/hs_five/xmlfile/%s.xml", $this->_asname);

        if ($this->_cor1 == 0)
            $this->_cor1 = -$row->seq_len;
        
        $seq_len = $this->_cor2 - $this->_cor1 + 1;

        $cor_width = $gd_x - $diff_gd * 2;
        $range = ($cor_width) / ($seq_len - 1); // seq_width = seq_len - 1
        $diff_seq = ((int)($seq_len / 500) + 1) * 25;


        $font_file = "/home/testuser/public_html/fonts/calibri.ttf";
        // $font_size = ($seq_len >= 1000) ? 10 : 25;
        $font_size = 10;

        // len_word_width --------------------------------------------------------
        $len_word_width= 6; // int, cha
        $len_word_height = 6;
        $len_stright = 5;
        $len_word_space = 10;
        $len_bara_height = 8;
        $len_barb_height = 3;
        $diff_barab_space = 2;

        $len_word_up_height = 9;

        $bar_x = $diff_gd - 15;
        $diff_bar_space = 10;

        // Color definition
        $background = imagecolorallocate($im, 255, 255, 255);
        $white = imagecolorallocate($im, 255, 255, 255);
        $color_Text = imagecolorallocate($im, 0, 0, 0);
        $color_Line = imagecolorallocate($im, 120, 0, 20);
        $color_bar1a = imagecolorallocate($im, 249, 76, 146);
        $color_bar1b = imagecolorallocate($im, 231, 18, 104);
        $color_bar1a = imagecolorallocate($im, 76, 184, 249);
        $color_bar1b = imagecolorallocate($im, 11, 153, 238);        
        $color_bar2a = imagecolorallocate($im, 255, 180, 200);
        $color_bar2b = imagecolorallocate($im, 255, 150, 150);
        $color_bar3a = imagecolorallocate($im, 180, 30, 30);
        $color_bar3b = imagecolorallocate($im, 142, 24, 24);        
        $color_bar2a = imagecolorallocate($im, 249, 76, 146);
        $color_bar2b = imagecolorallocate($im, 231, 18, 104);
        $color_background = imagecolorallocate($im, 255, 245, 245);


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


        if (-$diff_seq * $i_seqdiff != $this->_cor1)
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

        // coordinate_line_len end--------------------------------------------------------


        // ribosome --------------------------------------------------------
        $diff_space_cor_ri = 20;
        $diff_space_bar = 10;
        $diff_bar_content = 10;

        $trans_array = array("G1", "G2", "S1", "S2", "M1", "M2");

        $trans_word_x = $cor_x2 + $len_word_space;
        $bar_ri_y1 = $temp_y + $diff_space_cor_ri;
        $ri_depth_y1 = $bar_ri_y1 + $diff_bar_content;
        $bar_ri_word_y = $ri_depth_y1 + strlen("ribosome") * $len_word_up_height;

        $ri_depth_array_start = -$this->_cor2 - 1 + 1; // cor_x1 remove ex:-1043~-1 dot-1043 remove
        $ri_depth_array_end = $seq_len - 1;

        $sql = sprintf("SELECT * FROM saa_ri_dep WHERE ass LIKE '%s%%'", $this->_asname);
        $query = $this->db->query($sql);

        $range_dep = ($row->max == 0) ? 0 : 18 / $row->max;
        foreach ($trans_array as $sub_trans)
        {
            $ri_depth_x1 = $cor_x1;
            $temp_depth_str = "";

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
            imagefttext($im, $font_size, 0, $trans_word_x, $ri_depth_y1, $color_Text, $font_file, $sub_trans);
            $temp_y = $ri_depth_y1;
            $ri_depth_y1 += 20;
        }
            

        $bar_ri_y2 = $temp_y + $diff_bar_content;

        if ($bar_ri_y2 < $bar_ri_word_y)
            $bar_ri_y2 = $bar_ri_word_y + $diff_bar_content;
        imagefilledrectangle($im, $bar_x, $bar_ri_y1, $cor_x1, $bar_ri_y2, $color_bar1b); 
        imageStringUp($im, 5, $bar_x, $bar_ri_word_y, "Ribosome", $background);
        $temp_y = $bar_ri_y2;
        // ribosome end --------------------------------------------------------

        // IRES --------------------------------------------------------
        $IRES_array = explode(';', $row->IRES);
        $compare_line = 0;
        $bar_IRES_y1 = $temp_y + $diff_space_bar;
        $IRES_bara_y1 = $bar_IRES_y1 + $diff_bar_content;
        $bar_IRES_word_y = $IRES_bara_y1 + strlen("irezone") * $len_word_up_height;

        foreach ($IRES_array as $sub_IRES)
        {
            $temp_IRES = explode(':', $sub_IRES);
            $IRES_start = $temp_IRES[0];
            $IRES_end = $temp_IRES[1];

            if ($IRES_start <= $this->_cor2 && $IRES_end > $this->_cor1)
            {
                if ($IRES_end > $this->_cor2)
                    $IRES_end = $this->_cor2;
                if ($IRES_start < $this->_cor1)
                    $IRES_start = $this->_cor1;            

                if ($IRES_end < $compare_line - 25 || $compare_line == 0)
                {
                    $compare_line = $IRES_start;
                    $IRES_bara_y1 = $bar_IRES_y1 + $diff_bar_content;
                }
                $IRES_x1 = $cor_x2 + (($IRES_start - $this->_cor2) * $range);
                $IRES_x2 = $cor_x2 + (($IRES_end - $this->_cor2) * $range);
                $IRES_bara_y2 = $IRES_bara_y1 + $len_bara_height;
                $IRES_barb_y1 = $IRES_bara_y2 - $len_barb_height;


                imagefilledrectangle($im, $IRES_x1, $IRES_bara_y1, $IRES_x2, $IRES_bara_y2, $color_bar2a);
                imagefilledrectangle($im, $IRES_x1, $IRES_barb_y1, $IRES_x2, $IRES_bara_y2, $color_bar2b);
                $IRES_bara_y1 = $IRES_bara_y2 + $diff_barab_space;
                if ($IRES_bara_y2 > $temp_y)
                    $temp_y = $IRES_bara_y2;
            }
        }

        $IREZone_middle_y = $temp_y + $len_word_space * 2;
        $bar_IRES_y2 =  $IREZone_middle_y + $len_word_height + $len_word_space;;

        if ($bar_IRES_y2 < $bar_IRES_word_y)
            $bar_IRES_y2 = $bar_IRES_word_y + $diff_bar_content;
        imagefilledrectangle($im, $bar_x, $bar_IRES_y1, $cor_x1, $bar_IRES_y2, $color_bar2b); 
        imageStringUp($im, 5, $bar_x, $bar_IRES_word_y, "IREZone", $background);   
        $temp_y = $bar_IRES_y2;
        // IRES end --------------------------------------------------------

        // uorf --------------------------------------------------------
        $uorf_array = explode(';', $row->uorf);
        $compare_line = 0;
        $bar_uorf_y1 = $temp_y + $diff_space_bar;
        $uorf_bara_y1 = $bar_uorf_y1 + $diff_bar_content;
        $bar_uorf_word_y = $uorf_bara_y1 + strlen("uorf") * $len_word_up_height;

        foreach ($uorf_array as $sub_uorf)
        {
            $temp_uorf = explode('~', $sub_uorf);
            $uorf_start = $temp_uorf[0];
            $uorf_end = $temp_uorf[1];
            $level_array = array();

            if ($uorf_start <= $this->_cor2 && $uorf_end > $this->_cor1)
            {
                if ($uorf_end > $this->_cor2)
                    $uorf_end = $this->_cor2;
                if ($uorf_start < $this->_cor1)
                    $uorf_start = $this->_cor1;            

                $uorf_x1 = $cor_x2 + (($uorf_start - $this->_cor2) * $range);
                $uorf_x2 = $cor_x2 + (($uorf_end - $this->_cor2) * $range);
                $uorf_bara_y2 = $uorf_bara_y1 + $len_bara_height;
                $uorf_barb_y1 = $uorf_bara_y2 - $len_barb_height;

                imagefilledrectangle($im, $uorf_x1, $uorf_bara_y1, $uorf_x2, $uorf_bara_y2, $color_bar3a);
                imagefilledrectangle($im, $uorf_x1, $uorf_barb_y1, $uorf_x2, $uorf_bara_y2, $color_bar3b);
                $uorf_bara_y1 = $uorf_bara_y2 + $diff_barab_space;
                if ($uorf_bara_y2 > $temp_y)
                    $temp_y = $uorf_bara_y2;
            }
        }

        $bar_uorf_y2 = $temp_y + $diff_bar_content;

        if ($bar_uorf_y2 < $bar_uorf_word_y)
            $bar_uorf_y2 = $bar_uorf_word_y + $diff_bar_content;
        imagefilledrectangle($im, $bar_x, $bar_uorf_y1, $cor_x1, $bar_uorf_y2, $color_bar3b); 
        imageStringUp($im, 5, $bar_x, $bar_uorf_word_y, "UORF", $background);
        $temp_y = $bar_uorf_y2;
        // uorf end --------------------------------------------------------


        // IREZone --------------------------------------------------------
        $start_y = $bar_ri_y1;
        $IREZone_array = explode(';', $row->IREZone);
        $IREZone_count = 1;
        $bar_IREZone_y1 = $temp_y + $diff_space_bar;     
        $IREZone_y1 = $start_y;
        $IREZone_y2 = $temp_y; 
        $left = true;  
        $right = true;     

        foreach ($IREZone_array as $sub_IREZone)
        {
            $temp_IREZone = explode(':', $sub_IREZone);
            $IREZone_start = $temp_IREZone[0];
            $IREZone_end = $temp_IREZone[1];

            if ($IREZone_start <= $this->_cor2 && $IREZone_end > $this->_cor1)
            {

                $IREZone_x1 = $cor_x2 + (($IREZone_start - $this->_cor2) * $range);
                $IREZone_x2 = $cor_x2 + (($IREZone_end - $this->_cor2) * $range);

                if ($IREZone_end > $this->_cor2)
                {
                    $IREZone_x2 = $cor_x2;
                    $left = false;
                }
                if ($IREZone_start < $this->_cor1)
                {
                    $IREZone_x1 = $cor_x1;
                    $right = false;           
                }

                for (;$IREZone_y1 + 10 < $IREZone_y2; $IREZone_y1++)
                {
                    if ($right)
                        imageline($im, $IREZone_x1, $IREZone_y1, $IREZone_x1, $IREZone_y1 + 12, $color_Line); // right
                    if ($left)
                        imageline($im, $IREZone_x2, $IREZone_y1, $IREZone_x2, $IREZone_y1 + 12, $color_Line); // left
                    $IREZone_y1 += 15;
                }
                if ($right)    
                    imageline($im, $IREZone_x1, $IREZone_y1, $IREZone_x1, $IREZone_y2, $color_Line); //right
                if ($left)
                    imageline($im, $IREZone_x2, $IREZone_y1, $IREZone_x2, $IREZone_y2, $color_Line); //left
                
                $IREZone_width = $IREZone_x2 - $IREZone_x1;
                $IREZone_middle_x1 =  $IREZone_x1 + $IREZone_width / 2 - (strlen("IREZone") + strlen($IREZone_count)) * $len_word_width / 2 - $len_word_space;
                $IREZone_middle_x2 =  $IREZone_x2 - $IREZone_width / 2 + (strlen("IREZone") + strlen($IREZone_count)) * $len_word_width / 2 + $len_word_space;

                // Lines of len of start/end of arrow
                imageline($im, $IREZone_x1, $IREZone_middle_y, $IREZone_x1 + $len_stright, $IREZone_middle_y + $len_stright, $color_Line);
                imageline($im, $IREZone_x1, $IREZone_middle_y, $IREZone_x1 + $len_stright, $IREZone_middle_y - $len_stright, $color_Line);
                imageline($im, $IREZone_x2, $IREZone_middle_y, $IREZone_x2 - $len_stright, $IREZone_middle_y + $len_stright, $color_Line);
                imageline($im, $IREZone_x2, $IREZone_middle_y, $IREZone_x2 - $len_stright, $IREZone_middle_y - $len_stright, $color_Line);

                imageline($im, $IREZone_x1, $IREZone_middle_y, $IREZone_middle_x1, $IREZone_middle_y, $color_Line);
                imageline($im, $IREZone_middle_x2, $IREZone_middle_y,  $IREZone_x2, $IREZone_middle_y, $color_Line);
                imagefttext($im, $font_size, 0, $IREZone_middle_x1 + $len_word_space, $IREZone_middle_y + $len_stright, $color_Text, $font_file, "IREZone" . $IREZone_count);               
            }
            $IREZone_count++;
        }
        // IREZone end --------------------------------------------------------
  
        imagefttext($im, $font_size, 0, $cor_x1, 800, $color_Text, $font_file, count(array_slice($ri_depth_array, $ri_depth_array_start, $ri_depth_array_end)));
        imagefttext($im, $font_size, 0, $cor_x1, 950, $color_Text, $font_file, $this->_cor1);

        $this->db->close();

        // Output and free from memory
        header('Content-Type: image/jpeg');

        imagejpeg($im);
        imagedestroy($im);
    }

    public function runseq()
    {
        // Create a 600 x 80 image
        $im = imagecreate(600, 100);

        // Get variables
        $pic_5leng = 0;
        $pic_3leng = 0;
        $data_length = 0;

        $a = 0;
        $b = 0;
        $c = 0;
        $d = 0;

        // Settings
        $font_file = "/home/testuser/public_html/fonts/calibri.ttf";

        // Color definition
        $background = imagecolorallocate($im, 255, 255, 255);
        $white = imagecolorallocate($im, 255, 255, 255);
        $color_Text = imagecolorallocate($im, 0, 0, 0);
        $color_Line = imagecolorallocate($im, 120, 0, 20);
        $color_bar1 = imagecolorallocate($im, 180, 30, 30);
        $color_bar1b = imagecolorallocate($im, 142, 24, 24);
        $color_bar2 = imagecolorallocate($im, 255, 180, 200);
        $color_bar2b = imagecolorallocate($im, 255, 150, 150);
        $color_bar3 = imagecolorallocate($im, 255, 235, 235);
        $color_bar1a = imagecolorallocate($im, 255, 215, 215);
        $color_background = imagecolorallocate($im, 255, 245, 245);

        // Lines of start/end codon
        imageline($im, 120, 20, 120, 60, $color_Line);
        imageline($im, 480, 20, 480, 60, $color_Line);
        imageline($im, 40, 52, 55, 52, $color_Line);
        imageline($im, 55, 52, 52, 55, $color_Line);
        imageline($im, 120, 52, 105, 52, $color_Line);
        imageline($im, 105, 52, 108, 55, $color_Line);
        imageline($im, 120, 52, 135, 52, $color_Line);
        imageline($im, 135, 52, 132, 55, $color_Line);
        imageline($im, 480, 52, 465, 52, $color_Line);
        imageline($im, 465, 52, 468, 55, $color_Line);
        imageline($im, 480, 52, 495, 52, $color_Line);
        imageline($im, 495, 52, 492, 55, $color_Line);
        imageline($im, 559, 52, 544, 52, $color_Line);
        imageline($im, 544, 52, 547, 55, $color_Line);


        // Draw the bars
        // Bar 1
        imagerectangle($im, 40, 32, 560, 48, $color_Line);


        // Draw the strings
        // Tags
        imagefttext($im, 10, 0, 40, 15, $color_Text, $font_file, $this->_cor1);
        imagefttext($im, 10, 0, 68, 25, $color_Text, $font_file, $this->_cor2);


        // Output and free from memory
        header('Content-Type: image/jpeg');

        imagejpeg($im);
        imagedestroy($im);
    }
}
