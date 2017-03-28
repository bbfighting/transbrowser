<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*****
 *** Website Model ***
 *** Model Name: Page_Main
 *** Description: Load the main page contents
 *
 *****/
 
class Page_search extends CI_Model { 
	// -------- --------
	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

	// -------- --------
	// Function get_outputs - get the data outputs
	public function get_outputs_search($search_key, $relation_web, $species)
	{
        $result_str = "";
        $sql = sprintf("SELECT DISTINCT * FROM saa_gene_%s WHERE official_symbol LIKE '%%%s%%' OR fullname LIKE '%%%s%%' OR known LIKE '%%%s%%'", $species, $search_key, $search_key, $search_key);

		$query = $this->db->query($sql);
        $temp_count = 0;

		if ($query->num_rows() > 0)
		{
            $temp_output_list = array(
                "<table width=\"50%\" class=\"table\"><tr><th>#</th><th>Official Symbol</th><th>Full Name</th><th>Alias</th></tr>"
            );

            // The table contents
            foreach ($query->result() as $item)
            {
                $temp_count++;
                array_push(
                    $temp_output_list,
                    sprintf(
                        "<tr><td>%d</td>
                             <td><a href=\"%ssearch/result/%s/%s\">%s</a></td>
                             <td><a href=\"%ssearch/result/%s/%s\">%s</a></td>
                             <td><a href=\"%ssearch/result/%s/%s\">%s</a></td></tr>",
                        $temp_count, $relation_web, $species, urlencode($item->official_symbol), str_ireplace($search_key, "<span style=\"color: #FF0000\">" . $search_key . "</span>", $item->official_symbol), 
                        $relation_web, $species, urlencode($item->fullname), str_ireplace($search_key, "<span style=\"color: #FF0000\">" . strtolower($search_key) . "</span>", $item->fullname), 
                        $relation_web, $species, urlencode($item->known), str_ireplace($search_key, "<span style=\"color: #FF0000\">" . $search_key . "</span>", $item->known)
                    )
                );
            }

            // The table tail
            array_push($temp_output_list, "</table>");

            // Outputs
            $result_str = sprintf("<h6>There are %d results matching '%s'.</h6></br>", $query->num_rows(), $search_key);
            $result_str .= implode("", $temp_output_list);
		}
        else
            $result_str = "<h6>There is no result.</h6></br>";
		return $result_str;
	}

    public function get_outputs_result($species, $search_key)
    {
        $tax = ($species == "hs") ? "Homo sapiens" : "Mus musculus";
        $result_str = "";

        $sql = sprintf("SELECT DISTINCT * FROM saa_gene_%s WHERE official_symbol LIKE '%s' OR fullname LIKE '%s' OR known LIKE '%s'", $species, $search_key, $search_key, $search_key);
        $query = $this->db->query($sql);

        if ($query->num_rows() == 1)
        {
            $row = $query->row(); 

            $table_data = array("tax" => $tax, "ofname" => $row->official_symbol, "fullname" => $row->fullname, "id" => $row->gene_id, "alias" => $row->known, "summary" => $row->summary, "graphic" => "");
            if ($row->graphic != "no")
                $table_data['graphic'] = sprintf("<a href=\"http://www.ncbi.nlm.nih.gov%s\" target=\"_blank\">[NCBI Graphics]</a>", $row->graphic);

            $sql = sprintf("SELECT * FROM saa_as_%s WHERE gene = '%s'", $species, $table_data['ofname']);
            $query = $this->db->query($sql);
            $table_data['refseq'] = array("<table class=\"table table-bordered\" style=\"border:1px #000000 solid;\">    
    <thead>
      <tr style=\"background-color:#e2e2e3\">
        <th style=\"width:250px;border:1px #000000 solid;\">5’UTR of the mRNA Transcript</th>
        <th style=\"width:100px;border:1px #000000 solid;\">Sequence</th>
        <th style=\"width:100px;border:1px #000000 solid;\">Length of Sequence</th>
        <th style=\"border:1px #000000 solid;\"># of Predicted IRES</th>
        <th style=\"border:1px #000000 solid;\"># of Predicted IREZones</th>
        <th style=\"border:1px #000000 solid;\"># of uORFs</th>
        <th style=\"width:100px;border:1px #000000 solid;\">Detail</th>
      </tr>
    </thead>
    <tbody>");

            foreach ($query->result() as $item)
            {
                $temp_td = sprintf("<td style=\"border:1px #000000 solid;\">%s</td><td style=\"border:1px #000000 solid;\"><a href=\"javascript:GetSeq('%s', '%s');\" class=\"fa fa-file-text-o\"></a></td><td style=\"border:1px #000000 solid;\">%s</td><td style=\"border:1px #000000 solid;\">%s</td><td style=\"border:1px #000000 solid;\">%s</td><td style=\"border:1px #000000 solid;\">%s</td><td style=\"border:1px #000000 solid;\"><a href=\"%ssearch/plot/%s/%s\" class=\"fa fa-search\"></a></td>", $item->accession_number, $species, $item->accession_number, $item->seq_len, $item->numIRES, $item->numIREZone, $item->numuorf, WEBSITE_PATH, $species, $item->accession_number);
                array_push($table_data['refseq'], $temp_td);
            }   
            $table_data['refseq'] = implode("</tr><tr style=\"background-color:%s\">", $table_data['refseq']);
            $table_data['refseq'] .= "</tbody></table>"; 
            $result_str = $this->load->view('main_search_resulttable', $table_data, true);
        }
        else
            $result_str = "</h6>There is no result.<h6></br>" . "<div align=\"left\"><button type=\"button\" class=\"btn-primary\" onclick=\"javascript:history.go(-1)\">go back</button></div>";
        return $result_str;
    }
}