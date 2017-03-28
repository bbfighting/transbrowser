<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*****
 *** Website Model ***
 *** Model Name: Page_Main
 *** Description: Load the main page contents
 *
 *****/
 
class Page_getseq extends CI_Model { 
	// -------- --------
	// Constructor
	public function __construct()
	{
		parent::__construct();
	}


	public function get_outputs($species, $key)
	{
        $pydir = ($species == "hs") ? "pre" : $species;
        $path = sprintf("/home/testuser/public_html/temp_web/other/%s_py/IRESdataset/%s_five/xmlfile/%s.xml", $pydir, $species, $key);
        if (file_exists($path))
        {
            $dom = new DOMDocument();
            $dom->load($path);

            $IRESearch = simplexml_import_dom($dom);
            $GeneName = $IRESearch->InputInfo->GeneName;
            $Sequence = $IRESearch->InputInfo->Sequence;

            $output_seq = $this->_separator($Sequence, strtoupper("aug"));
        }
        else
            $output_seq = "No Sequence";
        return $output_seq;
    }


    public function get_ex()
    {
        $seq1 = ">Homo sapiens jun proto-oncogene (JUN), 5UTR mRNA</br>" . $this->_separator("GACAUCAUGGGCUAUUUUUAGGGGUUGACUGGUAGCAGAUAAGUGUUGAGCUCGGGCUGGAUAAGGGCUCAGAGUUGCACUGAGUGUGGCUGAAGCAGCGAGGCGGGAGUGGAGGUGCGCGGAGUCAGGCAGACAGACAGACACAGCCAGCCAGCCAGGUCGGCAGUAUAGUCCGAACUGCAAAUCUUAUUUUCUUUUCACCUUCUCUCUAACUGCCCAGAGCUAGCGCCUGUGGCUCCCGGGCUGGUGUUUCGGGAGUGUCCAGAGAGCCUGGUCUCCAGCCGCCCCCGGGAGGAGAGCCCUGCUGCCCAGGCGCUGUUGACAGCGGCGGAAAGCAGCGGUACCCACGCGCCCGCCGGGGGAAGUCGGCGAGCGGCUGCAGCAGCAAAGAACUUUCCCGGCUGGGAGGACCGGAGACAAGUGGCAGAGUCCCGGAGCGAACUUUUGCAAGCCUUUCCUGCGUCUUAGGCUUCUCCACGGCGGUAAAGACCAGAAGGCGGCGGAGAGCCACGCAAGAGAAGAAGGACGUGCGCUCAGCUUCGCUCGCACCGGUUGUUGAACUUGGGCGAGCGCGAGCCGCGGCUGCCGGGCGCCCCCUCCCCCUAGCAGCGGAGGAGGGGACAAGUCGUCGGAGUCCGGGCGGCCAAGACCCGCCGCCGGCCGGCCACUGCAGGGUCCGCACUGAUCCGCUCCGCGGGGAGAGCCGCUGCUCUGGGAAGUGAGUUCGCCUGCGGACUCCGAGGAACCGCUGCGCCCGAAGAGCGCUCAGUGAGUGACCGCGACUUUUCAAAGCCGGGUAGCGCGCGCGAGUCGACAAGUAAGAGUGCGGGAGGCAUCUUAAUUAACCCUGCGCUCCCUGGAGCGAGCUGGUGAGGAGGGCGCAGCGGGGACGACAGCCAGCGGGUGCGUGCGCUCUUAGAGAAACUUUCCCUGUCAAAGGCUCCGGGGGGCGCGGGUGUCCCCCGCUUGCCAGAGCCCUGUUGCGGCCCCGAAACUUGUGCGCGCAGCCCAAACUAACCUCACGUGAAGUGACGGACUGUUCUAUGACUGCAAAGAUG", strtoupper(""));
        $seq2 = "</br>>NM_001286968</br>" . $this->_separator("GAGGCUAUAAGAGGGCGCACAAGUGGCGCGGCGCAGGAGCCGCCGCCAGUGGAGGGCCGGGCGCUGCGGCCGCGGCCGGGGCGGGCGCAGGGCCGAGCGGACGGGGGGGCGCGGGCCCCCCGGGAGGCCGCGGCCACUCCCCCCCGGGCCGGCGCGGCGGGGGAGGCGGAGGAUGGAAACACCCUUCUACGGCGAUGAGGCGCUGAGCGGCCUGGGCGGCGGCGCCAGUGGCAGCGGCGGCAGCUUCGCGUCCCCGGGCCGCUUGUUCCCCGGGGCGCCCCCGACGGCCGCGGCCGGCAGCAUG", strtoupper(""));

        return $seq1 . $seq2;
    }


    private function _separator( /*string*/ $temp_seq = "", /*string*/ $temp_cds = "" )
    {
        $separator_num = 50;
        $sequence = "";
        $count_50 = (strlen($temp_seq) % $separator_num == 0) ? (strlen($temp_seq) / $separator_num) - 1 : intval(strlen($temp_seq) / $separator_num);

        for($i = 0; $i < $count_50; $i++)
        {
            $sequence .= substr($temp_seq, $i * $separator_num, $separator_num) . "<br>";
        }

        $sequence = 
            sprintf(
                "<div style=\"font-family:'Courier New', Courier, monospace\">%s<span style=\"color: #FF0000\">%s</span></div>",
                strtoupper($sequence . substr($temp_seq, $count_50 * $separator_num)), $temp_cds
        );  

        return $sequence;
    }
}