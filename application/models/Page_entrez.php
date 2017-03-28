<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*****
 *** Website Model ***
 *** Model Name: Page_Main
 *** Description: Load the main page contents
 *
 *****/
 
class Page_entrez extends CI_Model { 
	// -------- --------
	// Constructor
	public function __construct()
	{
		parent::__construct();
	}

	// -------- --------
	// Function get_outputs - get the data outputs
	public function get_outputs_search($search_key, $relation_web)
	{
        $result_str = "";

        $sql = sprintf("SELECT DISTINCT * FROM saa_gene WHERE official_symbol LIKE '%%%s%%' OR fullname LIKE '%%%s%%' OR gene_id LIKE '%%%s%%'", $search_key, $search_key, $search_key);

		$query = $this->db->query($sql);
        $temp_count = 0;

		if ($query->num_rows() > 0)
		{
            $temp_output_list = array(
                "<table width=\"50%\" class=\"table\"><tr><th>#</th><th>Official Symbol</th><th>Full Name</th><th>Gene ID</th></tr>"
            );

            // The table contents
            foreach ($query->result() as $item)
            {
                $temp_count++;
                array_push(
                    $temp_output_list,
                    sprintf(
                        "<tr><td>%d</td>
                             <td><a href=\"%sentrez/result/%s\">%s</a></td>
                             <td><a href=\"%sentrez/result/%s\">%s</a></td>
                             <td><a href=\"%sentrez/result/%s\">%s</a></td></tr>",
                        $temp_count, $relation_web, urlencode($item->gene_id), str_ireplace($search_key, "<span style=\"color: #FF0000\">" . $search_key . "</span>", $item->official_symbol), 
                        $relation_web, urlencode($item->gene_id), str_ireplace($search_key, "<span style=\"color: #FF0000\">" . strtolower($search_key) . "</span>", $item->fullname), 
                        $relation_web, urlencode($item->gene_id), str_ireplace($search_key, "<span style=\"color: #FF0000\">" . $search_key . "</span>", $item->gene_id)
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

    public function get_outputs_result($search_key)
    {
        $result_str = "";

        $sql = sprintf("SELECT DISTINCT * FROM saa_gene WHERE official_symbol LIKE '%s' OR fullname LIKE '%s' OR gene_id LIKE '%s'", $search_key, $search_key, $search_key);
        $query = $this->db->query($sql);

        if ($query->num_rows() == 1)
        {
            $row = $query->row(); 

            $table_data = array("ofname" => $row->official_symbol, "fullname" => $row->fullname, "id" => $row->gene_id, "dbxrefs" => $row->related, "alias" => $row->known, "graphic" => $row->graphic);


            $sql = sprintf("SELECT * FROM saa_as WHERE gene = '%s'", $table_data['ofname']);
            $query = $this->db->query($sql);
            $temp_refseq = "<input name=\"rb_refseq\" type=\"radio\" value=\"%s\"/><a href=\"javascript:GetSeq('%s');\">%s</a>";
            $temp_td = "<td>%s</td>";
            $table_data['refseq'] = array("<table class=\"table table-bordered\"><thead><tr><th width=\"160px\">Accession number</th><th width=\"70px\"># IRES</th><th width=\"90px\"># IREZone</th><th width=\"60px\"># uorf</th><th>Description</th></tr></thead><tbody>");

            foreach ($query->result() as $item)
            {
                $descript_array = explode('|', $item->description);
                if (count($descript_array) > 1)
                {
                    $temp_td = "<td rowspan=" . count($descript_array) .">%s</td>";
                }

                array_push($table_data['refseq'], '<tr>');
                array_push($table_data['refseq'], sprintf($temp_td, sprintf($temp_refseq, $item->accession_number, $item->accession_number, $item->accession_number)));
                array_push($table_data['refseq'], sprintf($temp_td, $item->numIRES));
                array_push($table_data['refseq'], sprintf($temp_td, $item->numIREZone));
                array_push($table_data['refseq'], sprintf($temp_td, $item->numuorf));

                if (count($descript_array) == 3)
                    array_push($table_data['refseq'], sprintf("<td>%s</td><tr><td>%s</td></tr><tr><td>%s</td>", $descript_array[0], $descript_array[1], $descript_array[2]));
                else if (count($descript_array) == 2)
                    array_push($table_data['refseq'], sprintf("<td>%s</td><tr><td>%s</td>", $descript_array[0], $descript_array[1]));
                else
                    array_push($table_data['refseq'], sprintf("<td>%s</td>", $item->description));
                array_push($table_data['refseq'], '</tr>');
            }   
            array_push($table_data['refseq'], "</tbody></table>");
            if (count($table_data['refseq']) > 1)
                $table_data['refseq'][2] = str_replace("/>", "checked>", $table_data['refseq'][2]);
            $table_data['refseq'] = implode("", $table_data['refseq']);
            $result_str = $this->load->view('main_entrez_resulttable', $table_data, true);
        }
        else
            $result_str = "There is no result.";
        return $result_str;
    }

}
		
