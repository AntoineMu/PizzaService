<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€
/**
 * Class Fahrer for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 7.4
 *
 * @file     Fahrer.php
 * @package  Page Templates
 * @author   Bernhard Kreling, <bernhard.kreling@h-da.de>
 * @author   Ralf Hahn, <ralf.hahn@h-da.de>
 * @version  3.1
 */

// to do: change name 'PageTemplate' throughout this file
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
class Fahrer extends Page
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
        // to do: fetch data for this view from the database
        $queryResult = array();
        $counter = 0;
        $sql = "SELECT ordering.ordering_id, ordering.address, article.article_id, ordered_article.status, article.name, article.price FROM ordering RIGHT JOIN ordered_article ON ordering.ordering_id=ordered_article.ordering_id RIGHT JOIN article ON ordered_article.article_id=article.article_id WHERE status>=2 AND status<4 ORDER BY ordering_id asc";
        $recordset = $this->_database->query($sql);
        if(!$recordset){
            throw new Exception("Abfrage fehlgeschlagen: ".$this->_database->error);
        }

		// to do: return array containing data
        while ($record = $recordset->fetch_assoc()) {
            $queryResult[$counter] = $record;
            $counter = $counter+1;
        }
        $recordset->free();
        //print_r($queryResult);

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
        $this->generatePageHeader('Fahrer'); //to do: set optional parameters
        //print_r($data);
        $numberOrders = 1;
        for($i=0; $i<sizeof($data)-1; ++$i){
            if($data[$i]["ordering_id"] != $data[$i+1]["ordering_id"]){
                $numberOrders += 1;
            }
        }
        $index = 0;
        $ck = "checked";
        for($i=0; $i<$numberOrders; ++$i){
            $priceTotal = 0.0;
            $address = $data[$index]["address"];
            $address = htmlspecialchars($address);
            $bestellNr = $data[$index]["ordering_id"];
            $bestellNr = htmlspecialchars($bestellNr);
            $fertig = "";
            $unterwegs = "";
            $geliefert = "";
            if($data[$index]["status"] == 2){
                $fertig = $ck;
            }else if($data[$index]["status"] == 3){
                $unterwegs = $ck;
            }else if($data[$index]["status"] == 4){
                $geliefert = $ck;
            }
            echo <<<EOT
                
                <div>
                <hr>
                <h4>Bestellung $bestellNr</h4>
                <h2>$address</h2>
EOT;
            for($j=$index; $j<sizeof($data)-1; ++$j){
                $priceTotal += (double)$data[$j]["price"];
                if($data[$j]["ordering_id"] == $data[$j+1]["ordering_id"]){
                    echo $data[$j]["name"];
                    echo ",";
                }else{
                    //letzte zeile ausgeben
                    $namePizza = $data[$index]["name"];
                    echo <<<EOT
                        $namePizza
                        <br><h3>Summe: $priceTotal €</h3>
                        <form action="fahrer.php" method="post">
                            <br><label><input type="radio" name="status" value="2" $fertig>fertig</label>
                            <br><label><input type="radio" name="status" value="3" $unterwegs>unterwegs</label>
                            <br><label><input type="radio" name="status" value="4" $geliefert>ausgeliefert</label>
                            <br><input type="submit" name="submit" value="absenden">
                            <input type="hidden" name="ordering_id" value="$bestellNr">
                        </form>
                        </div>
EOT;
                    $index = $j+1;
                    break;
                }
            }
        }
            
        $fertig = "";
        $unterwegs = "";
        $geliefert = "";
        if($data[$index+1]["status"] == 2){
            $fertig = $ck;
        }else if($data[$index+1]["status"] == 3){
            $unterwegs = $ck;
        }else if($data[$index+1]["status"] == 4){
            $geliefert = $ck;
        }

        $namePizza = $data[$index+1]["name"];
        $namePizza = htmlspecialchars($namePizza);
        $bestellNr = $data[$index+1]["ordering_id"];
        $bestellNr = htmlspecialchars($bestellNr);
        $priceTotal += (double)$data[$index+1]["price"];
                echo <<<EOT
                    $namePizza
                    <br><h3>Summe: $priceTotal €</h3>
                    <form action="fahrer.php" method="post">
                        <br><label><input type="radio" name="status" value="2" $fertig>fertig</label>
                        <br><label><input type="radio" name="status" value="3" $unterwegs>unterwegs</label>
                        <br><label><input type="radio" name="status" value="4" $geliefert>ausgeliefert</label>
                        <br><input type="submit" name="submit" value="absenden">
                        <input type="hidden" name="ordering_id" value="$bestellNr">
                    </form>
                    </div>
EOT;
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
        if(isset($_POST["status"]) && isset($_POST["ordering_id"])){
            $sqlUpdate = sprintf("UPDATE ordered_article SET status ='%d' WHERE ordering_id = '%d'", (int)$this->_database->real_escape_string($_POST["status"]), (int)$this->_database->real_escape_string($_POST["ordering_id"]));
            $this->_database->query($sqlUpdate);

            header('Location: fahrer.php');
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
            $page = new Fahrer();
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
Fahrer::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >