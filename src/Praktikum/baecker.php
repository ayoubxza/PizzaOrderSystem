<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€

require_once './Page.php';

class Bäcker extends Page
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
        $query =    "SELECT ordered_article.article_id, ordered_article.ordering_id, ordered_article.ordered_article_id, ordered_article.status, article.name
                    FROM ordered_article 
                    JOIN article ON ordered_article.article_id = article.article_id
                    WHERE ordered_article.status < 2 "; //

        $result = $this->_database->query($query);

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
        $this->generatePageHeader('Baecker');
        echo<<<HTML
        <section id="bestellung">
        <h1> Bäcker </h1>
        <form id ="formid" action="baecker.php" method="post" accept-charset="UTF-8">
        <script>
        // Refresh the page every 10 seconds
        setTimeout(function() {
        window.location.reload();
        }, 10000);
        </script>
HTML;
        foreach ($data as $order) {
            $pizzaID = htmlspecialchars($order['article_id']);
            $status = htmlspecialchars($order['status']);
            $article_name = htmlspecialchars($order['name']);
            $orderingID = htmlspecialchars($order['ordering_id']);
            $ordered_article_id = htmlspecialchars($order["ordered_article_id"]);
            $bestellt_checked = ($status === '0') ? 'checked' : '';
            $im_ofen_checked = ($status === '1') ? 'checked' : '';
            $fertig_checked = ($status === '2') ? 'checked' : '';

            echo<<<HTML

            <article id="$ordered_article_id">
            <fieldset>
                <h2> Bestellung {$orderingID}: {$article_name} </h2>
                <p> 
                    Bestellt
                    <input type="radio" name="$ordered_article_id" $bestellt_checked value="0" onclick="document.forms['formid'].submit()" >
                </p>
                <p>
                    im Ofen
                    <input type="radio" name="$ordered_article_id" $im_ofen_checked value="1" onclick="document.forms['formid'].submit()" >    
                </p>
                <p>
                    Fertig
                    <input type="radio" name="$ordered_article_id" $fertig_checked  value="2" onclick="document.forms['formid'].submit()">
                </p>
            </fieldset>
            </article>
HTML;
        }
        echo<<<HTML

        </form>
        </section>
HTML;
    $this->generatePageFooter();
    }

    public function dump($str): void
    {
        echo '<pre>';
        var_dump($str);
        echo '</pre>';
    }

    protected function processReceivedData():void
    {
        parent::processReceivedData();
        if(isset($_POST) && count($_POST)){//nur wenn ich was in dem Post habe soll das aufgerufen wurde
            foreach ($_POST as $key => $value) {
                $this->_database->query("UPDATE ordered_article SET status = $value WHERE ordered_article_id = $key");
            }
            header('Location: baecker.php'); die();
        }
    }


    public static function main():void
    {
        try {
            $page = new Bäcker();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}


Bäcker::main();
