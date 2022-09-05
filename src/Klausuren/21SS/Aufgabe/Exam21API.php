<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Exam21API extends Page
{
private $gameId = -1;

    protected function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData():array
    {
        $queryResult = array();
        $counter = 0;
        $sql = "SELECT count(*) AS playing FROM gameDetails WHERE gameId = $this->gameId";
        $recordset = $this->_database->query($sql);
        if (!$recordset) throw new Exception("Fehler in Abfrage: ".$this->_database->error);

        while ($record = $recordset->fetch_assoc()) {
            $queryResult[$counter] = $record;
            $counter = $counter+1;
        }
        $recordset->free();
 
        return $queryResult;
    }

    protected function generateView():void
    {
        header("Content-Type: application/json; charset=UTF-8");
        $data = $this->getViewData();
        $serializedData = json_encode($data);
        echo $serializedData;
    }

    protected function processReceivedData():void
    {
        parent::processReceivedData();
        if (isset($_GET["gameId"]) && is_numeric($_GET["gameId"])) {
            $this->gameId = (int) $_GET["gameId"];
        }
    }

    public static function main():void
    {
        try {
            $page = new Exam21API();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Exam21API::main();