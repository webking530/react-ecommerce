<?php

/**
 * ReturnPolicy DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    ReturnPolicy
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\ReturnPolicy;
use Yajra\DataTables\Services\DataTable;

class ReturnPolicyDataTable extends DataTable
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
            ->addColumn('action', function ($return_policy) {
                $edit = '<a href="'.url('admin/edit_return_policy/'.$return_policy->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>';
                $delete = '<a data-href="'.url('admin/delete_return_policy/'.$return_policy->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>';
              
                return $edit.'&nbsp;'.$delete;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \ReturnPolicy $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ReturnPolicy $model)
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
            ['data' => 'days', 'name' => 'days', 'title' => 'Days'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'return_policy_' . date('YmdHis');
    }
}