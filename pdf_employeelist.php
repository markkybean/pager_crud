<?php

require_once('lib/ezpdfclass/class/class.ezpdf.php');
require_once('db_config.php');

// Check if the ezpdf class file exists
$ezpdf_path = 'lib/ezpdfclass/class/class.ezpdf.php';
if (!file_exists($ezpdf_path)) {
    die('ezpdf class file not found.');
}

// Check if the POST variable is set
$txt_repoutput = isset($_POST['txt_repoutput']) ? $_POST['txt_repoutput'] : 'pdf';

try {
    // Initialize PDF object based on user input
    if ($txt_repoutput == 'pdf') {
        $pdf = new Cezpdf('Letter', 'portrait');
    } else {
        $pdf = new tab_ezpdf('Letter', 'portrait');
    }

    // Select font
    $pdf->selectFont("lib/ezpdfclass/fonts/Helvetica.afm");

    // Set up header
    $xheader = $pdf->openObject();
    $pdf->saveState();

    $xfsize = 10;
    $xtop = 750;

    $pdf->ezPlaceData(25, $xtop, "<b>SAMPLE HEADER</b>", 12, 'left');

    $xtop -= 30;

    // Draw top line
    $pdf->line(25, $xtop, 587, $xtop);

    $xtop -= 10;

    $xleft1 = array();
    $xleft1[0] = 25;
    $xleft1[1] = $xleft1[0] + 150;
    $xleft1[2] = $xleft1[1] + 150;
    $xleft1[3] = $xleft1[2] + 60;
    $xleft1[4] = $xleft1[3] + 190;

    $pdf->ezPlaceData($xleft1[0], $xtop, "Full Name", $xfsize, 'left');
    $pdf->ezPlaceData($xleft1[1], $xtop, "Address", $xfsize, 'left');
    $pdf->ezPlaceData($xleft1[2], $xtop, "Gender", $xfsize, 'left');
    $pdf->ezPlaceData($xleft1[3], $xtop, "Contact No.", $xfsize, 'left');
    $pdf->ezPlaceData($xleft1[4], $xtop, "Salary", $xfsize, 'right');

    $xtop -= 5;
    // Draw second line
    $pdf->line(25, $xtop, 587, $xtop);

    // Restore state and add header to all pages
    $pdf->restoreState();
    $pdf->closeObject();
    $pdf->addObject($xheader, 'all');

    // Fetch employee data
    $xqry = "SELECT * FROM employeefile";
    $xstmt = $link_id->prepare($xqry);
    $xstmt->execute();

    // Display employee details
    while ($xrs = $xstmt->fetch(PDO::FETCH_ASSOC)) {
        $xtop -= 15;
        $pdf->ezPlaceData($xleft1[0], $xtop, $xrs["fullname"], $xfsize, 'left');
        $pdf->ezPlaceData($xleft1[1], $xtop, $xrs["address"], $xfsize, 'left');
        $pdf->ezPlaceData($xleft1[2], $xtop, $xrs["gender"], $xfsize, 'left');
        $pdf->ezPlaceData($xleft1[3], $xtop, $xrs["contactnum"], $xfsize, 'left');
        $pdf->ezPlaceData($xleft1[4], $xtop, number_format($xrs["salary"], 2), $xfsize, 'right');
    }

    // Output PDF
    $pdf->ezStream();
} catch (Exception $e) {
    // Handle any exceptions that occur during PDF generation
    echo 'Error generating PDF: ' . $e->getMessage();
}
