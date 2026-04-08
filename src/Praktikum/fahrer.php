<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€


require_once './Page.php';


class Fahrer extends Page
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
        $query = "
                    SELECT 
                        ordered_article.article_id, 
                        ordered_article.ordering_id, 
                        ordered_article.ordered_article_id, 
                        ordered_article.status, 
                        ordering.address 
                    FROM 
                        ordered_article
                    JOIN 
                        ordering 
                    ON 
                        ordered_article.ordering_id = ordering.ordering_id
                    GROUP BY
                        ordered_article.ordering_id
                    HAVING 
                        MIN(ordered_article.status >= 2) AND MAX(ordered_article.status < 4)
                ";
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
        $this->generatePageHeader('Fahrer');
        echo<<<HTML
        <section id='bestellung'>
        <form id ="formid" action="fahrer.php" method="post" accept-charset="UTF-8">
        <script>
        // Refresh the page every 10 seconds
        setTimeout(function() {
        window.location.reload();
        }, 10000);
        </script>
        <h1> Fahrer </h1>
 HTML;
        foreach ($data as $kundenInfo) {
            $articleId = htmlspecialchars($kundenInfo['article_id']);
            $orderingId = htmlspecialchars($kundenInfo['ordering_id']);
            $address = htmlspecialchars($kundenInfo['address']);
            $status = htmlspecialchars($kundenInfo['status']);
            $orderedArticleId = htmlspecialchars($kundenInfo['ordered_article_id']);
            $fertig = ($status === '2') ? 'selected' : '';
            $unterwegs = ($status === '3') ? 'selected' : '';
            $geliefert = ($status === '4') ? 'selected' : '';
            echo<<<HTML

            <article id="$orderingId">
                <fieldset>
                    <h2> $orderingId </h2>
                    <p>$address</p>
                    <label> Status:
                    <select name="$orderingId" id="$orderedArticleId" onchange="document.forms['formid'].submit()">
                        <option value="2" $fertig>Fertig</option>
                        <option value="3" $unterwegs>Unterwegs</option>
                        <option value="4" $geliefert>Geliefert</option>  
                    </select>
                    </label>
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


    protected function processReceivedData():void
    {
        parent::processReceivedData();
        if(isset($_POST) && count($_POST)){//nur wenn ich was in dem Post habe soll das aufgerufen wurde
            foreach ($_POST as $key => $value) {
                $value = $this->_database->real_escape_string(strval($value));
                $key = $this->_database->real_escape_string(strval($key));
                $this->_database->query("UPDATE ordered_article 
                                                SET status = $value 
                                                WHERE ordering_id = $key");
            }
            header('Location: fahrer.php'); die;
        }
    }


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


Fahrer::main();
