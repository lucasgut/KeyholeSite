//	$.get('includes/check_new_items.php', 
function updateNews(){
	$.get('includes/check_new_items.php',function(newdata){
		if(newdata.length==3) 
		{
		$('#newdata').insertAfter('section > details');	
		
		// find out each new article, compute height, and set new heights for section and aside
		var sectionHeight = 75; // padding, margin, included elements
		$('section article').each(function(){
			sectionHeight = sectionHeight + $(this).height() + 10 + 25; // 10 comes from padding t b, 25 from margin top
		});
		asideHeight = 0;
		asideHeight = asideHeight + $('aside div').height();
		$('aside li').each(function(){
			asideHeight = asideHeight + $(this).height();
			if($(this).attr('class')=='title') asideHeight = asideHeight + 15;
		});
		
		if(asideHeight < sectionHeight) 
		{
		//	alert (sectionHeight);
			$('aside, section').animate({ height: sectionHeight },{queue:false}); // update height	
		}
		else
		{
		//	alert (asideHeight);
			$('aside, section').animate({ height: asideHeight },{queue:false}); // update height	
		}
		// done with column heights
		
		$('section article').fadeIn('slow');
		
		}
				
	});
	
}

function overlay()
{
	//create the overlay menu
	var opacity = '0.9'; // can be up to 1
	
	$('a[name=modal]').removeAttr('href');
	//javascript on, no need to redirect to a link here
	$('a[name=modal]').click(function ()
		{ 
		
		var rel = $(this).attr('rel');
		if(rel=='editform')
		{
			var id = $(this).attr('title');

			var article = $(this).parent().parent();			
			var details = article.children('details');
			var category = details.children('span.cat').html();
			var body = article.children('p').html();
			var title = article.children('h1').text();
		}
		
		var modal_content = $('.'+rel).html();
		$('#overlay-content div').html(modal_content);

		$('input.editid').val(id);
		$('input.edittitle').val(title);
		$('textarea.editbody').text(body);

		// next lines of code are required because a simple .attr('selected','selected')
		// did not seem to work
		// basically I am chainging selected option based on clicked article's category
		var oldvalue = $('select.editcategory option[selected]').val();
//		
		$('select.editcategory option[value='+category+']').text(oldvalue);
		$('select.editcategory option[value='+category+']').attr('value',oldvalue);			
//		
		$('select.editcategory option[selected]').attr('value',category);
		$('select.editcategory option[selected]').text(category);
		
//		$('select.category option[selected]').removeAttr('selected');
//		$('select.category option[value='+category+']').attr('selected','selected');	
		
		var maskHeight = $(document).height();  
		var maskWidth = $(document).width(); 
		var windowHeight = $(window).height();  
		var windowWidth = $(window).width(); 
		var contentWidth = $('#overlay-content').width(); // width
		var contentHeight = $('#overlay-content').height(); // and height of content area
		
		//Set height and width to mask to fill up the whole screen  
		$('#overlay-mask').css({'width':maskWidth,'height':maskHeight});
		$('#overlay-mask').css('opacity',opacity);
		$('#overlay-mask').css('display','block');
		
		// put the overlay content area in the center of the window
		$('#overlay-content').css('display','block');
		$('#overlay-content').css('left',(windowWidth-contentWidth)/2);
		$('#overlay-content').css('top',(windowHeight-contentHeight)/2);				
	});
		
	// move overlay content to center of the window
	$(window).resize(function () { 
		var maskHeight = $(document).height();  
		var maskWidth = $(window).width(); 
		var windowHeight = $(window).height();  
	 	var windowWidth = $(window).width();
		var contentWidth = $('#overlay-content').width(); // width
		var contentHeight = $('#overlay-content').height(); // and height of content area
		//Set height and width to mask to fill up the whole screen  
		$('#overlay-mask').css({'width':maskWidth,'height':maskHeight});
		$('#overlay-content').css({'left':(windowWidth-contentWidth)/2});
		$('#overlay-content').css({'top':(windowHeight-contentHeight)/2});
	});

	var $scrollingDiv = $("#overlay-content");	
	$(window).scroll(function() {			
		$scrollingDiv
			.stop()
			.animate({"marginTop": ($(window).scrollTop()) + "px"}, "fast" );			
	});			
	$('#overlay-mask').click(function () { $('#overlay-mask').css('display','none'); $('#overlay-content').css('display','none'); $('#overlay-content div').html(""); });
	$('.close').click(function () { $('#overlay-mask').css('display','none'); $('#overlay-content').css('display','none'); $('#overlay-content div').html(""); });
}

