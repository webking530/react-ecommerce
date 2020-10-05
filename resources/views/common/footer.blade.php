<div id="cls_footer">
	<div class="cls_footer" id="footer">
		<div class="col-lg-12 d-flex align-items-center flex-wrap">
			<ul class="col-lg-7 col-12 d-flex align-items-center flex-wrap cls_footerul">
				@foreach($company_pages as $company_page)
				<li>
					<a href="{{ url($company_page->url) }}">
						{{ $company_page->name }}
					</a>
				</li>
				@endforeach
			</ul>
			<ul class="col-lg-5 col-12 d-flex align-items-center flex-wrap cls_footerul justify-content-end">
				@foreach($join_us as $join)
				<li>
					<a href="{{$join->value}}">
						<i class="icon-{{ $join->name }}-square"></i>
					</a>
				</li>
				@endforeach
			</ul>
			
		</div>
	</div>
	<div class="cls_loading"></div>
</div>