<?php

namespace Action;

use Repository\CommentRepository;
use Service\DinnerPriceFounder;
use Service\RestaurantMenuCsvReader;

class IndexAction extends AbstractAction
{
    public function run()
    {
        $this->render('index');
    }
}
