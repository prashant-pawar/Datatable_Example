<!DOCTYPE html>
<html>
<head>
	<title>Question Answer</title>
</head>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css"/>
 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="text/javascript">
	function call_me($btn){
 		console.log($btn);
 	}
</script>

<body>
<div class="container-fluid">
	<button type="button" id="clnrecord" class="btn btn-primary mr-4 mb-4 mt-2" data-toggle="modal" onclick="call_me(this);" data-target="#mymodal">Add Question
</button>

<!-- The Modal -->
<div class="modal" id="mymodal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="modal_title">Add Question</h4>
        <button type="button" class="close"  data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">

      	<!-- Add Record Form -->
       	<form id="frm" name="frm">
       		<div class="form-group">
       			<label for="question">Question:</label>
       			<input type="text" id="question" name="question" class="form-control">
       		</div>
       		<div class="form-group">
       			<label for="question">Opt_1</label>
       			<input type="text" id="opt_1" name="opt_1" class="form-control">
       		</div>
       		<div class="form-group">
       			<label for="question">Opt_2</label>
       			<input type="text" id="opt_2" name="opt_2" class="form-control">
       		</div>
       		<div class="form-group">
       			<label for="question">Opt_3</label>
       			<input type="text" id="opt_3" name="opt_3" class="form-control">
       		</div>
       		<div class="form-group">
       			<label for="question">Opt_4</label>
       			<input type="text" id="opt_4" name="opt_4" class="form-control">
       		</div>
       		<div class="form-group">
       			<label for="question">Answer</label>
       			<input type="text" id="true_ans" name="true_ans" class="form-control">
       				<input type="hidden" id="question_id"  name="question_id" class="form-control">
       		</div>
       	
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
      	<button type="submit" value="submit" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>
	<table class="table table-striped" id="tbl">
		<thead>
			<tr>
			<th >ID</th>
			<th >Qestions</th>
			<th >Opt_1</th>
			<th >Opt_2</th>
			<th >Opt_3</th>
			<th >Opt_4</th>
			<th >True_ans</th>
			<th >Action</th>
		</tr>
	</thead>

	<tbody id="tbody">
		
	</tbody>
		
	</table>
</div>

<script>
	 $(document).ready(function() {
	 	show_record();

	 		//DataTable Methods



	 		//Clear Record	
	 	$('#clnrecord').on('click', function(event) {
	 		event.preventDefault();
	 		$('input[name=question_id]').val("");
	 		$('input[name=question]').val("");
	 		$('input[name=opt_1]').val("");
	 		$('input[name=opt_2]').val("");
	 		$('input[name=opt_3]').val("");
	 		$('input[name=opt_4]').val("");
	 		$('input[name=true_ans]').val("");
	 	});	
	 	

	 	/*This Function used for Delete Record*/
	 	$('table tbody').on('click', '#delete', function(event) {
	 		event.preventDefault();
	 		var id=$(this).attr('data-id');
	 		$.ajax({
	 			url: 'question_master/delete_record',
	 			type: 'POST',
	 			dataType: 'json',
	 			data: {question_id:id},
	 		})
	 		.done(function() {
	 			$("#tbl").DataTable().ajax.reload();
	 			//show_record();
	 		})
	 		.fail(function() {
	 			console.log("error");
	 		})
	 		.always(function() {
	 			console.log("complete");
	 		});
	 		
	 	});


	 		/*This Function used for Edit Record*/
	 	$('table tbody').on('click', '#edit', function(event) {
	 		event.preventDefault();
			var id=$(this).attr('data-id');
			$.ajax({
				url: 'question_master/edit_record',
				type: 'POST',
				dataType: 'json',
				data: {question_id:id},
			})
			.done(function(response) {
				$('#mymodal').modal('show');
				$('input[name=question_id]').val(response['question_id']);
				$('input[name=question]').val(response['question']);
				$('input[name=opt_1]').val(response['opt_1']);
				$('input[name=opt_2]').val(response['opt_2']);
				$('input[name=opt_3]').val(response['opt_3']);
				$('input[name=opt_4]').val(response['opt_4']);
				$('input[name=true_ans]').val(response['true_ans']);
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
			
			
	 	});

	 		/*Show Record In Table */
	 	function show_record()
	 	{
	 		var DataTable=$('#tbl').DataTable({
	 		"processing":true,
	 		"serverSide":true,
	 		"order":[],
	 		"ajax":{
	 			url:"question_master/fetch_user",
	 			type:"POST",
	 			dataType:"json",

	 		"columnDefs":[
	 		{
	 			"targets":[0,3,4],
	 			"orederable":false,
	 		}
	 		],	
	 	}
	 });
	 		/*$.ajax({
	 			url: 'question_master/show_data',
	 			type: 'POST',
	 			dataType: 'json',
	 		})
	 		.done(function(response) {
	 			var html="";
	 			var id=1;
	 			var length=response.length;
	 			for (var i = 0; i < length; i++) {
	 				html+="<tr><td>"+id+"</td><td>"+response[i]['question']+"</td><td>"+response[i]['opt_1']+"</td><td>"+response[i]['opt_2']+"</td><td>"+response[i]['opt_3']+"</td><td>"+response[i]['opt_4']+"</td><td>"+response[i]['true_ans']+"</td><td><button id='edit' class='btn btn-warning' data-id="+response[i]['question_id']+">Edit</button><button id='delete' class='btn btn-danger ml-2' data-id="+response[i]['question_id']+">Delete</button></td></tr>";
	 				id++;
	 				$('#tbody').html(html);
	 				
	 			}
	 		})
	 		.fail(function() {
	 			console.log("error");
	 		})
	 		.always(function() {
	 			console.log("complete");
	 		});*/
	 		
	 	}

	 	/*insert record into Table*/

	 	$('#frm').on('submit',function(event) {
	 		event.preventDefault();
	 		var datastring=$(this).serialize();
	 		$.ajax({
	 			url: 'question_master/add_record',
	 			type: 'POST',
	 			dataType: 'json',
	 			data: datastring,
	 		})
	 		.done(function(response) {
	 			$('#mymodal').modal('hide');
	 			//show_record();
	 			$("#tbl").DataTable().ajax.reload();
	 		})
	 		.fail(function() {
	 			console.log("error");
	 		})
	 		.always(function() {
	 			console.log("complete");
	 		});
	 		
	 	});
	 });
</script>

</body>
</html>