<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Chargeback extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url', 'cookie'));
    }

    public function index() {
        $this->load->view('index');
    }

    public function test() {
        $this->load->view('test');
    }

}

$array = array(
    [0] => array(
        'mer_id' => "1850257",
        'mer_name' => '247MedCall',
    ),
    [1] => array(
        'mer_id' => "1864029",
        'mer_name' => 'Accelerated Servicing Group LLC',
    ),
    [2] => array(
        'mer_id' => "1857334",
        'mer_name' => 'Alpha Recovery Corp',
    ),
    [3] => array(
        'mer_id' => "1855813",
        'mer_name' => 'Amin &amp; Associates Inc',
    ),
    [4] => array(
        'mer_id' => "1856429",
        'mer_name' => 'Apple Recovery LLC',
    ),
    [5] => array(
        'mer_id' => "1855288", 'mer_name' => 'AR Solutions Inc',
    ),
    [6] => array(
        'mer_id' => "1880424", 'mer_name' => 'Arial Business Services Inc',
    ),
    [7] => array(
        'mer_id' => "1854315", 'mer_name' => 'Assured Financial Partners',
    ),
    [8] => array(
        'mer_id' => "1852894", 'mer_name' => 'Atlantic Recovery Solutions',
    ),
    [9] => array(
        'mer_id' => "1850996", 'mer_name' => 'Bernardini Law Office PC',
    ),
    [10] => array(
        'mer_id' => "1858390", 'mer_name' => 'Bison Recovery Group',
    ),
    [11] => array(
        'mer_id' => "1856420", 'mer_name' => 'Brown and Joseph Ltd',
    ),
    [12] => array(
        'mer_id' => "1866538", 'mer_name' => 'CACi',
    ),
    [13] => array(
        'mer_id' => "1845842", 'mer_name' => 'Cherry Creek Strategic Advisors / Gryphon Trust',
    ),
    [14] => array(
        'mer_id' => "1854492", 'mer_name' => 'Commerce Investment Group',
    ),
    [15] => array(
        'mer_id' => "1876967", 'mer_name' => 'Crown Asset Management',
    ),
    [16] => array(
        'mer_id' => "1857207", 'mer_name' => 'D2 Management LLC',
    ),
    [17] => array(
        'mer_id' => "1869347", 'mer_name' => 'Delta Outsource Group Inc',
    ),
    [18] => array(
        'mer_id' => "1855289", 'mer_name' => 'Dynamic Recovery Solutions',
    ),
    [19] => array(
        'mer_id' => "1850647", 'mer_name' => 'Eastpoint Recovery Group Inc',
    ),
    [20] => array(
        'mer_id' => "1845124", 'mer_name' => 'EHS Card',
    ),
    [21] => array(
        'mer_id' => "1867071", 'mer_name' => 'Elevation Capital',
    ),
    [22] => array(
        'mer_id' => "1854504", 'mer_name' => 'Elggren & amp;Peterson, P.C.',
    ),
    [23] => array(
        'mer_id' => "1845123", 'mer_name' => 'ERT America Inc',
    ),
    [24] => array(
        'mer_id' => "1865172", 'mer_name' => 'Everest Receivable Services',
    ),
    [25] => array(
        'mer_id' => "1876966", 'mer_name' => 'Excelero',
    ),
    [26] => array(
        'mer_id' => "1849874", 'mer_name' => 'Fidelis Recovery Management LLC',
    ),
    [27] => array(
        'mer_id' => "1849766", 'mer_name' => 'First Choice Assets LLC',
    ),
    [28] => array(
        'mer_id' => "1849717", 'mer_name' => 'Fox & amp;Castle Inc',
    ),
    [29] => array(
        'mer_id' => "1855364", 'mer_name' => 'Frontline Asset Strategies LLC',
    ),
    [30] => array(
        'mer_id' => "1857663", 'mer_name' => 'G Reynolds Sims & amp;Associates, P.C.',
    ),
    [31] => array(
        'mer_id' => "1859288", 'mer_name' => 'Global Credit Recovery LLC',
    ),
    [32] => array(
        'mer_id' => "1851801", 'mer_name' => 'Halsted Financial Services LLC',
    ),
    [33] => array(
        'mer_id' => "1876968", 'mer_name' => 'J A Cambece Law Office P C',
    ),
    [34] => array(
        'mer_id' => "1853036", 'mer_name' => 'Kinum Inc',
    ),
    [35] => array(
        'mer_id' => "1851802", 'mer_name' => 'LCA Services',
    ),
    [36] => array(
        'mer_id' => "1853569", 'mer_name' => 'Legacy CMG',
    ),
    [37] => array(
        'mer_id' => "1851943", 'mer_name' => 'Machol &amp;Johannes',
    ),
    [38] => array(
        'mer_id' => "1861391", 'mer_name' => 'Michael W. Skop P.A. Law Office',
    ),
    [39] => array(
        'mer_id' => "1853660", 'mer_name' => 'Millennium Asset Management Group',
    ),
    [40] => array(
        'mer_id' => "1858438", 'mer_name' => 'Mintex',
    ),
    [41] => array(
        'mer_id' => "1850617", 'mer_name' => 'MNS and Associates',
    ),
    [42] => array(
        'mer_id' => "1854890", 'mer_name' => 'National Recovery Association LLC',
    ),
    [43] => array(
        'mer_id' => "1848534", 'mer_name' => 'Northeast Collection Bureau',
    ),
    [44] => array(
        'mer_id' => "1850375", 'mer_name' => 'OK Tree Account Administrator LLC / Round Two Recovery LLC',
    ),
    [45] => array(
        'mer_id' => "1859148", 'mer_name' => 'Oliphant Financial LLC',
    ),
    [46] => array(
        'mer_id' => "1875116", 'mer_name' => 'Oxygen Recovery Group',
    ),
    [47] => array(
        'mer_id' => "1859983", 'mer_name' => 'Performance Portfolio Management',
    ),
    [48] => array(
        'mer_id' => "1854318", 'mer_name' => 'Pinnacle Financial Partners',
    ),
    [49] => array(
        'mer_id' => "1853033", 'mer_name' => 'Premier Recovery Group',
    ),
    [50] => array(
        'mer_id' => "1848683", 'mer_name' => 'Profile Management Inc',
    ),
    [51] => array(
        'mer_id' => "1856436", 'mer_name' => 'Ramona Funding Group',
    ),
    [52] => array(
        'mer_id' => "1872762", 'mer_name' => 'Ratchford Law Group',
    ),
    [53] => array(
        'mer_id' => "1854802", 'mer_name' => 'Ratchford Law Group PC',
    ),
    [54] => array(
        'mer_id' => "1851481", 'mer_name' => 'Regional Recovery Group LLC',
    ),
    [55] => array(
        'mer_id' => "1856189", 'mer_name' => 'Rocky Mountain Recovery Group LLC',
    ),
    [56] => array(
        'mer_id' => "1870349", 'mer_name' => 'S/D Horan Real Estate Services LLC',
    ),
    [57] => array(
        'mer_id' => "1852126", 'mer_name' => 'S3 / Single Source Surgical LLC',
    ),
    [58] => array(
        'mer_id' => "1854869", 'mer_name' => 'Second Round LP',
    ),
    [60] => array(
        'mer_id' => "1857626", 'mer_name' => 'Southern Credit Adjusters Inc',
    ),
    [61] => array(
        'mer_id' => "1862315", 'mer_name' => 'Southern Credit Bureau',
    ),
    [62] => array(
        'mer_id' => "1859865", 'mer_name' => 'SOZO',
    ),
    [63] => array(
        'mer_id' => "1851412", 'mer_name' => 'Surgipalooza LLC',
    ),
    [64] => array(
        'mer_id' => "1861221", 'mer_name' => 'Taurus Law Group LLC',
    ),
    [65] => array(
        'mer_id' => "1867175", 'mer_name' => 'The Preferred Path',
    ),
    [66] => array(
        'mer_id' => "1851216", 'mer_name' => 'Upstate Management Services LLC',
    ),
    [67] => array(
        'mer_id' => "1851482", 'mer_name' => 'US Mortgage Resolution LLC',
    ),
    [68] => array(
        'mer_id' => "1849040", 'mer_name' => 'V &amp;R Recovery Inc',
    ),
    [69] => array(
        'mer_id' => "1848453", 'mer_name' => 'Wiedman &amp;Jankowski',
    ),
    [70] => array(
        'mer_id' => "1847085", 'mer_name' => 'Y2Payment Systems Inc',
    ),
    [71] => array(
        'mer_id' => "1848049", 'mer_name' => 'Zeller & amp;Associates',
    ),
    [71] => array(
        'mer_id' => "1851133", 'mer_name' => 'Zenith Financial Network Inc',
    )
);
