$(document).ready(function() {
	$('.modal_link').click(function(event){
		event.preventDefault();
		$('#myModal').removeData("modal");
		$('#myModal').load($(this).attr('href'),function(){
			$('#myModal').modal();
  		});
	});

	$("#visited_directory").change(function(){
		if($(this).val()==$("#directory_separator").val()) {
			var r_location=$("#web_root_directory").val()+'index.php';
		} else {
			var r_location=$("#web_root_directory").val()+'index.php?dir='+$(this).val();
		}
		location.href=r_location;
	});

	$("#check_all").change(function(){
		if($(this).is(':checked')) {
			$('table tbody input:checkbox').prop('checked',true);
		} else {
			$('table tbody input:checkbox').prop('checked',false);
		}
	});

	$("#file_list input:checkbox").change(function(){
		if($("#file_list input:checked").length) {
			$("#rename,#delete,#download").removeClass('disabled');
		} else {
			$("#rename,#delete,#download").addClass('disabled');
		}
	});

	$('#myModal').on('shown.bs.modal', function () {
		if($("#myModal form").attr('action')=='rename.php') {
			$("#file_list tbody input:checked").each(function(index){
				var form_group=$("#myModal form .form-group:first").clone();
				form_group.find('input').val($(this).val());
				form_group.find('input:first').attr('name','files['+index+'][old_name]');
				form_group.find('input:eq(1)').attr('name','files['+index+'][new_name]').attr('id','new_name'+index);
				form_group.find('label').attr('for','new_name'+index).append($(this).parent().find('img').clone());
				$("#myModal form .modal-body").append(form_group);
				form_group.show();
			});
		}
	});

	$("#upload").click(function(){
		window.open($(this).attr('href'),"_blank","top=100,left=100,width=800,height=600");
		return false;
	});

	$("#download").click(function(){
		if(!$('table input:checked').length) {
			alert(select_download_file);
			return false;
		}

		if($('table input:checked').length<2) {
			location.href=$('table input:checked:first').parent().parent().find('td:eq(1) a').attr('href');
			return false;
		}

		var aa=[];
		folder_name=[];
		$('table input:checked').each(function(index){
			aa[index]=$(this).val();
			if($(this).parent().find('input:eq(1)').val()=='directory') {
				folder_name[index]=$(this).parent().find('input:eq(2)').val();
			}
		});

		$.post($(this).attr('href'),{'dir':$("#current_folder").val(),'files':aa,'json':true},function(data){
			if(data.result=='success') {
				location.href=data.zip_file;
			} else {
				alert(data.message);
			}
		},'json');

		return false;
	});

	$("#delete").click(function(){
		if(!$('table input:checked').length) {
			alert(select_delete_file);
			return false;
		}

		var aa=[];
		folder_exists=false;
		folder_name=[];
		$('table input:checked').each(function(index){
			aa[index]=$(this).val();
			if($(this).parent().find('input:eq(1)').val()=='directory') {
				folder_exists=true;
				folder_name[index]=$(this).parent().find('input:eq(2)').val();
			}
		});

		if(folder_exists) {
			if(!confirm('('+folder_name.join()+')'+notice_cascade_delete)) {
				return false;
			}
		}

		$.post($(this).attr('href'),{'dir':$("#current_folder").val(),'files':aa},function(data){
			if(data.result=='success') {
				delete_result=false;
				var message='';
				
				if($("#current_folder").val()==$("#directory_separator").val()) {
					location.href=$("#web_root_directory").val()+'index.php';
				} else {
					location.href=$("#web_root_directory").val()+'index.php?dir='+$("#current_folder").val();
				}
			} else {
				alert(data.message);
			}
		},'json');

		return false;
	});
});
