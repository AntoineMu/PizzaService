<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€
/**
 * Class Baecker for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 7.4
 *
 * @file     Baecker.php
 * @package  Page Templates
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @version  3.1
 */

// to do: change name 'Baecker' throughout this file
require_once './Page.php';

/**
 * This is a template for top level classes, which represent
 * a complete web page and which are called directly by the user.
 * Usually there will only be a single instance of such a class.
 * The name of the template is supposed
 * to be replaced by the name of the specific HTML page e.g. baker.
 * The order of methods might correspond to the order of thinking
 * during implementation.
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 */
class Baecker extends Page
{
    // to do: declare reference variables for members 
    // representing substructures/blocks

    /**
     * Instantiates members (to be defined above).
     * Calls the constructor of the parent i.e. page class.
     * So, the database connection is established.
     * @throws Exception
     */
    protected function __construct()
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }

    /**
     * Cleans up whatever is needed.
     * Calls the destructor of the parent i.e. page class.
     * So, the database connection is closed.
     */
    public function __destruct()
    {
        parent::__destruct();
    }

    /**
     * Fetch all data that is necessary for later output.
     * Data is returned in an array e.g. as associative array.
	 * @return array An array containing the requested data. 
	 * This may be a normal array, an empty array or an associative array.
     */
    protected function getViewData():array
    {
        // fetch data for this view from the database
        $queryResult = array();
        $counter = 0;
        $sql = "SELECT article.name, ordered_article.ordered_article_id, ordered_article.status, ordered_article.ordering_id FROM ordered_article INNER JOIN article ON article.article_id=ordered_article.article_id WHERE status<2";
        $recordset = $this->_database->query($sql);
        if (!$recordset) throw new Exception("Fehler in Abfrage: ".$this->_database->error);

		// return array containing data
        while ($record = $recordset->fetch_assoc()) {
           $queryResult[$counter] = $record;
           $counter = $counter+1;
        }
        $recordset->free();

        return $queryResult;
    }

    /**
     * First the required data is fetched and then the HTML is
     * assembled for output. i.e. the header is generated, the content
     * of the page ("view") is inserted and -if available- the content of
     * all views contained is generated.
     * Finally, the footer is added.
	 * @return void
     */
    protected function generateView():void
    {
		$data = $this->getViewData();
        $this->generatePageHeader('Bäcker'); 
        
        // to do: output view of this page
        //echo '<meta http-equiv="refresh" content="10">';    //refreshed die seite alle 10 sekunden
        
        foreach($data as $value){
            $value["ordering_id"] = htmlspecialchars($value["ordering_id"]);
            $value["name"] = htmlspecialchars($value["name"]);
            $value["status"] = htmlspecialchars($value["status"]);
            $value["ordered_article_id"] = htmlspecialchars($value["ordered_article_id"]);
            $oaid = $value["ordered_article_id"];
            $ck = "checked";
            $bestellt = "";
            $imOfen = "";
            $fertig = "";
            if($value["status"] == 0){
                $bestellt = $ck;
            }else if($value["status"] == 1){
                $imOfen = $ck;
            }else if($value["status"] == 2){
                $fertig = $ck;
            }
            echo <<<EOT
                <form action="baecker.php" method="post">
                    <h3>Bestellung $value[ordering_id] - Pizza $value[name]</h3>
                    <label><input type="radio" name="status" id="bestellt$oaid" value="0" $bestellt>bestellt</label>
                    <label><input type="radio" name="status" id="imOfen$oaid" value="1" $imOfen>im Ofen</label>
                    <label><input type="radio" name="status" id="fertig$oaid" value="2" $fertig>fertig</label>
                    <input type="submit" name="submit" id="submit$oaid" value="Absenden">
                    <input type="hidden" name="ordered_article_id" value="$oaid">   
                </form>
EOT;
        }
        unset($value);
        $this->generatePageFooter();
    }

    /**
     * Processes the data that comes via GET or POST.
     * If this page is supposed to do something with submitted
     * data do it here.
	 * @return void
     */
    protected function processReceivedData():void
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
        if(isset($_POST["status"]) && isset($_POST["ordered_article_id"])){
            $sqlUpdate = sprintf("UPDATE ordered_article SET status ='%d' WHERE ordered_article_id = '%d'", (int)$_POST["status"], (int)$_POST["ordered_article_id"]);
            $this->_database->query($sqlUpdate);

            header('Location: baecker.php');
            die();
        }
    }

    /**
     * This main-function has the only purpose to create an instance
     * of the class and to get all the things going.
     * I.e. the operations of the class are called to produce
     * the output of the HTML-file.
     * The name "main" is no keyword for php. It is just used to
     * indicate that function as the central starting point.
     * To make it simpler this is a static function. That is you can simply
     * call it without first creating an instance of the class.
	 * @return void
     */
    public static function main():void
    {
        try {
            $page = new Baecker();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page. 
// That is input is processed and output is created.
Baecker::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >