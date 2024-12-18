function Block_packs(all_items, parent_elm, block_id, hide_add_to_cart_button, num_elements_per_row)
{
	this.elements_count = num_elements_per_row;
	this.position = 0;
	this.speed = 70;
	this.max_loading_time = 70; // 7 sec
	this.obj_image = [];
	this.config = [];
	this.all_items = all_items;
	this.parent_elm = parent_elm;
	this.all_items_count = all_items.length;
	this.block_packs_items = all_items;
	this.block_packs_items_count = this.all_items_count;
	this.block_id = block_id;

	this.config['elements'] = 0;
	this.config['use_delay'] = false; 
	this.config['direction'] = [];
	this.hide_add_to_cart_button = hide_add_to_cart_button;

	this.init();
	
};

Block_packs.prototype = {
	init: function()
	{
		var _block_packs = this;
		
		$('.cm-block_packs-left', this.parent_elm).click(function () {
			_block_packs.shift_left(_block_packs);
		});
		$('.cm-block_packs-right', this.parent_elm).click(function () {
			_block_packs.shift_right(_block_packs);
		});
		
		
		$('.cm-block_packs-category', this.parent_elm).click(function () {
			_block_packs.change_elements($(this).attr('name'));
		});
		
		this.change_elements(0);
	},
	
	delay: function(time, func)
	{
		setTimeout(func, time);
	},
	
	use_pagination: function(idx, direction)
	{
		this.position = idx;
		
		this.set_pagination(this.block_packs_items_count, (idx == 0) ? 0 : idx / this.elements_count);
		this.get_html_items(idx, this.block_packs_items, true, direction);
	},
	
	set_pagination: function(count, idx)
	{
		var pages = Math.round(count / this.elements_count);
		var ret = "";
		var add2end = 0;
		var add2start = 0;
		var output_class;
		
		var end_dif = this.elements_count - idx;
		if (end_dif <= this.elements_count && end_dif >= 0) {
			add2end += end_dif;
		}
		
		var start_dif = this.elements_count - (pages - idx - 1);
		if (start_dif <= this.elements_count && start_dif >= 0) {
			add2start += start_dif;
		}
		
		pagination_list = $(".cm-block_packs-pagination-list", this.parent_elm);
		
		if (pages == 1) {
			pagination_list.css('visibility', "hidden");
		} else {
			for (var i = 0; i < pages; i++) {
				if (i >= (idx - this.elements_count - add2start) && i <= (idx + this.elements_count + add2end)) {
					if (i == idx) {
						output_class = 'pagination-selected-page';
						href = i + 1;
					} else {
						output_class = '';
						href = "<a name='" + i*this.elements_count + "' class='cm-block_packs-pagination'>" + (i+1) + "</a>";
					}
					ret += "<span class='" + output_class + "'>" + href + "</span>&nbsp;";
				}
			}
			
			pagination_list.html(ret);
			pagination_list.css('visibility', "visible");
			
			var _block_packs = this;
			
			$('.cm-block_packs-pagination', this.parent_elm).click(function () {
				_block_packs.use_pagination(parseInt($(this).attr('name')), 'right');
			});
		}
	},
	
	add_spacers: function(items, count)
	{
		var differ;
		
		((count % this.elements_count) == 0) ? differ = 0 : differ = this.elements_count - (count % this.elements_count);
	
		for (i = count; i < count + (differ); i++) {
			items[i] = {name: '', link: '', image: images_dir + '/spacer.gif', width: 0, height: 0};
		}
	
		count += differ;
	
		return {items: items, count: count};
	},
	
	filter_elements: function(cat_id, items)
	{
		var elements = [];
	
		for (i in items) {
			if (items[i].cat_id == cat_id) {
				elements.push(items[i]);
			}
		}
	
		ret = this.add_spacers(elements, elements.length);
	
		return {items: ret.items, count: ret.count};
	},
	
	change_elements: function(cat_id)
	{
		if (cat_id == 0) {
			ret = this.add_spacers(this.all_items, this.all_items.length);
			this.block_packs_items = ret.items;
			this.block_packs_items_count = ret.count;
			
		} else {
			ret = this.filter_elements(cat_id, this.all_items);
			this.block_packs_items = ret.items;
			this.block_packs_items_count = ret.count;
		}
		
		this.mark_category(cat_id);
		
		if (this.block_packs_items_count <= this.elements_count) {
			$(".cm-block_packs-left", this.parent_elm).hide();
			$(".cm-block_packs-right", this.parent_elm).hide();
			
		} else {
			$(".cm-block_packs-left", this.parent_elm).show();
			$(".cm-block_packs-right", this.parent_elm).show();
		}
		
		this.position = 0;
		this.set_pagination(this.block_packs_items_count, 0);
		this.get_html_items(0, this.block_packs_items);
	},
	
	mark_category: function(cat_id)
	{
		$('.active', this.parent_elm).removeClass('active');
		
		$('.cm-block_packs-category', this.parent_elm).each(function(key, elem) {
			if (parseInt($(elem).attr('name')) == cat_id) {
				$(elem).addClass('active');
			}
		});
	},
	
	load_images: function()
	{
		use_delay = this.config['use_delay'];
		elements = this.config['elements'];
		direction = this.config['direction'];
		_block_packs = this;
		
		if (use_delay) {
			_block_packs.delay(_block_packs.speed,function(){
				$(".cm-block_packs-item-" + direction[0], _block_packs.parent_elm).html(elements[direction[0]]);
				_block_packs.delay(_block_packs.speed,function(){
					$(".cm-block_packs-item-" + direction[1], _block_packs.parent_elm).html(elements[direction[1]]);
					_block_packs.delay(_block_packs.speed,function(){
						$(".cm-block_packs-item-" + direction[2], _block_packs.parent_elm).html(elements[direction[2]]);
						_block_packs.delay(_block_packs.speed,function(){
							$(".cm-block_packs-item-" + direction[3], _block_packs.parent_elm).html(elements[direction[3]]);
						});
					});
				});
			});
			
		} else {
			for (i in elements) {
				$(".cm-block_packs-item-" + i, this.parent_elm).html(elements[i]);
			}
		}
		
		jQuery.toggleStatusBox('hide');
	},
	
	check_images: function(count)
	{
		var is_loaded = true;
		
		for (var j = 0; j <= this.elements_count - 1; j++) {
			if (typeof(this.obj_image[j]) == 'object' && !this.obj_image[j].complete) {
				is_loaded = false;
			}
		}
		
		count++;
		
		if (is_loaded || count > this.max_loading_time) {
			this.load_images();
		} else {
			var _block_packs = this;
			setTimeout(function () {
				_block_packs.check_images(count)
			}, 100);
		}
	},
	
	get_html_items: function(idx, items, use_delay, direction)
	{
		var elements = [];
		var direct = [];
		var preimages = [];
		var is_flash = false;

		loaded = 0;
		
		if (direction == "left") {
			direction = ['0', '1', '2', '3'];
		} else {
			direction = ['3', '2', '1', '0'];
		}
	
		jQuery.toggleStatusBox('show', lang.loading);
	
		if ($(".hot-block_packs-list", this.parent_elm)) {
			var j = 0;
			for (var i = idx; i <= idx + this.elements_count - 1; i++) {
				
				if (items[i].link == '') {
					elements.push('<div class="image-border center">&nbsp;</div>');
				} else {
					is_flash = (items[i].image.substring(items[i].image.length, items[i].image.length - 3) == 'swf')? true : false;
					if (is_flash == false) {
						
						product_id = items[i].link.substring(items[i].link.indexOf('product_id=')+11);
						//var currencySymbol = currencies.primary;
						
						item_html = ( 
						'<div class="image-border center" style="position:relative; width:200px; margin-left:10px; padding-bottom:10px;">');
						if(items[i].is_new !=''){
							item_html += '<div class="label_new_fullwidth"></div>';
							//item_html += '<div class="isnew">'+items[i].is_new+'</div>';
						}
						if(items[i].is_ngo !='N'){
							item_html += '<div class="label_ngo"></div>';
							//item_html += '<div class="isnew">'+items[i].is_new+'</div>';
						}
						//item_html += '<a href="' + items[i].link + '"><img src="' + items[i].image + '" width="' + (items[i].width*0.82) + '" height="' + (items[i].height*0.82) + '" alt="' + items[i].name + '" border="0" /></a><br />';
						item_html += '<a href="' + items[i].link + '"><img src="' + items[i].image + '" width="' + (items[i].width) + '" height="' + (items[i].height) + '" alt="' + items[i].name + '" border="0" /></a><br />';
							
						
            
       if(items[i].pro_discount !='0')
		  { 
    		   item_html += '<div class="label_discount_fullwidth_postion '+items[i].style +'">'+items[i].pro_discount+'% <br /> OFF '+'</div>';
	      }
							item_html += '<div class="clear"><a href="' + items[i].link + '"><span class="block_packs-title">' + this.truncate(items[i].name) +'</span></a></div>'+'<div class="">';
						
						
						//Only add in the list (sale) price if the item is on sale
						if (items[i].list_price > items[i].price)
							item_html += '<span class="block_packs-strikethrough">'+currencySymbol+items[i].list_price+'</span>&nbsp;&nbsp';
						
						item_html += '<span class="block_packs-price price">'+currencySymbol+items[i].price+'</span></div>';
						
						
						if(this.hide_add_to_cart_button=='N')
						{
						item_html = item_html+(
						'<div class="">'+
							'<form action="index.php" method="post" name="product_form_760007" enctype="multipart/form-data" class="cm-disable-empty-files cm-ajax"> '+
							'<input type="hidden" name="result_ids" value="cart_status,wish_list" /> '+
							'<input type="hidden" name="redirect_url" value="index.php?currency=USD" /> '+
							'<input type="hidden" name="product_data['+product_id+'][product_id]" value="'+product_id+'" /> '+
							''+
							
							'<div class="buttons-container center"> '+
								'<span  class="button-submit-action">'+
									'<input  type="submit" name="dispatch[checkout.add..'+product_id+']"  value="Add to Cart" />'+
								'</span>'+
							'</div>'+
							'</form>'+
						'</div>');	
						}
						item_html = item_html+(
								
						'</div>'	
						);
						elements.push(item_html);	
					} else {
						elements.push('<div class="image-border center"><a href="' + items[i].link + '"><object width="' + items[i].width + '" height="' + items[i].height + '" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param value="' + items[i].image + '" name="movie"><param value="high" name="quality"><param value="transparent" name="wmode"><param value="sameDomain" name="allowScriptAccess"><embed width="' + items[i].width + '" height="' + items[i].height + '" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="sameDomain" wmode="transparent" quality="high" src="' + items[i].image + '"></object></a><br /><a href="' + items[i].link + '"><span class="block_packs-title">' + this.truncate(items[i].name) +'</span></a></div>');
					}
				}
				
				preimages[j] = items[i].image;
				j++;
				
			}
		}

		this.config['elements'] = elements;
		this.config['direction'] = direction;
		this.config['use_delay'] = use_delay;
		
		for (j = 0; j <= this.elements_count - 1; j++) 
		{
			
				is_flash = (preimages[j].substring(preimages[j].length, preimages[j].length - 3) == 'swf')? true : false;
				if (is_flash == false) {
					this.obj_image[j] = new Image();
					this.obj_image[j].src = preimages[j];
				} else {
					this.obj_image[j] = false;
				}
				
			
		}
		
		this.check_images(0);
	},
	
	shift_left: function(block_packs_class)
	{
		block_packs_class.position = (block_packs_class.position == 0) ? block_packs_class.block_packs_items_count - this.elements_count : block_packs_class.position - this.elements_count;
		block_packs_class.use_pagination(block_packs_class.position, 'left');
	},
	
	shift_right: function(block_packs_class)
	{
		block_packs_class.position = ((block_packs_class.position + this.elements_count) == block_packs_class.block_packs_items_count) ? 0 : block_packs_class.position + this.elements_count;
		block_packs_class.use_pagination(block_packs_class.position, 'right');
	},
	
	truncate: function(str)
	{
		if (str) {
			var len = 24;
	
			if (str.length > len) {
				str = str.replace(/&#?[a-z0-9]{2,8};/i, '');
				str = str.substring(0, len);
				str = str.replace(/\w+$/, '') + '...';
			}
			return str;
		}
	
		return '';
	}
};