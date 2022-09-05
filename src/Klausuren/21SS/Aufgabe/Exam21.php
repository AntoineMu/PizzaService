<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Exam21 extends Page
{

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
        $sql = "SELECT * FROM games";
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
        $data = $this->getViewData();
        $this->generatePageHeader('Spielplanung'); //to do: set optional parameters

        $readyGame = false;

        foreach($data as $value){
            if($value["status"] == 1 || $value["status"] == 2){
                $readyGame = true;
                break;
            }
        }
        
        echo <<<EOT
        <body onload="pollData()">
            <header> <img src="Logo.png" alt="Logo der Seite" title="Logo"/> </header>
EOT;
        if($readyGame){
            echo <<<EOT
                <h2>$value[datetime] Uhr gegen $value[opposingTeam]</h2>
                <div id="zusagen">Zusagen Spieler:innen <span id="players">?</span></div>
                <form action="Exam21.php" id="planung" method="post">
                    <input type="submit" value="Planung abschließen">
                    <input type="hidden" id="teamId" value=$value[id] name="teamID">
                </form>
EOT;
        }else{
            echo "<h2>kein Aktuelles Spiel</h2>";
        }
        echo <<<EOT
            <div>
                <h2>Spiele</h2>
                <table>
                    <tr>
                        <th>Datum</th>
                        <th>Team</th>
                        <th>Status</th>
                    </tr>
EOT;
        unset($value);
        $statusValue = array('zukünftig', 'in Planung', 'Planung abgeschlossen', 'vorbei');
        foreach($data as $value){
            $value["datetime"] = htmlspecialchars($value["datetime"]);
            $value["opposingTeam"] = htmlspecialchars($value["opposingTeam"]);
            $statusName = $statusValue[$value["status"]];
            $statusName = htmlspecialchars($statusName);
            echo <<<EOT
                <tr>
                    <td>$value[datetime]</td>
                    <td>$value[opposingTeam]</td>
                    <td>$statusName</td>
                </tr>
EOT;
        }
        unset($value);
        echo " </table>  </div> </body>";

        $this->generatePageFooter();
    }

    protected function processReceivedData():void
    {
        parent::processReceivedData();

        if(isset($_POST["teamID"])){
            $sqlUpdate = sprintf("UPDATE games SET status='%d' WHERE id='%d'", 2, $_POST["teamID"]);
            $this->_database->query($sqlUpdate);

            header('Location: Exam21.php');
            die();
        }
    }

    public static function main():void
    {
        try {
            $page = new Exam21();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}


Exam21::main();

