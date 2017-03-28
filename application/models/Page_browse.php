<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*****
 *** Website Model ***
 *** Model Name: Page_Main
 *** Description: Load the main page contents
 *
 *****/
 
class Page_browse extends CI_Model { 
	// -------- --------
	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

    public function get_outputs_result($species, $search_key)
    {
        $tax = ($species == "hs") ? "Homo sapiens" : "Mus musculus";

        $result_str = "<table class=\"table table-bordered\">    
    <thead>
      <tr style=\"background-color:#000000;color:#ffffff;style=\"border:1px #000000 solid;\"\">
        <th>Official Symbol</th>
        <th>5’UTR of the mRNA Transcript</th>
        <th>Sequence</th>
        <th>Length of Sequence</th>
        <th># of Predicted IRES</th>
        <th># of Predicted IREZones</th>
        <th># of uORFs</th>
        <th>Detail</th>
      </tr>
    </thead>
    <tbody>";

        $sql = sprintf("SELECT DISTINCT * FROM saa_gene_%s WHERE official_symbol LIKE '%s%%'", $species, $search_key);
        $query = $this->db->query($sql);
        $count_strip = 1;

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $table_data = array("ofname" => $row->official_symbol, "id" => $row->gene_id);           

                $sql = sprintf("SELECT * FROM saa_as_%s WHERE gene = '%s'", $species, $table_data['ofname']);
                $query = $this->db->query($sql);
                if ($query->num_rows() > 0)
                {
                    $table_data['color'] = ($count_strip % 2 == 1) ? "#d5f0f2" : "#ffffff";
                    $count_strip += 1;
                    $table_data['num_nm'] = ($query->num_rows() != 0) ? $query->num_rows() : 1;
                    $table_data['refseq'] = array();

                    foreach ($query->result() as $item)
                    {
                        $temp_td = sprintf("<td style=\"border:1px #000000 solid;\">%s</td><td style=\"border:1px #000000 solid;\"><a href=\"javascript:GetSeq('%s', '%s');\" class=\"fa fa-file-text-o\"></a></td><td style=\"border:1px #000000 solid;\">%s</td><td style=\"border:1px #000000 solid;\">%s</td><td style=\"border:1px #000000 solid;\">%s</td><td style=\"border:1px #000000 solid;\">%s</td><td style=\"border:1px #000000 solid;\"><a href=\"%ssearch/plot/%s/%s\" class=\"fa fa-search\"></a></td>", $item->accession_number,  $species, $item->accession_number, $item->seq_len, $item->numIRES, $item->numIREZone, $item->numuorf, WEBSITE_PATH, $species, $item->accession_number);
                        array_push($table_data['refseq'], $temp_td);
                    }


                    $table_data['refseq'] = implode(sprintf("</tr><tr style=\"background-color:%s\">", $table_data['color']), $table_data['refseq']);
                    $result_str .= $this->load->view('main_browse_resulttable', $table_data, true);
                }
            }
            $result_str .= "</tbody></table>                    <div align=\"left\">
                        <button type=\"button\" class=\"btn-primary\" onclick=\"javascript:history.go(-1)\">go back</button>                                                   
                    </div>     ";
        }
        else
            $result_str = "</h6>There is no result.<h6></br>" . "<div align=\"left\"><button type=\"button\" class=\"btn-primary\" onclick=\"javascript:history.go(-1)\">go back</button></div>";
        return $result_str;
    }
}