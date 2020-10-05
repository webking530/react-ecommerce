<?php

/**
 * Products DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    Products
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Services\DataTable;
use Auth;

class ProductsDataTable extends DataTable
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
            ->addColumn('status', function ($products) {
                $class = ($products->product_status == 'Inactive') ? 'danger' : 'success';
                $url = url('admin/status_update?id='.$products->product_id);
                $status = '<a href="'.$url.'" class="btn btn-xs btn-'.$class.'">'.$products->product_status.'</a>';

                return $status;
            })
            ->addColumn('approval', function ($products) {
                $class = ($products->admin_status == 'Waiting') ? 'danger' : 'success';
                $url = ($products->admin_status == 'Waiting') ? url('admin/set_approval?id='.$products->product_id ): 'javascript:;';
                $approval = '<a href="'.$url.'" class="btn btn-xs btn-'.$class.'">'.$products->admin_status.'</a>';

                return $approval;
            })
            ->addColumn('featured', function ($products) {
                $class = ($products->is_featured == 'No') ? 'danger' : 'success';
                $url = url('admin/set_update?type=is_featured&id='.$products->product_id );
                $featured = '<a href="'.$url.'" class="btn btn-xs btn-'.$class.'">'.$products->is_featured.'</a>';

                return $featured;
            })
            ->addColumn('original_price', function ($products) {
                return $products->products_prices_details->original_currency_symbol.$products->price;
            })
            ->addColumn('recommend', function ($products) {

                $class = ($products->is_recommend == 'No') ? 'danger' : 'success';
                $url = url('admin/set_update?type=is_recommend&id='.$products->product_id);
                $recommend = '<a href="'.$url.'" class="btn btn-xs btn-'.$class.'">'.$products->is_recommend.'</a>';

                return $recommend;
            })
            ->addColumn('editor', function ($products) {

                $class = ($products->is_editor == 'No') ? 'danger' : 'success';
                $url = url('admin/set_update?type=is_editor&id='.$products->product_id);
                $editor = '<a href="'.$url.'" class="btn btn-xs btn-'.$class.'">'.$products->is_editor.'</a>';

                return $editor;
            })
            ->addColumn('header', function ($products) {

                $class = ($products->headers == 'No') ? 'danger' : 'success';
                $url = url('admin/set_update?type=is_header&id='.$products->product_id);
                $header= '<a href="'.$url.'" class="btn btn-xs btn-'.$class.'">'.$products->headers.'</a>';

                return $header;
            })
            ->addColumn('action', function ($products) {
                $edit = (Auth::guard('admin')->user()->can('update-products')) ? '<a href="'.url(ADMIN_URL.'/products/edit/'.$products->product_id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>' : '';

                $delete = (Auth::guard('admin')->user()->can('delete-products')) ? '<a data-href="'.url(ADMIN_URL.'/products/delete/'.$products->product_id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>' : '';

                return $edit.'&nbsp;'.$delete;   

            })
            ->rawcolumns(['status','approval','featured','recommend','editor','header','action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Product $model)
    {
        return $model->join('users', function($join) {
            $join->on('users.id', '=', 'products.user_id');
        })
        ->join('products_prices_details', function($join) {
            $join->on('products_prices_details.product_id', '=', 'products.id');
        })
        ->join('categories', function($join) {
            $join->on('categories.id', '=', 'products.category_id');
        })
        ->select(['products.id as product_id', 'products.title as product_name', 'products.status as product_status', 'products.created_at as product_created_at', 'products.updated_at as product_updated_at','users.full_name as host_name','categories.title as category_name', 'products.*', 'users.*', 'products_prices_details.*','categories.*','products.is_header as headers','products.id as id']);
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
                    ->orderBy(0)
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
            ['data' => 'product_id', 'name' => 'products.id', 'title' => 'Id'],
            ['data' => 'product_name', 'name' => 'products.title', 'title' => 'Name'],
            ['data' => 'category_name', 'name' => 'categories.title', 'title' => 'Category'],
            ['data' => 'host_name', 'name' => 'users.full_name', 'title' => 'Merchant Name'],
            ['data' => 'original_price', 'name' => 'original_price', 'title' => 'Price', 'orderable' => false, 'searchable' => false],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'orderable' => false, 'searchable' => false],
            ['data' => 'approval', 'name' => 'approval', 'title' => 'Admin Status', 'orderable' => false, 'searchable' => false],
            ['data' => 'featured', 'name' => 'featured', 'title' => 'Featured', 'orderable' => false, 'searchable' => false],
            ['data' => 'recommend', 'name' => 'recommend', 'title' => 'Recommend', 'orderable' => false, 'searchable' => false],
            ['data' => 'editor', 'name' => 'editor', 'title' => 'Editor', 'orderable' => false, 'searchable' => false],
            ['data' => 'header', 'name' => 'header', 'title' => 'Header', 'orderable' => false, 'searchable' => false],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'products_' . date('YmdHis');
    }
}