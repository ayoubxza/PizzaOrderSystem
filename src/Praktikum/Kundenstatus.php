<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€

// to do: change name 'PageTemplate' throughout this file
require_once './Page.php';


class PageTemplate extends Page
{


    protected function __construct()
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }


    public function __destruct()
    {
        parent::__destruct();
    }


    protected function getViewData(): array
    {
        session_start();
        if(!isset($_SESSION['ordering_id'])){
            header('Location: bestellung.php'); die;
        }
        $ordering_id = $_SESSION['ordering_id'];
        $query = 'SELECT O.status, O.ordered_article_id
              FROM pizzaservice.ordered_article O
              WHERE O.ordering_id = ?';
        $stmt = $this->_database->prepare($query);
        $stmt->bind_param('i', $ordering_id);  // 'i' für integer, da ordering_id eine Zahl ist
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;  // Füge jede Zeile zu dem Array hinzu
        }
        $result->free();
        $stmt->close();
        return $data;
    }


    protected function generateView(): void
    {

        $data = $this->getViewData();
        header("Content-Type: application/json; charset=UTF-8");
        $serializedData = json_encode($data, JSON_PRETTY_PRINT);

        if (json_last_error() !== JSON_ERROR_NONE)
            echo json_encode(["error" => "Fehler beim Generieren der JSON-Daten: " . json_last_error_msg()]);

        else
            echo $serializedData;

    }



    protected function processReceivedData():void
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
    }

    public static function main():void
    {
        try {
            $page = new PageTemplate();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}


PageTemplate::main();

