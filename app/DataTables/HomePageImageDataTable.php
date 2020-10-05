<?php

/**
 * HomePage Image DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    HomePage Image
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Feature;
use Yajra\DataTables\Services\DataTable;

class HomePageImageDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->of($query)
            ->addColumn('image', function ($feature) {   
                return '<img src="'.$feature->image_url.'" width="200" height="100">';
            })
            ->addColumn('action', function ($feature) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_homepage_image/'.$feature->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
              
                return $edit;
            })
            ->rawColumns(['image','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Feature $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Feature $model)
    {
        return $model->all();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->addAction()
                    ->minifiedAjax()
                    ->dom('r<"table-responsive"t>ip')
                    ->orderBy(0,'ASC')
                    ->buttons(
                        ['csv','excel', 'print', 'reset']
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return array(
            'id',
            'image',
            'title',
            'description',
            'order', 
        );
    }
}