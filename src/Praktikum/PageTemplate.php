<?php declare(strict_types=1);
// UTF-8 marker äöüÄÖÜß€



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


    protected function getViewData():array
    {   

    }

    protected function generateView():void
    {
        $data = $this->getViewData(); //NOSONAR ignore unused $data
        $this->generatePageHeader('to do: change headline'); //to do: set optional parameters
        // to do: output view of this page
        $this->generatePageFooter();
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
            header("Content-type: text/html; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}


PageTemplate::main();

