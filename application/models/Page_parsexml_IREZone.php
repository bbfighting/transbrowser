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
class Page_parsexml extends CI_Model { 
    /* -------- -------- -------- --------
     * Variables
     * ------- -------- -------- --------*/
    private $_xml_dir;
    private $_const_diff;
    private $_IREZone_size;
    private $_separator;


    public function __construct()
    {
        $this->initialize();
    }


    public function initialize()
    {
        $this->_xml_dir = "xml/";
        $this->_const_diff = 25;
        $this->_IREZone_size = 3;
        $this->_pat_separate = 25;
        $this->_separator = 50;
    }
    public function var_dump_to_string($var)
    {
        $output = "<pre>";
        $this->_var_dump_to_string($var, $output);
        $output .= "</pre>";
        return $output;
    }

    public function _var_dump_to_string($var, &$output, $prefix = "")
    {
        foreach ($var as $key => $value)
        {
            if (is_array($value))
            {
                $output .= $prefix . $key . ": \n";
                $this->_var_dump_to_string($value,$output, "  ". $prefix);
            } 
            else
            {
                $output .= $prefix . $key . ": " . $value . "\n";
            }
        }
    }


    public function get_outputs($species = "", $target_query = "", $method = "predict")
    {
        $sub_IRES_table = array();
        $IRESxml_array = array("IRES_str" => array(), "IREZone_str" => array());
        $img_src = "";        
        $py_path = ($species == "hs") ? "pre" : "mouse";

        if ($method == "predict")
            $xmlpath = sprintf("/home/testuser/public_html/IRES_test/SingleIRES/xmlfile/%s.xml", $target_query);
        else
            $xmlpath = sprintf("/home/testuser/public_html/temp_web/other/%s_py/IRESdataset/%s_five/xmlfile/%s.xml", $py_path, $species, $target_query);

        if (file_exists($xmlpath))
        {
            $IREZoneinfo_all = "";
            $dom = new DOMDocument();
            $dom->load($xmlpath);

            $IRESearch = simplexml_import_dom($dom);

            $table_data['GeneName'] = $IRESearch->InputInfo->GeneName;
            $Sequence = $IRESearch->InputInfo->Sequence;
            $table_data['Sequence'] = $Sequence;
            $table_data['SC'] = strtoupper("aug");
            $seq_len = strlen($Sequence);
            $table_data['cor1'] = -$seq_len;

            $deviration_start = 0;
            $IREZone_count = 0;

            $IRES = $IRESearch->OutputInfo->IRES;
            $IRES_count = count($IRES);
                    
            for ($i = 0; $i < $IRES_count; $i ++)
            {
                $StartCoden = ($IRES[$i]->attributes()['StartCoden'] == 1) ? "+1" : $IRES[$i]->attributes()['StartCoden'];
                $IREZoneinfo_table['StartCoden'] = $StartCoden;

                $temp_start = (int)$IRES[$i]->attributes()['start'];
                $temp_end = (int)$IRES[$i]->attributes()['end'];
                array_push($IRESxml_array['IRES_str'], sprintf("%s:%s", $temp_start, $temp_end));

                if ($deviration_start <= $temp_end)
                {
                    $deviation = ($deviration_start - $temp_start) + ($deviration_end - $temp_end);
                    array_push($sub_IRES_table, sprintf("<tr><td>%s</td><td>%s</td>", $temp_start, $temp_end));
                    array_push($sub_IRES_table, sprintf("<td>%s</td>", "$deviation = ($deviration_start - $temp_start) + ($deviration_end - $temp_end)"));
                    $deviration_start = min($temp_start, $deviration_start);

                    if ($deviration_start >= $temp_start && $deviation > $this->_const_diff)
                    {
                        $deviration_end = $temp_end;
                        $IREZone_count++;
                    }

                    array_push($sub_IRES_table, sprintf("<td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", 
                        $deviration_start, $IREZone_end, $deviration_start, $deviration_end, $IREZone_count));

                    if ($i == $IRES_count - 1 and $IREZone_count >= $this->_IREZone_size)
                    {
                        $seq_start = $seq_len + $deviration_start;
                        $seq_end = $seq_len + $IREZone_end - $seq_start + 1;
                        $IREZone_seq = substr($Sequence, $seq_start, $seq_end);

                        $IREZoneinfo_table['IREZoneSTART'] = $deviration_start;
                        $IREZoneinfo_table['IREZoneEND'] = $IREZone_end;
                        $IREZoneinfo_table['IRESCount'] = $IREZone_count;
                        $IREZoneinfo_table['IREZone_Sequence'] = $IREZone_seq;

                        $IREZoneinfo_table['IREZone_table'] = implode($sub_IRES_table, '');
                        $IREZoneinfo_all .= $this->load->view('main_IREZoneinfo_table', $IREZoneinfo_table, true);
                        $sub_IRES_table = array();
                        array_push($IRESxml_array['IREZone_str'], sprintf("%s:%s", $IREZoneinfo_table['IREZoneSTART'], $IREZoneinfo_table['IREZoneEND']));
                    }
                }
                else
                {
                    if ($IREZone_count >= $this->_IREZone_size)
                    {
                        $seq_start = $seq_len + $deviration_start;
                        $seq_end = $seq_len + $IREZone_end - $seq_start + 1;
                        $IREZone_seq = substr($Sequence, $seq_start, $seq_end);
                        $IREZoneinfo_table['IREZoneSTART'] = $deviration_start;
                        $IREZoneinfo_table['IREZoneEND'] = $IREZone_end;
                        $IREZoneinfo_table['IRESCount'] = $IREZone_count;
                        $IREZoneinfo_table['IREZone_Sequence'] = $IREZone_seq;
                        $IREZoneinfo_table['IREZone_table'] = implode($sub_IRES_table, '');
                        $IREZoneinfo_all .= $this->load->view('main_IREZoneinfo_table', $IREZoneinfo_table, true);
                        array_push($IRESxml_array['IREZone_str'], sprintf("%s:%s", $IREZoneinfo_table['IREZoneSTART'], $IREZoneinfo_table['IREZoneEND']));
                        $sub_IRES_table = array();
                    }
                    $IREZone_count = 1;
                    $deviration_start = $temp_start;
                    $deviration_end = $temp_end;
                    $IREZone_end = $temp_end;
                    array_push($sub_IRES_table, sprintf("<tr style=\"background-color:#FAEEF2\"><td>%s</td><td>%s</td>", $temp_start, $temp_end));
                    array_push($sub_IRES_table, sprintf("<td></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", 
                        $deviration_start, $IREZone_end, $deviration_start, $deviration_end, $IREZone_count));
                }
            }
            $table_data['outputinfo_table'] = $IREZoneinfo_all;

            $table_data['imgkey'] = $target_query;
            $table_data['imgspecies'] = $species;

            $ribosome_choose = "        <div class=\"form-inline\">
            <label for=\"comment\">Choose transcription : </label>
            <input name=\"transcription\" type=\"checkbox\" value=\"G1\" checked> G1
            <input name=\"transcription\" type=\"checkbox\" value=\"G2\" checked> G2
            <input name=\"transcription\" type=\"checkbox\" value=\"S1\" checked> S1
            <input name=\"transcription\" type=\"checkbox\" value=\"S2\" checked> S2
            <input name=\"transcription\" type=\"checkbox\" value=\"M1\" checked> M1
            <input name=\"transcription\" type=\"checkbox\" value=\"M2\" checked> M2
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"all\" id=\"all\" value=\"all\" checked> choose all or not
        </div>";

            if ($method == "predict")
            {
                $table_data['ribosome_choose'] = "";
                $table_data['method'] = "predict";
                $this->CreateXMLOutput($target_query, $table_data['GeneName'], $table_data['Sequence'], implode($IRESxml_array['IRES_str'], ";"), implode($IRESxml_array['IREZone_str'], ";"));
            }
            else
            {
                $table_data['ribosome_choose'] = $ribosome_choose;
                $table_data['method'] = "search";
            }
            $result_str = $this->load->view('main_plot', $table_data, true);
        }
        else
        {
            $result_str = "No Sequence and IRES!<div align=\"left\"><button type=\"button\" class=\"btn-primary\" onclick=\"javascript:history.go(-1)\">go back</button></div>";
        }
        return $result_str;
    }


    public function separator( /*string*/ $temp_seq, /*string*/ $temp_cds = "" )
    {
        $sequence = "";
        $count_50 = (strlen($temp_seq) % $this->_separator == 0) ? (strlen($temp_seq) / $this->_separator) - 1 : intval(strlen($temp_seq) / $this->_separator);

        for($i = 0; $i < $count_50; $i++)
        {
            $sequence .= substr($temp_seq, $i * $this->_separator, $this->_separator) . "<br>";
        }

        $sequence = 
            sprintf(
                "<div style=\"font-family:'Courier New', Courier, monospace\">%s<span style=\"color: #FF0000\">%s</span></div>",
                strtoupper($sequence . substr($temp_seq, $count_50 * $this->_separator)), strtoupper($temp_cds)
        );  
        return $sequence;
    }


    public function CreateXMLOutput($target_query, $genemame, $sequence, $IRES_str, $IREZone_str)
    {  
        $filepath = sprintf("/home/testuser/public_html/transbrowser/other/IRESxml/%s_IRES_str.xml", $target_query);
           
        $handle = fopen($filepath,"w+");

        //create xml outfile
        fwrite($handle, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
        fwrite($handle, "<IRESearch>\n");
        fwrite($handle, "\t<InputInfo>\n");
        fwrite($handle, "\t\t<GeneName>");
        fwrite($handle, $genemame);
        fwrite($handle, "</GeneName>\n");
        fwrite($handle, "\t\t<Sequence>");
        fwrite($handle, $sequence);
        fwrite($handle, "</Sequence>\n");
        fwrite($handle, "\t\t<IRES_str>");
        fwrite($handle, $IRES_str);
        fwrite($handle, "</IRES_str>\n");
        fwrite($handle, "\t\t<IREZone_str>");
        fwrite($handle, $IREZone_str);
        fwrite($handle, "</IREZone_str>\n");
        fwrite($handle, "\t</InputInfo>\n");
        fwrite($handle, "</IRESearch>");
        fclose($handle);
    }
}