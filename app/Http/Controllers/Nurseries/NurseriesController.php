<?php

namespace App\Http\Controllers\Nurseries;

use App\Http\Controllers\Controller;
use App\Http\Services\PaginateService;
use Database\DB;
use Views\View;

class NurseriesController extends Controller
{
    private PaginateService $paginate;

    const LIMIT_ITEM_PAGE = 1;

    public function __construct()
    {
        $this->paginate = new (PaginateService::class)($_GET);
    }

    public function index():View
    {
        $offset = $this->paginate->offset(self::LIMIT_ITEM_PAGE);
        $last_page = $this->paginate->lastPage('nurseries');
        $paginate = $this->paginate->arrayPaginate(self::LIMIT_ITEM_PAGE, $last_page);

        $nurseries = DB::select("SELECT * FROM nurseries LIMIT ? OFFSET ?", [self::LIMIT_ITEM_PAGE, $offset])->fetchAll();
        return new View('nurseries.nurseries', ['nurseries' => $nurseries, 'paginate' => $paginate]);
    }
}