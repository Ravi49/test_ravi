<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/tcpdf/tcpdf.php'); // Include TCPDF library

class Book_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function saveData($data)
    {
    	ini_set('display_errors', '1');
		ini_set('display_startup_errors', '1');
		error_reporting(E_ALL);
        //$this->load->library('Pdf');
        $finalArray = array();
        $result = '';

        if (!empty($data)) {
            foreach ($data['book_name'] as $key => $value) {
                $form_data = array(
                    'book_name' => trim($value),
                    'book_title' => trim($data['book_title'][$key]),
                    'book_author' => trim($data['book_author'][$key]),
                    'book_contents' => trim($data['book_contents'][$key])
                );
                $finalArray[] = $form_data;
            }

            // Insert data into the database
            $result = $this->db->insert_batch('book', $finalArray);

            // If data is successfully inserted, generate PDF for each entry
            if ($result) {
                // Create new PDF document
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // Set document information
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Bribooks');
                $pdf->SetTitle('KDP Book');
                $pdf->SetSubject('Kindle Direct Publishing Book');
                $pdf->SetKeywords('KDP, Kindle Direct Publishing, PDF');

                // Set margins
                $pdf->SetMargins(10, 10, 10);

                // Loop to generate 10 pages
                for ($i = 0; $i < count($finalArray); $i++) {
				    $entry = $finalArray[$i]; // Get the current entry from the array

				    // Add a new page
				    $pdf->AddPage();

				    // Set content for the page
				    $pdf->SetFont('helvetica', '', 12);
				    // Add book_name and book_title
				    $pdf->MultiCell(0, 10, "Book Name: {$entry['book_name']}");
				    $pdf->MultiCell(0, 10, "Book Title: {$entry['book_title']}");
				    $pdf->MultiCell(0, 10, "Book Author: {$entry['book_author']}");
				    $pdf->MultiCell(0, 10, "Book Contents: {$entry['book_contents']}");
				   
				    $pdf->Image('assets/pdf/image_demo.png', 15, 50, 180, 120, 'PNG', '', '', true, 150, '', false, false, 1, false, false, false);
				}
                // Close and output PDF document
                $pdf->Output('example_009.pdf', 'I');
            }
        }

        if ($result) {
            $msg = 'Data saved successfully.';
            echo json_encode(array('success' => true, 'msg' => $msg));
        } else {
            $msg = 'Something went wrong.';
            echo json_encode(array('success' => false, 'msg' => $msg));
        }
    }
}
?>
