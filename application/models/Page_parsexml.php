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
        $temp_seq = "";
        $py_path = ($species == "hs") ? "pre" : "mouse";
        $IREZone_table_count = 0;

        if ($method == "predict")
        {
            $xmlpath = sprintf("/home/testuser/public_html/IRES_test/SingleIRES/xmlfile/%s.xml", $target_query);
            $table_data['method_th_name'] = "Sequence";
            $table_data['method_th_nm'] = "";
            $table_data['method_th_lenseq'] = "Length of Sequence";
            $table_data['method_th_seq'] = "Sequence";
        }
        else
        {
            $xmlpath = sprintf("/home/testuser/public_html/temp_web/other/%s_py/IRESdataset/%s_five/xmlfile/%s.xml", $py_path, $species, $target_query);
            $table_data['method_th_name'] = "Gene";
            $table_data['method_th_nm'] = sprintf("<tr><td style=\"width:15%%;\"><strong>mRNA Transcript</strong></td><td><div style=\"width:80%%;font-family:'Courier New', Courier, monospace\">%s</div></td></tr>", $target_query);
            $table_data['method_th_lenseq'] = "Length of 5’UTR Sequence";
            $table_data['method_th_seq'] = "5’UTR Sequence";
        }

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
            $table_data['seq_len'] = $seq_len;
            $table_data['cor1'] = -$seq_len;

            $deviration_start = 0;
            $IREZone_count = 0;
            $IREZone_num = 0;

            $IRES = $IRESearch->OutputInfo->IRES;
            $IRES_count = count($IRES);
            $is_end = false;

            $table_data['outputinfo_table'] = "";

            if ($IRES_count != 0)
            {
                $table_data['outputinfo_table'] = "<table class=\"table table-bordered\" style=\"word-break:break-all\"><tr style=\"background-color:#000000;color:#ffffff;\"><td>Predicted IRES</td><td>Start Position</td><td>End Position</td><td>Predicted IREZone</td></tr>";
            }

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
                        $is_end = true;
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
                        $IREZone_num += 1;
                        $temp_seq = str_replace("x", "IREZone " . $IREZone_num, $temp_seq);
                    }

                    $IREZone_table_count++;
                    $table_data['outputinfo_table'] .= $temp_seq;
                    $temp_seq = "";
                    $IREZone_count = 1;
                    $deviration_start = $temp_start;
                    $deviration_end = $temp_end;
                    $IREZone_end = $temp_end;
                    array_push($sub_IRES_table, sprintf("<tr style=\"background-color:#FAEEF2\"><td>%s</td><td>%s</td>", $temp_start, $temp_end));
                    array_push($sub_IRES_table, sprintf("<td></td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>", 
                        $deviration_start, $IREZone_end, $deviration_start, $deviration_end, $IREZone_count));

                }
                if ($IREZone_table_count % 2 == 0)
                    $IREZone_table_color = "";
                else
                    $IREZone_table_color = " style=\"background-color:#eeeeee;\"";
                $temp_seq .= sprintf("<tr%s><td style=\"width: 15%%;\">IRES %d</td><td>%s</td><td>%s</td><td>x</td></tr>", $IREZone_table_color, $i + 1, $temp_start, $temp_end);
                // $table_data['outputinfo_table'] .= sprintf("<tr><td>%d</td><td>%s</td><td>%s</td><td>%d</td></tr>", $i + 1, $temp_start, $temp_end, $IREZone_count);
            }
            if ($is_end)
            {
                $IREZone_num += 1;
                $temp_seq = str_replace("x", "IREZone " . $IREZone_num, $temp_seq);
            }
            if ($IRES_count != 0)
            {
                $table_data['outputinfo_table'] .= $temp_seq;
                $table_data['outputinfo_table'] .= "</table>";
            }

            if ($method != "predict")
            {
                $sql = sprintf("SELECT * FROM saa_IRES_IREZone_uorf_%s WHERE accession_number LIKE '%s'", $species, $target_query);
                $query = $this->db->query($sql);
                $row = $query->row();
                $uorfall = $row->uorf;

                if ($uorfall != "")
                {
                    $table_data['outputinfo_table'] .= "<table class=\"table table-bordered table-striped\" style=\"word-break:break-all\"><tr style=\"background-color:#000000;color:#ffffff;\"><td>uORF</td><td>Start Position</td><td>End Position</td></tr>";
                    $uorf_array = explode(";", $uorfall);

                    for($i = 0; $i < count($uorf_array); $i++)
                    {
                        $uorf_pos_array = explode("~", $uorf_array[$i]);
                        $table_data['outputinfo_table'] .= sprintf("<tr><td>uORF %d</td><td>%s</td><td>%s</td></tr>", $i + 1, $uorf_pos_array[0], $uorf_pos_array[1]);
                    }
                    $table_data['outputinfo_table'] .= "</table>";
                }
            }

            $table_data['imgkey'] = $target_query;
            $table_data['imgspecies'] = $species;

            if ($species == "hs")
            {
                $ribosome_choose = "<label for=\"comment\">Show Ribosome Profiling Data: : </label><table class=\"table table-bordered\" style=\"word-break:break-all\">
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"G1_1\" checked>SRR970538(G1_1)</td>
        <td><p><strong><a href=\"http://www.ncbi.nlm.nih.gov/sra/SRX345302[accn]\" target=\"_blank\">SRX345302</a>: Ribosome profile of G1 synchronized Hela cells</strong><br />1 ILLUMINA (Illumina HiSeq 2000) run: 221.1M spots, 11.3G bases, 7.7Gb downloads</p></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"G1_2\" checked>SRR970490(G1_2)</td>
        <td><p><strong><a href=\"http://www.ncbi.nlm.nih.gov/sra/SRX345301[accn]\" target=\"_blank\">SRX345301</a>: Ribosome profile of G1 synchronized Hela cells</strong><br />1 ILLUMINA (Illumina HiSeq 2000) run: 201.9M spots, 10.3G bases, 6.8Gb downloads</p></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"S1\" checked>SRR970565(S1)</td>
        <td><p><strong><a href=\"http://www.ncbi.nlm.nih.gov/sra/SRX345306[accn]\" target=\"_blank\">SRX345306</a>: Ribosome profile of S-phase Hela cells</strong><br />1 ILLUMINA (Illumina HiSeq 2000) run: 194.5M spots, 9.9G bases, 6.3Gb downloads</p></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"S2\" checked>SRR970561(S2)</td>
        <td><p><strong><a href=\"http://www.ncbi.nlm.nih.gov/sra/SRX345305[accn]\" target=\"_blank\">SRX345305</a>: Ribosome profile of S-phase Hela cells</strong><br />1 ILLUMINA (Illumina HiSeq 2000) run: 110.4M spots, 5.6G bases, 4Gb downloads</p></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"M1\" checked>SRR970587(M1)</td>
        <td><p><strong><a href=\"http://www.ncbi.nlm.nih.gov/sra/SRX345309[accn]\" target=\"_blank\">SRX345309</a>: Ribosome profile of mitotic Hela cells</strong><br />1 ILLUMINA (Illumina HiSeq 2000) run: 91.7M spots, 4.7G bases, 3.2Gb downloads</p></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"M2\" checked>SRR970588(M2)</td>
        <td><p><strong><a href=\"http://www.ncbi.nlm.nih.gov/sra/SRX345310[accn]\" target=\"_blank\">SRX345310</a>: Ribosome profile of mitotic Hela cells</strong><br />1 ILLUMINA (Illumina HiSeq 2000) run: 218.7M spots, 11.2G bases, 7.4Gb downloads</p></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"H_Rep1\" checked>HRep1</td>
        <td></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"H_Rep2\" checked>HRep2</td>
        <td></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"N_Rep1\" checked>NRep1</td>
        <td></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"N_Rep2\" checked>NRep2</td>
        <td></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"Ribo_GFP\" checked>Ribo_GFP</td>
        <td></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"Ribo_GFPQ1\" checked>Ribo_GFPQ1</td>
        <td></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"Ribo_nEGF\" checked>Ribo_nEGF</td>
        <td></td>
    </tr>
    <tr>
        <td><input name=\"transcription\" type=\"checkbox\" value=\"Ribo_pEGF\" checked>Ribo_pEGF</td>
        <td></td>
    </tr>
    </table>";
            }
            else
            {
                $ribosome_choose = "<label for=\"comment\">Choose transcription : </label><table class=\"table table-bordered\" style=\"word-break:break-all\"><tr><td><input name=\"transcription\" type=\"checkbox\" value=\"SRR3208406\" checked> SRR3208406</td><td>
    <p><strong><a href=\"http://www.ncbi.nlm.nih.gov/sra/SRX1616308[accn]\" target=\"_blank\">SRX1616308</a>: Ribosome profiling of MEF cells</strong><br />1 ILLUMINA (Illumina HiSeq 2000) run: 23.5M spots, 729.8M bases, 499.6Mb downloads</p></td></tr></table>";             
            }

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