$(document).ready(function(){

	// column heights - equalize
	var asideHeight = 0; // store aside box height
	var contentHeight = 0; // store content box height
	asideHeight = $('aside').height(); // get height
	contentHeight = $('section').height(); // get height
	if(asideHeight < contentHeight) $("aside").height(contentHeight); // update height
		else $("section").height(asideHeight); // or update height
	// done with column heights	
	
	// next lines added after completed jquery ajax for categories sessions
	// they are needed to add the X on the preselected categories, on page load
	$("aside ul li a.selected-aside").append('<span>X</span>');
	var anyCatSelected = $('.selected-aside').size();
	if ( anyCatSelected != 0) $('.categories').attr('class','categories selected');
	var startdateset = $('#start_date').val();
	var enddateset = $('#end_date').val();
	if((startdateset.length==10)||(enddateset.length==10)) $('.daterange').attr('class','daterange selected');
	
	overlay();
	
	// change style for aside category selector and filters above content + ajax requests for updating selected categories
	$("aside ul li a").click(function (){
		var classAttr = $(this).attr('class');
		if (classAttr != 'selected-aside')
		{
			// update style class for category just clicked
			$(this).attr('class','selected-aside');
			var category = $(this).text();
			$(this).append('<span>X</span>');
			
			 // check filter legend status and update it if required
			var classAttr = $('.categories').attr('class');
			if(classAttr == 'categories not-selected') $('.categories').attr('class','categories selected');
			
			
			// now we need to make an ajax call to add our category to our selected categories session
			var jdata = "function=add&category="+category; 
			$.ajax({
				type: "POST",
				url: "includes/categories_sessions.php",
  				data: jdata 
			});
		}
		else
		{
			// update style class for category just clicked
			$(this).attr('class','');
			var span = $(this).children();
			$(span).remove();
			var category = $(this).text();
			
			// now we need to make an ajax call to remove our category to our selected categories session
			var jdata = "function=remove&category="+category; 
			$.ajax({
				type: "POST",
				url: "includes/categories_sessions.php",
  				data: jdata 
			});
			
			// check if there is any category selected
			// if not, update filter legend class
			var anyCatSelected = $('.selected-aside').size();
			if(anyCatSelected==0) 
			{	
				$('.categories').attr('class','categories not-selected');

				// now we need to make an ajax call to remove our category from our selected categories session
//				var jdata = "function=destroy&category=none"; 
//				$.ajax({
//					type: "POST",
//					url: "includes/categories_sessions.php",
//			  		data: jdata
//				});
			}
		}// from else
		
		// ajax requests to reload content and resize section + aside
		$('section article').fadeOut('slow', function(){ 
				// completion of fading current content out
				//$('section').animate({ height: sectionHeight });  // reset height of section to original value
				if($('section article').length>0) $('section article').remove();
				var oldcontent = $('section').html();
				
				$.get('includes/load_news.php', function(newdata){
					$('section').html(oldcontent+newdata);
					
					// find out each new article, compute height, and set new heights for section and aside
					var sectionHeight = 50; // padding, margin, included elements
					$('section article').each(function(){
						sectionHeight = sectionHeight + $(this).height() + 10 + 25; // 10 comes from padding t b, 25 from margin top
					});
					asideHeight = 0;
					asideHeight = asideHeight + $('aside div').height();
					$('aside li').each(function(){
						asideHeight = asideHeight + $(this).height();
						if($(this).attr('class')=='title') asideHeight = asideHeight + 15;
					});
					
					if(asideHeight < sectionHeight) 
					{
					//	alert (sectionHeight);
						$('aside, section').animate({ height: sectionHeight },{queue:false}); // update height	
					}
					else
					{
					//	alert (asideHeight);
						$('aside, section').animate({ height: asideHeight },{queue:false}); // update height	
					}
					// done with column heights
					
					$('section article').fadeIn('slow');
					
					overlay();
					
				});
			});
	});

	$('.date-pick').datePicker(); // start datepicker
		
	$('#start_date').change(function(){
		// the start date of date picker has been changed
		// ajax call to handle it
		var startdate = $(this).val();
		if(startdate!=null)
		{
			var classAttr = $('.daterange').attr('class');
			if(classAttr == 'daterange not-selected') $('.daterange').attr('class','daterange selected');	
		
				var jdata = "function=start&date="+startdate; 
				$.ajax({
					type: "POST",
					url: "includes/datepicker.php",
					data: jdata 
				});

					// ajax requests to reload content and resize section + aside
					$('section article').fadeOut('slow', function(){ 
							// completion of fading current content out
							//$('section').animate({ height: sectionHeight });  // reset height of section to original value
							if($('section article').length>0) $('section article').remove();
							var oldcontent = $('section').html();

							$.get('includes/load_news.php', function(newdata){
								$('section').html(oldcontent+newdata);

								// find out each new article, compute height, and set new heights for section and aside
								var sectionHeight = 50; // padding, margin, included elements
								$('section article').each(function(){
									sectionHeight = sectionHeight + $(this).height() + 10 + 25; // 10 comes from padding t b, 25 from margin top
								});
								asideHeight = 0;
								asideHeight = asideHeight + $('aside div').height();
								$('aside li').each(function(){
									asideHeight = asideHeight + $(this).height();
									if($(this).attr('class')=='title') asideHeight = asideHeight + 15;
								});

								if(asideHeight < sectionHeight) 
								{
								//	alert (sectionHeight);
									$('aside, section').animate({ height: sectionHeight },{queue:false}); // update height	
								}
								else
								{
								//	alert (asideHeight);
									$('aside, section').animate({ height: asideHeight },{queue:false}); // update height	
								}
								// done with column heights

								$('section article').fadeIn('slow');

								overlay();

							});
						});
		}
		
	});
	
	$('#end_date').change(function(){
		// the start date of date picker has been changed
		// ajax call to handle it
		var enddate = $(this).val();
		if(enddate!=null)
		{
			var classAttr = $('.daterange').attr('class');
			if(classAttr == 'daterange not-selected') $('.daterange').attr('class','daterange selected');	
		
				var jdata = "function=end&date="+enddate; 
				$.ajax({
					type: "POST",
					url: "includes/datepicker.php",
					data: jdata 
				});

					// ajax requests to reload content and resize section + aside
					$('section article').fadeOut('slow', function(){ 
							// completion of fading current content out
							//$('section').animate({ height: sectionHeight });  // reset height of section to original value
							if($('section article').length>0) $('section article').remove();
							var oldcontent = $('section').html();

							$.get('includes/load_news.php', function(newdata){
								$('section').html(oldcontent+newdata);

								// find out each new article, compute height, and set new heights for section and aside
								var sectionHeight = 50; // padding, margin, included elements
								$('section article').each(function(){
									sectionHeight = sectionHeight + $(this).height() + 10 + 25; // 10 comes from padding t b, 25 from margin top
								});
								asideHeight = 0;
								asideHeight = asideHeight + $('aside div').height();
								$('aside li').each(function(){
									asideHeight = asideHeight + $(this).height();
									if($(this).attr('class')=='title') asideHeight = asideHeight + 15;
								});

								if(asideHeight < sectionHeight) 
								{
								//	alert (sectionHeight);
									$('aside, section').animate({ height: sectionHeight },{queue:false}); // update height	
								}
								else
								{
								//	alert (asideHeight);
									$('aside, section').animate({ height: asideHeight },{queue:false}); // update height	
								}
								// done with column heights

								$('section article').fadeIn('slow');
								
								overlay();
								
							});
						});			
		}
		
	});
		
	$('aside div span').click(function(){
		// the start date of date picker has been changed
		// ajax call to handle it
		$('.daterange').attr('class','daterange not-selected');	
		$('#start_date').val('');
		$('#end_date').val('');
		
		var jdata = "function=clear&date=none"; 
		$.ajax({
			type: "POST",
			url: "includes/datepicker.php",
			data: jdata 
		});
		
			// ajax requests to reload content and resize section + aside
			$('section article').fadeOut('slow', function(){ 
					// completion of fading current content out
					//$('section').animate({ height: sectionHeight });  // reset height of section to original value
					if($('section article').length>0) $('section article').remove();
					var oldcontent = $('section').html();

					$.get('includes/load_news.php', function(newdata){
						$('section').html(oldcontent+newdata);

						// find out each new article, compute height, and set new heights for section and aside
						var sectionHeight = 50; // padding, margin, included elements
						$('section article').each(function(){
							sectionHeight = sectionHeight + $(this).height() + 10 + 25; // 10 comes from padding t b, 25 from margin top
						});
						asideHeight = 0;
						asideHeight = asideHeight + $('aside div').height();
						$('aside li').each(function(){
							asideHeight = asideHeight + $(this).height();
							if($(this).attr('class')=='title') asideHeight = asideHeight + 15;
						});

						if(asideHeight < sectionHeight) 
						{
						//	alert (sectionHeight);
							$('aside, section').animate({ height: sectionHeight },{queue:false}); // update height	
						}
						else
						{
						//	alert (asideHeight);
							$('aside, section').animate({ height: asideHeight },{queue:false}); // update height	
						}
						// done with column heights

						$('section article').fadeIn('slow');

						overlay();

					});
				});
		
	});	
	
	$('#newdata').click(function(){
		
		$('#newdata').insertAfter('footer');	
		$('#newdata').removeAttr('style');
		
		// ajax requests to reload content and resize section + aside
			$('section article').fadeOut('slow', function(){ 
					// completion of fading current content out
					//$('section').animate({ height: sectionHeight });  // reset height of section to original value
					if($('section article').length>0) $('section article').remove();
					var oldcontent = $('section').html();
					
					$.get('includes/load_new_news.php', function(newdata){
						$('section').html(oldcontent+newdata);

						// find out each new article, compute height, and set new heights for section and aside
						var sectionHeight = 75; // padding, margin, included elements
						$('section article').each(function(){
							sectionHeight = sectionHeight + $(this).height() + 10 + 25; // 10 comes from padding t b, 25 from margin top
						});
						asideHeight = 0;
						asideHeight = asideHeight + $('aside div').height();
						$('aside li').each(function(){
							asideHeight = asideHeight + $(this).height();
							if($(this).attr('class')=='title') asideHeight = asideHeight + 15;
						});

						if(asideHeight < sectionHeight) 
						{
						//	alert (sectionHeight);
							$('aside, section').animate({ height: sectionHeight },{queue:false}); // update height	
						}
						else
						{
						//	alert (asideHeight);
							$('aside, section').animate({ height: asideHeight },{queue:false}); // update height	
						}
						// done with column heights

						$('section article').fadeIn('slow');
						
						overlay();

					});
				});
		
	});
		
	$('.display').click(function(){ $(this).fadeOut(); $(this).remove(); });
		
	setInterval( "updateNews()", 10000 );	
			
});