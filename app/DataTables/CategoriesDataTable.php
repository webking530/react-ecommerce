<?php

/**
 * Category DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    Category
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Category;
use Yajra\DataTables\Services\DataTable;

class CategoriesDataTable extends DataTable
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
            ->addColumn('status', function ($category) {

                $class = ($category->status == 'Inactive') ? 'danger' : 'success';
                $url = url('admin/category_update?type=status&id='.$category->id );
                $status = '<a href="'.$url.'"  class="btn btn-xs btn-'.$class.'">'.$category->status.'</a>';

                return $status;
            })
            ->addColumn('featured', function ($category) {

                $class = ($category->featured == 'No') ? 'danger' : 'success';
                $url = url('admin/category_update?type=featured&id='.$category->id );
                $featured = '<a href="'.$url.'" class="btn btn-xs btn-'.$class.'">'.$category->featured.'</a>';

                return $featured;
            })
            ->addColumn('browse', function ($category) {

                $class = ($category->browse == 'No') ? 'danger' : 'success';
                $disabled = ($category->parent_id == '0') ? '' : 'disabled';
                $disabled_a = ($category->parent_id == '0') ? 'true' : 'false';
                $url = url('admin/category_update?type=browse&id='.$category->id );
                $browse = '<a href="'.$url.'"  onclick="return '.$disabled_a.'" '.$disabled.' class="btn btn-xs btn-'.$class.'">'.$category->browse.'</a>';

                return $browse;
            })
            ->addColumn('action', function ($category) {
                $edit = '<a href="'.url(ADMIN_URL.'/edit_category/'.$category->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
                $delete = '<a data-href="'.url(ADMIN_URL.'/delete_category/'.$category->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';

                return $edit.'&nbsp;'.$delete;
            })
            ->rawColumns(['status','featured','browse','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Category $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Category $model)
    {
        return $model->select(['id', 'title', 'parent_id','status', 'status','featured','browse']);
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
                    ->addAction(["printable" => false])
                    ->minifiedAjax()
                    ->dom('lBfr<"table-responsive"t>ip')
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
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'parent_id', 'name' => 'parent_id', 'title' => 'Parent Id'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            ['data' => 'featured', 'name' => 'featured', 'title' => 'Featured'],
            ['data' => 'browse', 'name' => 'browse', 'title' => 'Browse'],
        );
    }


    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'categories_' . date('YmdHis');
    }
}