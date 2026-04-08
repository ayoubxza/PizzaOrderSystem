<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';


class Kunde extends Page
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


    protected function getViewData():array
    {
        session_start();
        if(!isset($_SESSION['ordering_id'])){
            header('Location: bestellung.php'); die;
            return[];
        }
        $ordering_id = $_SESSION['ordering_id'];
        $query = "SELECT A.name, O.status, O.ordered_article_id, A.article_id
                 FROM pizzaservice.article A, pizzaservice.ordered_article O
                 WHERE A.article_id = O.article_id AND O.ordering_id = ?";
        $stmt = $this->_database->prepare($query);
        $stmt->bind_param('i', $ordering_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if(!$result) {
                   throw new Exception("database request error: " . $this->_database->error);
               }
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        return $data;
    }


    protected function generateView():void
    {
        $data = $this->getViewData();
        $this->generatePageHeader('Kunde');
    echo<<<HTML
        <script src ="StatusUpdate.js"></script>
        <section id='bestellung'>
            <form action="bestellung.php" method="post" accept-charset="UTF-8">
        <h1> Kunde </h1>
        <section id="Bestellungen">
            <h2>Bestellungen</h2>

HTML;
        foreach ($data as $pizza){
            $article_id = htmlspecialchars((string)$pizza["article_id"]);
            $name = htmlspecialchars($pizza["name"]);
            $status = htmlspecialchars((string)$pizza["status"]);
            $ordered_article_id = htmlspecialchars((string)$pizza["ordered_article_id"]);
            
            $status_str = $this->statusToString($status);

            echo<<<HTML
                <article id= $article_id>
                    <fieldset>
                        <h2>$name: <span id="$ordered_article_id"> $status_str </span></h2>
                    </fieldset>
                </article>

HTML;
            }
        echo <<<HTML
                </section>
            </form>
        </section>
HTML;
        $this->generatePageFooter();
    }

    private function statusToString($status): string
    {
     switch ($status){
            case '0':
                return "Bestellt";
            case '1':
                return "Im Ofen";
            case '2':
                return "fertig";
            case '3':
                return "unterwegs";
            case '4':
                return "geliefert";
            default:
                return "ungültig";
     }
    }


    protected function processReceivedData():void
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
    }

    public static function main():void
    {
        try {
            $page = new Kunde();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            //header("Content-type: text/plain; charset=UTF-8");
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}


Kunde::main();

