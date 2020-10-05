@foreach($categories as $category)
<li class="{{ isset($is_child) ? '' : 'cls_li' }}">
	<a href="{{ url('shop/browse/'.$category->title) }}" class="{{ isset($is_child) ? '' : 'onsale_title' }}">
		{{ $category->title }}
	</a>
	<ul class="{{ isset($is_child) ? 'onsale_subcategory' : 'onsale_category' }}">
		@include('products.category_list_item', ['categories' => $category->childs,'is_child' => true])
	</ul>
</li>
@endforeach