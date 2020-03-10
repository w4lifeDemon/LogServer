// загрузка сообщений из БД в контейнер messages
	function show_messages(index)
	{
		$.ajax({
			url: "show.php",
			cache: false,
			data: "id_log=".$this->objSource->strId ."&index="+ index ,
			success: function(html){
				$("#messages").html(html);
				
			}
		});
	}

	function next_post(){
		var t1 = parseInt( $("#this1").text());
		var max = parseInt( $("#max").text());
		if((t1 + 10) < max){
			show_messages(t1 + 10);
		}
	
		}

	function prev_post(){
		var t1 = parseInt( $("#this1").text());
		
		if((t1 - 10) >= 0){
			show_messages(t1 - 10);
		}
		}
		
	$(document).ready(function(){

		show_messages(0);

		
		
		// контроль и отправка данных на сервер в фоновом режиме при нажатии на кнопку отправить сообщение
		$("#myForm").submit(function(){

		
			
			var msg  = $("#comment_textarea").val();
			
			$.ajax({
				type: "POST",
				url: "action.php",

				data: "msg="+msg+"&id_log=".$this->objSource->strId ."",
				success: function(html){
				$("#messages").html(html);
					show_messages(0);
				$("#comment_textarea").val( "");
			   }
			});
			
			return false;
		});
		
	});
