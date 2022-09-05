<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€
/**
 * Class Bestellung for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 *
 * PHP Version 7.4
 *
 * @file     Bestellung.php
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
class Bestellung extends Page
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
        //fetch data for this view from the database
        $queryResult = array();
        $counter = 0;
        $sql = "SELECT * FROM article";
        $recordset = $this->_database->query($sql);
        if (!$recordset) throw new Exception("Fehler in Abfrage: ".$this->_database->error);

		// to do: return array containing data
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
        $this->generatePageHeader('Bestellung'); //to do: set optional parameters
        // to do: output view of this page
        echo '<h2>Speisekarte</h2>';
        foreach ($data as &$value) {
            $value["name"]=htmlspecialchars($value["name"]);
            $value["price"]=htmlspecialchars($value["price"]);
            $value["picture"]=htmlspecialchars($value["picture"]);
            echo <<<EOT
                <article>
                    <img src="../images/$value[picture]" alt="gif of a pizza" title="pizza image" />
                    <br>$value[name]
                    <br>$value[price]
                </article>
EOT;
        }
        unset($value);

        echo <<<EOT
        <section>
            <h2>Warenkorb</h2>
            <form action="bestellung.php" method="post" accept-charset="UTF-8">
            <label for="Warenkorb">Pizza:</label>
                <select Name="Warenkorb[]" id="warenkorb" tabindex="1" Size="3" multiple>  
                    <option selected value="1"> Salami </option>  
                    <option value="2"> Magherita </option>  
                    <option value="3"> Hawaii </option>   
                </select> 

                <span>14.50€</span>

                <div>
                    <label for="adresse">Adresse:</label>
                    <input type="text" name="adresse" id="adresse" value="" size="30" maxlength="40" placeholder="Ihre Adresse" required/>
                </div>
                <input type="reset" name="löschen" value="Alle Löschen" onclick="">
                <input type="button" name="ausgewählt löschen" value="Auswahl Löschen" onclick="">
                <input type="submit" name="bestellen" value="Bestellen" onclick="">
            </form> 
        </section>
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
        if(isset($_POST["Warenkorb"]) && isset($_POST["adresse"])){   
            $address = $_POST["adresse"];

            for($i=0; $i<sizeof($_POST["Warenkorb"]); ++$i){
                $pizza[$i] = $_POST["Warenkorb"][$i];
            }
            if(strlen($address) <= 0){
                throw new Exception("Bitte geben Sie eine Adresse an!");
            }
            else{
                $sqlInsertAddress = sprintf("INSERT INTO ordering (address) VALUES ('%s')", $this->_database->real_escape_string($address));
                $this->_database->query($sqlInsertAddress);
                $orderingID = $this->_database->insert_id;
                $_SESSION["bestellung_id"] = $orderingID;
                for($i=0; $i<sizeof($_POST["Warenkorb"]); ++$i){
                    $sqlInsertPizza = sprintf("INSERT INTO ordered_article (ordering_id, article_id, status) VALUES ('%d', '%d', '%d')", (int)$orderingID, (int)$pizza[$i], 0);
                    $this->_database->query($sqlInsertPizza);
                }
            }
            header('Location: bestellung.php');
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
            $page = new Bestellung();
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
Bestellung::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >