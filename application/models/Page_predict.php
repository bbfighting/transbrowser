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
class Page_predict extends CI_Model { 
    /* -------- -------- -------- --------
     * Variables
     * ------- -------- -------- --------*/
    private $_db_linker = NULL;
    private $_sql_obj = NULL;
    private $_temp_seq = "";
    private $_pat_separate = 25;
    private $_xmlcounter;
    private $_geneinfo = array();
    private $_genename = array();
    private $_pathinfo = array();
    private $_subname = array();


    public function __construct()
    {
        $this->initialize();
    }


    public function initialize()
    {
        // The global variables
        global $gv_db_link;
        global $gv_sql;

        // Set the reference
        $this->_db_linker = &$gv_db_link;
        $this->_sql_obj = &$gv_sql;        
        $this->_appname = "/home/testuser/public_html/IRES_test/SingleIRES/library/scan_for_matches";
        $this->_input = "/home/testuser/public_html/IRES_test/SingleIRES/patscan_input/input";
        $this->_output = "/home/testuser/public_html/IRES_test/SingleIRES/patscan_output/output";
        $this->_pattern = "/home/testuser/public_html/IRES_test/SingleIRES/patscan_pattern/pattern";
        $this->_xml = "/home/testuser/public_html/IRES_test/SingleIRES/xmlfile/";
        $this->_xmlcounter = 0;

        return $this;
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


    public function CreateInput( /*array*/ $input_content = array(), /*string*/ $target_sc )
    {
        for ($i = 0; $i < count($input_content); $i++)
        {
            $input_content[$i] = preg_replace('/(\s+)$/', '', $input_content[$i]) ? preg_replace('/(\s+)$/', '', $input_content[$i]) : array("");

            if (($input_content[$i][0] == ">" and isset($genename)) or $i == (count($input_content) - 1))
            {
                if (isset($handle))
                {
                    if ($input_content[$i][0] != ">" and $input_content[$i][0] != "")
                    {
                        $this->_temp_seq .= strtoupper($input_content[$i]);
                    }

                    $temp_patscan_seq = ($input_content[$i][0] == ">") ? strrev($this->_temp_seq) . "\n" : strrev($this->_temp_seq);
                    fwrite($handle, $temp_patscan_seq);
                    fclose($handle);

                    $sub_genename = preg_replace('/\s.+/', '', $genename);
                    array_push($this->_genename, $sub_genename);
                    array_push($this->_subname, $genename);
                    $temp_seq = str_replace("T", "U", $this->_temp_seq);
                    $this->_geneinfo[$genename]['sub_genename'] = $sub_genename;
                    $this->_geneinfo[$genename]['seq'] = $temp_seq;
                    $this->_geneinfo[$genename]['seq_len'] = strlen($temp_seq);

                    $this->exec_patscan($genename, $target_sc);

                    $this->_temp_seq = "";
                }
            }  

            if ($input_content[$i][0] == ">")
            {
                $genename = substr($input_content[$i], 1, strlen($input_content[$i]));

                $handle = fopen($this->_input, "w+");
                fwrite($handle, $input_content[$i] . "\n");
                fwrite($handle, $target_sc);
            }
            else if ($input_content[$i][0] != "")
            {
                $this->_temp_seq .= strtoupper($input_content[$i]);
            }          
        }

        $temp = $this->get_output($target_sc);
        return $temp;  
    }


    public function exec_patscan( /*string*/ $temp_genename, /*string*/ $sc )
    {
        $fiveutr_len = $this->_geneinfo[$temp_genename]['seq_len'];
        $output_num = 0;
        $patNum = ceil($fiveutr_len / $this->_pat_separate);

        for ($i = 0; $i < $patNum; $i++)
        {
            $temp_pat = sprintf("%d...%d", (($i == 0) ? 3 : $this->_pat_separate * $i + 1), $this->_pat_separate * ($i + 1));
            $pattern = "r1={au,ua,cg,gc,gu,ug} ^ " . $sc . " " . $temp_pat . " p9=5...6 p10=3...8 r1~p9[1,0,0] 2...5 p1=5...6 0...6 p2=5...6 p8=0...5 p6=5...8 p7=3...5 r1~p6[1,0,0] p4=5...8 p5=3...8 r1~p4[1,0,0] p3=0...2 r1~p2[1,0,0] 0...6 r1~p1[1,0,0] (10...10|(9...9|(8...8|(7...7|(6...6|(5...5|(4...4|(3...3|(2...2|(1...1|0...0))))))))))";

            $handle = fopen($this->_pattern, "w+");
            fwrite($handle, $pattern);
            fclose($handle);  

            $parse = $this->_appname . " -m1 " . $this->_pattern . " < " . $this->_input;

            $output = shell_exec($parse);
            if ($output != "")
            {
                $sub_output = explode(" ", $output);
                array_pop($sub_output);

                preg_match('/\[(\d+),(\d+)\]/', $sub_output[0], $matches);  
                array_shift($sub_output);
                $temp_structure = $this->GetStructure($sub_output);

                $structure = ($i == 0) ? substr($temp_structure, 0, strlen($temp_structure) - 3) : preg_replace('/(\.)+$/', '..........', $temp_structure);
                
                $temp_start = $fiveutr_len - $matches[2] + 1 + 3;
                $temp_end = $fiveutr_len - $matches[1] + 1;
                $diff_len = strlen($temp_structure) - strlen($structure);

                $new_end = $temp_end - $temp_start + 1 - ($diff_len) + 3;

                $this->_geneinfo[$temp_genename][$output_num]['pat'] = $temp_pat;
                $this->_geneinfo[$temp_genename][$output_num]['IREStart'] = -($fiveutr_len - $temp_start + 1);
                $this->_geneinfo[$temp_genename][$output_num]['IRESEnd'] = -($fiveutr_len - ($temp_end - $diff_len + 3) + 1);
                $this->_geneinfo[$temp_genename][$output_num]['structure'] = $structure;
                $this->_geneinfo[$temp_genename][$output_num]['IRESeq'] = substr($this->_geneinfo[$temp_genename]['seq'] , $temp_start - 1, $new_end);
                $this->_geneinfo[$temp_genename][$output_num]['startcoden'] = $temp_end - $fiveutr_len + 1;
                $this->_geneinfo[$temp_genename][$output_num]['PSfile'] = "no constrct";
                $this->_geneinfo[$temp_genename][$output_num]['JPGfile'] = "no constrct";

                $output_num ++;
            }
        }
        $this->_geneinfo[$temp_genename]['IRESNum'] = $output_num;
        $this->CreateXMLOutput($temp_genename, $output_num, $this->_geneinfo[$temp_genename]['sub_genename']);
    }


    public function CreateXMLOutput( /*string*/ $xml_name, /*string*/ $IRES_num, /*string*/ $xml_subname )
    {  
        $filepath = $this->_xml . $xml_subname .".xml";
           
        $handle = fopen($filepath,"w+");
        $this->_pathinfo[$this->_xmlcounter] = $filepath;
        $this->_xmlcounter ++;

        //create xml outfile
        fwrite($handle, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
        fwrite($handle, "<IRESearch>\n");
        fwrite($handle, "\t<InputInfo>\n");
        fwrite($handle, "\t\t<GeneName>");
        fwrite($handle, $xml_name);
        fwrite($handle, "</GeneName>\n");
        fwrite($handle, "\t\t<Sequence>");
        fwrite($handle, $this->_geneinfo[$xml_name]['seq']);
        fwrite($handle, "</Sequence>\n");
        fwrite($handle, "\t</InputInfo>\n");

        fwrite($handle, "\t<OutputInfo>\n");
        for ($i = 0; $i < $IRES_num; $i++)
        {
            $xml_array = $this->_geneinfo[$xml_name][$i];
            fwrite($handle, "\t\t<IRES StartCoden=\"" . $xml_array['startcoden'] . "\" start=\"" . $xml_array['IREStart'] . "\" end=\"" . $xml_array['IRESEnd'] . "\" IRES2AUG=\"" . $xml_array['pat'] . "\" PSfile=\"" . $xml_array['PSfile'] . "\" JPGfile=\"" . $xml_array['JPGfile'] ."\">\n");
            fwrite($handle, "\t\t\t<Sequence>");             
            fwrite($handle, $xml_array['IRESeq']);              
            fwrite($handle, "</Sequence>\n");
            fwrite($handle, "\t\t\t<Structure>");
            fwrite($handle, $xml_array['structure']);
            fwrite($handle, "</Structure>\n");
            fwrite($handle, "\t\t</IRES>\n");            
        }
        fwrite($handle, "\t</OutputInfo>\n");
        fwrite($handle, "</IRESearch>");
        fclose($handle);
    }


    public function GetStructure( /*array*/ $match_seq )
    {
        $replace_array = array(".", "(", ".", "(", ".", "(", ".", ")", "(", ".", ")", ".", ")", ".", ")", ".", "(", ".", ")", ".", ".");
        $temp_structure = "";

        for ($i = 0; $i < 21; $i++)
        {
            for ($j = 0; $j < strlen($match_seq[20 - $i]); $j++)
            {
                $temp_structure .= $replace_array[$i];
            }
        }
        return $temp_structure;
    }


    public function XMLParser()
    {
        $dom = new DOMDocument();
        
        for ($i = 0; $i < count($this->_pathinfo); $i++)
        {
            $dom->load($this->_pathinfo[$i]);
            $element = $dom->documentElement;

            $genename = $dom->getElementsByTagName('GeneName');
        }   

    }


    public function get_output( /*string*/ $sc )
    {
        if (count($this->_genename) == 1)
        {
            $url = sprintf("%spredict/plot/%s", WEBSITE_PATH, $this->_genename[0]);
            header("Location:" . $url);
        }
        else if (count($this->_genename) > 1)
        {
            $radio_gene = "<tr><td><a href=\"%spredict/plot/%s\">&nbsp;&nbsp;&nbsp;%s</a></td></tr>";
            $temp_output_list = array("<table class=\"table table-bordered\"><thead><tr><th>Input sequence name(s)</th></tr></thead><tbody>");

            // The table contents
            for ($i = 0; $i < count($this->_genename); $i++)
            {
                array_push($temp_output_list, sprintf($radio_gene, WEBSITE_PATH, $this->_genename[$i], $this->_subname[$i], $this->_genename[$i]));
            }   
            array_push($temp_output_list, "</tbody></table>");
            array_push($temp_output_list, "<div>
                                            <button align=\"left\" type=\"button\" class=\"btn-primary\" onclick=\"javascript:history.go(-1)\">go back</button>                                                   
                                           </div>");

            // Outputs
            $result_str = implode("", $temp_output_list);
        }
        else
            $result_str = "<h6>There is no results.</h6></br>" . "<div align=\"left\"><button type=\"button\" class=\"btn-primary\" onclick=\"javascript:history.go(-1)\">go back</button></div>";
        return $result_str;
    }    
}