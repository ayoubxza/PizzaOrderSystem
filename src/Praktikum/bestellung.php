<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€


require_once './Page.php';


class Bestellung extends Page
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
        $query  = "SELECT * FROM article";
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
        $this->generatePageHeader('Bestellung');
echo<<<HTML
        <script src="bestellung.js"></script>
        <section id="bestellung">
        <form action="bestellung.php" method="post" accept-charset="UTF-8">
        <h1>Bestellung</h1>
        <section id="speisekarte">
        <h2>Speisekarte</h2>
HTML;
        foreach ($data as $article) {
            $articleID = htmlspecialchars($article['article_id']);
            $name = htmlspecialchars($article['name']);
            $price = (float)$article['price']; // Preis formatieren
            $picture = htmlspecialchars($article['picture']);

            echo <<<HTML

                <article onclick="addPizza(this); enableButtons();"
                data-article-id="$articleID"  data-name="$name"  data-price="$price">
                    <h3>$name</h3>
                    <img src="Bilder/$picture" alt="$name" height="100" width="150">      
                    <p class="price">Preis: $price €</p>
                </article>
HTML;
        }

        echo <<<HTML

            </section>
            <section>
                <h2>Warenkorb</h2>
                <select name="Pizzenauswahl[]" size="10" multiple required id = "warenkorb" onchange="calculatePrice();" >
                </select>
                <p>Gesamt: <span id="total-price">0,00 €</span></p>
            </section>
            <section>
                <h2>Adresse</h2>
                <label for="adresse">Adresse eingeben:</label>
                <input tabindex="0" type="text" id="adresse" name="adresse" required onkeyup="enableButtons();">
                <br>
                <input id = "DeleteAll" type="button" tabindex="1" value="Alles löschen" onclick="deleteAllPizza(this);">
                <input id = "DeleteOne" type="button" tabindex="2" value="Pizza löschen" onclick="deletePizza(this);">
                <input id = "Order" type="submit" tabindex="3" value="Bestellen" name="bestell_button" onclick="orderPizza();">
            </section>
        </form>
        </section>
HTML;
        $this->generatePageFooter();
        }
    private function getArticleId($article_name): int
    {
        $article_name = $this->_database->real_escape_string($article_name);
        $sql = "
        SELECT article_id
        FROM pizzaservice.article
        WHERE name = '$article_name'";
        $recordset = $this->_database->query($sql);
        $record = $recordset->fetch_assoc();
        if (isset($record['article_id'])){
            return (int)$record['article_id'];
        }
        throw new Exception('Article \'' . $article_name . ' not found!');
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


        if(isset($_POST) && isset($_POST['bestell_button'])) {
            if(!isset($_POST['adresse']))
                return;
            $adresse = $this->_database->real_escape_string($_POST['adresse']);
            $SQL = "INSERT INTO pizzaservice.ordering (address)
                    VALUES ('$adresse');";
            $result = $this->_database->query($SQL);
            if(!$result) {
                throw new Exception("database request error: " . $this->_database->error);
            }
            $ordering_id = $this->_database->insert_id;
            foreach ($_POST['Pizzenauswahl'] as $pizza) {
                $pizza = $this->_database->real_escape_string($pizza);
                $articleID = $this->getArticleId($pizza);
                $SQL = "INSERT INTO pizzaservice.ordered_article (article_id,ordering_id)
                    VALUES ($articleID,$ordering_id);";
                 $result = $this->_database->query($SQL);
                if(!$result)
                    throw new Exception("database request error: " . $this->_database->error);
            }
            session_start();
            $_SESSION['ordering_id'] = $ordering_id;
            header('Location: kunde.php'); die;

        }
    }

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


Bestellung::main();
